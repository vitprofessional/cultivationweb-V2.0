<?php

namespace Tests\Unit;

use App\Services\PublicAssetUrl;
use Illuminate\Foundation\Vite;
use Tests\TestCase;

class PublicAssetUrlTest extends TestCase
{
    public function test_static_assets_use_public_directory_boundary(): void
    {
        config([
            'public_assets.base_url' => 'https://web.example.test',
            'public_assets.path_prefix' => 'public',
        ]);

        $assets = app(PublicAssetUrl::class);

        $this->assertSame(
            'https://web.example.test/public/cultivation/style.css',
            $assets->url('cultivation/style.css')
        );

        $this->assertSame(
            'https://web.example.test/public/cultivation/style.css',
            $assets->url('public/cultivation/style.css')
        );

        $this->assertSame(
            'https://web.example.test/public/img/forms.jpg',
            $assets->url('img/forms.jpg')
        );

        $this->assertSame(
            'https://web.example.test/public/build/assets/app.js',
            $assets->url('build/assets/app.js')
        );
    }

    public function test_public_prefix_is_never_duplicated(): void
    {
        config([
            'public_assets.base_url' => 'http://localhost/school',
            'public_assets.path_prefix' => 'public',
        ]);

        $assets = app(PublicAssetUrl::class);

        $this->assertSame(
            'http://localhost/school/public/cultivation/style.css',
            $assets->url('public/cultivation/style.css')
        );

        $this->assertNull(
            $assets->url('../.env')
        );
    }

    public function test_vite_assets_use_public_build_boundary(): void
    {
        config([
            'app.url' => 'https://web.example.test',
            'app.asset_url' => null,
            'public_assets.base_url' => 'https://web.example.test',
            'public_assets.path_prefix' => 'public',
        ]);

        $vite = app(\Illuminate\Foundation\Vite::class);

        $vite->useBuildDirectory('build');

        $vite->createAssetPathsUsing(
            fn (string $path) => app(PublicAssetUrl::class)->url($path)
        );

        $html = (string) $vite([
            'resources/js/app.js',
        ]);

        $this->assertStringContainsString(
            'https://web.example.test/public/build/assets/',
            $html
        );

        $this->assertStringNotContainsString(
            'https://web.example.test/build/assets/',
            $html
        );
    }

    public function test_production_media_remains_separate_from_static_assets(): void
    {
        config([
            'public_assets.base_url' => 'https://web.example.test',
            'public_assets.path_prefix' => 'public',

            'media.public_base_url' => 'https://admin.example.test',
            'media.public_path_prefix' => 'public',
        ]);

        $media = app(\App\Services\PublicMediaUrl::class);

        $this->assertSame(
            'https://admin.example.test/public/upload/image/cultivation/about.jpg',
            $media->institutionAboutImage('about.jpg')
        );

        $this->assertSame(
            'https://admin.example.test/public/upload/image/webHomepage/slide.jpg',
            $media->slider('slide.jpg')
        );

        $this->assertSame(
            'https://admin.example.test/public/upload/image/PhotoGallery/photo.jpg',
            $media->galleryPhoto('photo.jpg')
        );
    }
}