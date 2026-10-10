<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

final class SocialPreview
{
    public function url($institution): string
    {
        $version = substr(hash('sha256', ($institution?->instituteName ?? '').'|'.($institution?->logo ?? '').'|'.config('media.public_base_url')), 0, 16);
        return route('socialPreview', ['v' => $version]);
    }

    /** Only fetch the server-configured media authority, never request-supplied URLs. */
    public function image($institution): string
    {
        $name = trim((string) ($institution?->instituteName ?? '')) ?: 'Official Website';
        $url = app(PublicMediaUrl::class)->institutionLogo($institution?->logo);
        $logo = $url ? Cache::remember('social-logo:'.hash('sha256', $url), 3600, function () use ($url) {
            try {
                $response = Http::timeout(3)->connectTimeout(2)->withoutRedirecting()->get($url);
                $bytes = $response->successful() ? $response->body() : '';
                $size = strlen($bytes) <= 5 * 1024 * 1024 ? @getimagesizefromstring($bytes) : false;
                return $size && $size[0] * $size[1] <= 16000000 ? $bytes : '';
            } catch (\Throwable) {
                return '';
            }
        }) : '';
        $directory = storage_path('app/social-previews');
        File::ensureDirectoryExists($directory);
        $path = $directory.'/social-preview-'.hash('sha256', 'v1|'.$name.'|'.$logo).'.png';
        if (is_file($path)) {
            $this->retainRecentPreviews($directory, $path);
            return $path;
        }

        $image = imagecreatetruecolor(1200, 630);
        for ($y = 0; $y < 630; $y++) {
            $t = $y / 630;
            imageline($image, 0, $y, 1200, $y, imagecolorallocate($image, 247 - (int) (16 * $t), 252 - (int) (12 * $t), 255));
        }
        $navy = imagecolorallocate($image, 17, 45, 86);
        $blue = imagecolorallocate($image, 0, 143, 203);
        $pale = imagecolorallocate($image, 209, 231, 245);
        $white = imagecolorallocate($image, 255, 255, 255);
        // Code-native campus silhouette: no third-party or uploaded background.
        imagefilledrectangle($image, 690, 405, 1199, 581, $pale);
        imagefilledpolygon($image, [665, 405, 945, 328, 1225, 405], $pale);
        for ($x = 722; $x < 1180; $x += 70) {
            imagefilledrectangle($image, $x, 434, $x + 34, 476, $white);
            imagefilledrectangle($image, $x, 502, $x + 34, 544, $white);
        }
        imagefilledrectangle($image, 0, 594, 1200, 630, $navy);
        imagefilledrectangle($image, 0, 594, 310, 630, $blue);
        imagefilledrectangle($image, 65, 92, 142, 98, $blue);
        $left = 75;
        if ($logo && ($source = @imagecreatefromstring($logo))) {
            imagefilledellipse($image, 210, 288, 280, 280, $white);
            $scale = min(220 / imagesx($source), 220 / imagesy($source));
            $w = (int) round(imagesx($source) * $scale);
            $h = (int) round(imagesy($source) * $scale);
            imagecopyresampled($image, $source, 210 - (int) ($w / 2), 288 - (int) ($h / 2), 0, 0, $w, $h, imagesx($source), imagesy($source));
            imagedestroy($source);
            $left = 395;
        }
        $font = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
        $width = 1120 - $left;
        for ($size = 42; $size >= 18; $size -= 2) {
            $lines = $this->wrap($name, $font, $size, $width);
            if (count($lines) <= 4) break;
        }
        $y = 245 - (int) ((count($lines) - 1) * ($size + 12) / 2);
        foreach ($lines as $line) {
            imagettftext($image, $size, 0, $left, $y, $navy, $font, $line);
            $y += $size + 12;
        }
        imagefilledrectangle($image, $left, $y + 7, $left + 70, $y + 11, $blue);
        imagettftext($image, 24, 0, $left, $y + 64, $navy, base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf'), 'Official Website');
        $temporary = tempnam($directory, 'og-');
        try {
            if (!imagepng($image, $temporary, 7)) throw new \RuntimeException('Cannot generate social preview');
            if (!@rename($temporary, $path) && !is_file($path)) throw new \RuntimeException('Cannot cache social preview');
        } finally {
            imagedestroy($image);
            if (is_file($temporary)) unlink($temporary);
        }
        $this->retainRecentPreviews($directory, $path);
        return $path;
    }

    /** Best-effort, nonrecursive retention of our own PNGs only. */
    private function retainRecentPreviews(string $directory, string $active): void
    {
        try {
            // Never follow a substituted directory or file symlink during cleanup.
            if (is_link($directory) || realpath(dirname($directory)) !== realpath(storage_path('app'))) return;
            $root = realpath($directory);
            if ($root === false) return;
            $activePath = realpath($active);
            $lock = @fopen($directory.'/.retention.lock', 'c');
            if ($lock === false) return;
            try {
                if (!flock($lock, LOCK_EX | LOCK_NB)) return;
                $files = [];
                foreach (new \DirectoryIterator($directory) as $file) {
                    if ($file->isLink() || !$file->isFile()
                        || !preg_match('/\Asocial-preview-[a-f0-9]{64}\.png\z/', $file->getFilename())
                        || $file->getRealPath() === $activePath) continue;
                    $files[] = ['path' => $file->getPathname(), 'time' => $file->getMTime()];
                }
                usort($files, fn ($a, $b) => ($b['time'] <=> $a['time']) ?: strcmp($a['path'], $b['path']));
                // Reserve one slot for the active image, even if it is older.
                foreach (array_slice($files, 19) as $file) {
                    $path = $file['path'];
                    if (!is_link($path) && is_file($path) && dirname((string) realpath($path)) === $root) {
                        @unlink($path);
                    }
                }
            } finally {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        } catch (\Throwable) {
            // Retention failure must never prevent a valid preview being served.
        }
    }

    private function wrap(string $text, string $font, int $size, int $width): array
    {
        $lines = [];
        $line = '';
        foreach (mb_str_split($text) as $character) {
            $candidate = $line.$character;
            $box = imagettfbbox($size, 0, $font, $candidate);
            if ($box[2] - $box[0] > $width && $line !== '') {
                $space = mb_strrpos($line, ' ');
                if ($space !== false && $space > mb_strlen($line) / 2) {
                    $lines[] = trim(mb_substr($line, 0, $space));
                    $line = ltrim(mb_substr($line, $space + 1).$character);
                } else {
                    $lines[] = trim($line);
                    $line = $character;
                }
            } else $line = $candidate;
        }
        if (trim($line) !== '') $lines[] = trim($line);
        return $lines;
    }
}
