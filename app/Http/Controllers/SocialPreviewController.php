<?php

namespace App\Http\Controllers;

use App\Models\ServerConfig;
use App\Services\SocialPreview;

final class SocialPreviewController extends Controller
{
    public function __invoke(SocialPreview $preview)
    {
        return response()->file($preview->image(ServerConfig::query()->latest('id')->first()), [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=300',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
