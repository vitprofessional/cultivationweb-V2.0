<?php
namespace Tests\Unit;
use App\Services\PublicAssetUrl;
use Tests\TestCase;

class PublicAssetUrlTest extends TestCase
{
    public function test_cpanel_assets_and_vite_use_document_root_without_public_prefix(): void
    {
        config(['public_assets.base_url'=>'https://web.example.test','public_assets.path_prefix'=>'']);
        $assets=app(PublicAssetUrl::class);
        $this->assertSame('https://web.example.test/cultivation/style.css',$assets->url('public/cultivation/style.css'));
        $this->assertSame('https://web.example.test/build/assets/app.js',$assets->url('build/assets/app.js'));
        $html=(string)app(\Illuminate\Foundation\Vite::class)(['resources/js/app.js']);
        $this->assertStringContainsString('https://web.example.test/build/assets/',$html);
        $this->assertStringNotContainsString('/public/',$html);
    }
    public function test_local_required_public_prefix_is_coalesced(): void
    {
        config(['public_assets.base_url'=>'http://localhost/school/public','public_assets.path_prefix'=>'public']);
        $this->assertSame('http://localhost/school/public/cultivation/style.css',app(PublicAssetUrl::class)->url('public/cultivation/style.css'));
        $this->assertNull(app(PublicAssetUrl::class)->url('../.env'));
    }
    public function test_production_media_remains_separate_from_static_assets(): void
    {
        config(['public_assets.base_url'=>'https://web.example.test','public_assets.path_prefix'=>'','media.public_base_url'=>'https://admin.example.test','media.public_path_prefix'=>'']);
        $media=app(\App\Services\PublicMediaUrl::class);
        $this->assertSame('https://admin.example.test/upload/image/cultivation/about.jpg',$media->institutionAboutImage('about.jpg'));
        $this->assertSame('https://admin.example.test/upload/image/webHomepage/slide.jpg',$media->slider('slide.jpg'));
        $this->assertSame('https://admin.example.test/upload/image/PhotoGallery/photo.jpg',$media->galleryPhoto('photo.jpg'));
    }
}
