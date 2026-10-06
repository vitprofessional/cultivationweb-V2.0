<?php

namespace App\Services;

use Illuminate\Config\Repository;

final class PublicAssetUrl
{
    public function url(string $path): ?string
    {
        // Accept legacy public/... inputs at one boundary while preserving
        // exactly one configured browser-facing public prefix.
        $path = preg_replace('~\Apublic/~', '', trim($path));

        if ($path === '') {
            return null;
        }

        return (new PublicMediaUrl(new Repository([
            'media' => [
                'public_base_url' => config('public_assets.base_url')
                    ?: config('app.url'),

                'public_path_prefix' => config(
                    'public_assets.path_prefix',
                    'public'
                ),
            ],
        ])))->url($path);
    }
}