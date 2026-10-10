<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

final class SocialPreview
{
    private const DESIGN_VERSION = 'v2';

    public function url($institution): string
    {
        $logo = $this->logoBytes($institution);
        $version = substr(hash('sha256', self::DESIGN_VERSION.'|'.($institution?->instituteName ?? '').'|'.($logo !== '' ? $institution?->logo : '').'|'.hash('sha256', $logo).'|'.config('media.public_base_url').'|'.config('media.public_path_prefix').'|'.config('app.url')), 0, 16);
        return route('socialPreview', ['v' => $version]);
    }

    /** Only fetch the server-configured media authority, never request-supplied URLs. */
    public function image($institution): string
    {
        $name = trim((string) ($institution?->instituteName ?? '')) ?: 'Official Website';
        $logo = $this->logoBytes($institution);
        $directory = storage_path('app/social-previews');
        File::ensureDirectoryExists($directory);
        $domain = (string) parse_url(config('app.url'), PHP_URL_HOST);
        $path = $directory.'/social-preview-'.hash('sha256', self::DESIGN_VERSION.'|'.$name.'|'.$logo.'|'.$domain).'.png';
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
        imagefilledrectangle($image, 0, 594, 400, 630, $blue);
        imagefilledrectangle($image, 65, 92, 170, 100, $blue);
        $left = 75;
        if ($logo && ($source = @imagecreatefromstring($logo))) {
            imagefilledellipse($image, 235, 285, 350, 350, $white);
            // Limit low-resolution enlargement while allowing high-quality originals to shine.
            $scale = min(300 / imagesx($source), 300 / imagesy($source), 1.5);
            $w = (int) round(imagesx($source) * $scale);
            $h = (int) round(imagesy($source) * $scale);
            imagecopyresampled($image, $source, 235 - (int) ($w / 2), 285 - (int) ($h / 2), 0, 0, $w, $h, imagesx($source), imagesy($source));
            imagedestroy($source);
            $left = 445;
        }
        $font = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
        $width = 1120 - $left;
        for ($size = 48; $size >= 18; $size -= 2) {
            $lines = $this->wrap($name, $font, $size, $width);
            if (count($lines) <= 4) break;
        }
        $y = 265 - (int) ((count($lines) - 1) * ($size + 12) / 2);
        foreach ($lines as $line) {
            imagettftext($image, $size, 0, $left, $y, $navy, $font, $line);
            $y += $size + 12;
        }
        imagefilledrectangle($image, $left, $y + 7, $left + 70, $y + 11, $blue);
        imagettftext($image, 24, 0, $left, $y + 64, $navy, base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf'), 'Official Website');
        if ($domain !== '') {
            $regular = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf');
            $footer = $this->wrap($domain, $regular, 18, 1050);
            foreach (array_slice($footer, 0, 2) as $index => $line) {
                imagettftext($image, 18, 0, 75, 540 + $index * 26, $navy, $regular, $line);
            }
        }
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

    /** Revalidate at most once per minute; share bytes between URL and PNG requests. */
    private function logoBytes($institution): string
    {
        $url = app(PublicMediaUrl::class)->institutionLogo($institution?->logo);
        if (!$url) return '';
        $key = 'social-logo-v2:'.hash('sha256', $url);
        $state = Cache::get($key);
        if (is_array($state) && $state['checked_at'] > now()->timestamp - 60) return $state['bytes'];
        $headers = ['Cache-Control' => 'no-cache'];
        if (!empty($state['etag'])) $headers['If-None-Match'] = $state['etag'];
        if (!empty($state['modified'])) $headers['If-Modified-Since'] = $state['modified'];
        $next = ['bytes' => '', 'etag' => null, 'modified' => null, 'checked_at' => now()->timestamp];
        try {
            $response = Http::timeout(3)->connectTimeout(2)->withoutRedirecting()->withHeaders($headers)->get($url);
            if ($response->status() === 304 && is_array($state)) {
                $next = array_replace($state, ['checked_at' => now()->timestamp]);
            } elseif ($response->status() === 200) {
                $bytes = $response->body();
                $size = strlen($bytes) <= 5 * 1024 * 1024 ? @getimagesizefromstring($bytes) : false;
                if ($size && $size[0] * $size[1] <= 16000000 && ($decoded = @imagecreatefromstring($bytes))) {
                    imagedestroy($decoded);
                    $next['bytes'] = $bytes;
                    $next['etag'] = $response->header('ETag');
                    $next['modified'] = $response->header('Last-Modified');
                }
            }
        } catch (\Throwable) {
            // Failed revalidation produces deterministic text-only branding, not stale logo bytes.
        }
        Cache::put($key, $next, 3600);
        return $next['bytes'];
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
