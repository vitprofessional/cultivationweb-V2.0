<?php

namespace App\Services;

/** The shared attachment column contains a local upload or an allowlisted YouTube URL. */
final class GalleryVideoSource
{
    public static function youtubeId(?string $value, bool $allowId = false): ?string
    {
        $value = trim($value ?? '');
        if ($allowId && preg_match('/\A[A-Za-z0-9_-]{11}\z/', $value)) return $value;
        if (strlen($value) > 2048 || preg_match('~[\x00-\x20\x7f\\\\<>"\']~', $value)) return null;
        $url = parse_url($value);
        if (!is_array($url) || !in_array(strtolower($url['scheme'] ?? ''), ['https', 'http'], true)
            || isset($url['user']) || isset($url['pass']) || isset($url['port'])) return null;
        $host = strtolower($url['host'] ?? ''); $path = $url['path'] ?? '';
        $id = null;
        if (in_array($host, ['youtu.be', 'www.youtu.be'], true) && preg_match('~\A/([A-Za-z0-9_-]{11})/?\z~', $path, $match)) $id = $match[1];
        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if ($path === '/watch') {
                // Reject duplicate/array identifiers; never embed a caller-provided URL.
                $values = [];
                foreach (explode('&', $url['query'] ?? '') as $part) {
                    $pair = explode('=', $part, 2);
                    if (urldecode($pair[0]) === 'v') $values[] = urldecode($pair[1] ?? '');
                }
                if (count($values) === 1) $id = $values[0];
            } elseif (preg_match('~\A/(?:embed|shorts|live)/([A-Za-z0-9_-]{11})/?\z~', $path, $match)) $id = $match[1];
        }
        if (in_array($host, ['youtube-nocookie.com', 'www.youtube-nocookie.com'], true)
            && preg_match('~\A/embed/([A-Za-z0-9_-]{11})/?\z~', $path, $match)) $id = $match[1];
        return is_string($id) && preg_match('/\A[A-Za-z0-9_-]{11}\z/', $id) ? $id : null;
    }

    public static function normalized(?string $input): ?string
    {
        $id = self::youtubeId($input, true);
        return $id ? 'https://www.youtube.com/watch?v='.$id : null;
    }

    public static function resolve(?string $attachment, PublicMediaUrl $media): array
    {
        $id = self::youtubeId($attachment);
        if ($id) return ['source' => 'youtube', 'url' => 'https://www.youtube.com/watch?v='.$id,
            'embed' => 'https://www.youtube-nocookie.com/embed/'.$id, 'id' => $id];
        return ['source' => 'upload', 'url' => $media->galleryVideo($attachment), 'embed' => null, 'id' => null];
    }
}
