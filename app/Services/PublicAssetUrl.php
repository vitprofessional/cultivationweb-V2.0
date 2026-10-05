<?php
namespace App\Services;
use Illuminate\Config\Repository;

final class PublicAssetUrl
{
    public function url(string $path): ?string
    {
        // Accept the legacy template spelling at a single boundary.
        $path = preg_replace('~\Apublic/~', '', $path);
        return (new PublicMediaUrl(new Repository(['media' => [
            'public_base_url' => config('public_assets.base_url') ?: config('app.url'),
            'public_path_prefix' => config('public_assets.path_prefix', ''),
        ]])))->url($path);
    }
}
