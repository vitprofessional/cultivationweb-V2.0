<?php

namespace App\Services;

use Illuminate\Contracts\Config\Repository;

final class PublicMediaUrl
{
    public function __construct(private readonly Repository $config) {}

    /** Resolve the existing filename-only home_sliders.avatar contract. */
    public function slider(?string $filename): ?string
    {
        $filename = trim($filename ?? '');
        if ($filename === '' || str_contains($filename, '/') || str_contains($filename, '\\')) {
            return null;
        }

        return $this->url('upload/image/webHomepage/'.$filename);
    }

    /** URL construction only: never reads local files or makes remote requests. */
    public function url(?string $relativePath): ?string
    {
        $path = $this->segments($relativePath ?? '');
        $prefix = $this->segments((string) $this->config->get('media.public_path_prefix', 'public'));
        $base = trim((string) $this->config->get('media.public_base_url', ''));
        if ($path === null || $path === [] || $prefix === null
            || ! filter_var($base, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($base);
        if (! is_array($parts) || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
            || empty($parts['host'])
            || isset($parts['user']) || isset($parts['pass'])
            || isset($parts['query']) || isset($parts['fragment'])) {
            return null;
        }
        $segments = $this->segments($parts['path'] ?? '');
        if ($segments === null) {
            return null;
        }

        // Only coalesce the known public-root boundary, never interior path segments.
        foreach ([$prefix, $path] as $next) {
            if ($segments !== [] && end($segments) === 'public' && ($next[0] ?? null) === 'public') {
                array_shift($next);
            }
            $segments = array_merge($segments, $next);
        }

        $origin = strtolower($parts['scheme']).'://'.$parts['host'];
        if (isset($parts['port'])) {
            $origin .= ':'.$parts['port'];
        }

        return $origin.'/'.implode('/', array_map('rawurlencode', $segments));
    }

    private function segments(string $path): ?array
    {
        // Reject traversal, pre-encoded paths, URL authority and filesystem syntax.
        if (preg_match('~[\\x00-\\x1f\\x7f\\\\\\\\:%?#]~', $path)) {
            return null;
        }
        $segments = preg_split('~/+~', trim($path, " /"));
        $segments = array_values(array_filter($segments, static fn ($part) => $part !== ''));
        foreach ($segments as $segment) {
            if ($segment === '.' || $segment === '..') {
                return null;
            }
        }

        return $segments;
    }
}
