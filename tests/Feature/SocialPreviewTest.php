<?php

namespace Tests\Feature;

use App\Services\SocialPreview;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SocialPreviewTest extends TestCase
{
    private string $previewSandbox;
    private bool $ownsConfigFixture = false;

    public function createApplication()
    {
        $app = parent::createApplication();
        $db = $app['db']->connection();
        if (!$app->environment('testing') || $db->getDriverName() !== 'mysql'
            || $db->getConfig('host') !== '127.0.0.1' || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') {
            throw new \RuntimeException('Social preview tests require isolated cultivation_test.');
        }
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->previewSandbox = sys_get_temp_dir().'/social-preview-test-'.bin2hex(random_bytes(8));
        $this->app->useStoragePath($this->previewSandbox);
    }

    protected function tearDown(): void
    {
        if ($this->ownsConfigFixture) \Illuminate\Support\Facades\Schema::dropIfExists('server_configs');
        \Illuminate\Support\Facades\File::deleteDirectory($this->previewSandbox);
        parent::tearDown();
    }

    public function test_retention_preserves_active_and_newest_previews_but_never_unrelated_files(): void
    {
        config(['media.public_base_url' => null]);
        $service = app(SocialPreview::class);
        $config = (object) ['instituteName' => 'Retention Academy', 'logo' => null];
        $active = $service->image($config);
        touch($active, 1000000000);
        $directory = dirname($active);
        $owned = [];
        for ($i = 1; $i <= 24; $i++) {
            $path = $directory.'/social-preview-'.hash('sha256', (string) $i).'.png';
            copy($active, $path);
            touch($path, 1000000000 + $i);
            $owned[$i] = $path;
        }
        $unrelated = [$directory.'/keep.png', $directory.'/'.str_repeat('a', 64).'.png', $directory.'/social-preview-not-a-hash.png'];
        foreach ($unrelated as $path) file_put_contents($path, 'untouched');
        mkdir($directory.'/nested');
        copy($active, $directory.'/nested/social-preview-'.str_repeat('b', 64).'.png');
        $this->assertSame($active, $service->image($config));
        $this->assertFileExists($active);
        for ($i = 1; $i <= 24; $i++) {
            if ($i <= 5) $this->assertFileDoesNotExist($owned[$i]);
            else $this->assertFileExists($owned[$i]);
        }
        foreach ($unrelated as $path) $this->assertSame('untouched', file_get_contents($path));
        $this->assertFileExists($directory.'/nested/social-preview-'.str_repeat('b', 64).'.png');
    }

    public function test_cleanup_failure_does_not_break_cached_or_new_preview_generation(): void
    {
        config(['media.public_base_url' => null]);
        $service = app(SocialPreview::class);
        $config = (object) ['instituteName' => 'Safe Cleanup Academy', 'logo' => null];
        $active = $service->image($config);
        unlink(dirname($active).'/.retention.lock');
        // Deterministic unavailable cleanup lock, independent of OS permissions.
        mkdir(dirname($active).'/.retention.lock');
        $this->assertSame($active, $service->image($config));
        $config->instituteName = 'New Safe Cleanup Academy';
        $this->assertSame([1200, 630], array_slice(getimagesize($service->image($config)), 0, 2));
    }

    public function test_branded_png_dimensions_cache_and_logo_refresh(): void
    {
        config(['cache.default' => 'array', 'media.public_base_url' => 'https://admin.example.test']);
        Http::preventStrayRequests();
        $logo = imagecreatetruecolor(120, 80);
        imagefill($logo, 0, 0, imagecolorallocate($logo, 240, 120, 20));
        ob_start(); imagepng($logo); $bytes = ob_get_clean(); imagedestroy($logo);
        Http::fake(['https://admin.example.test/upload/image/cultivation/logo.png' => Http::response($bytes)]);
        $config = (object) ['instituteName' => 'Example Academy', 'logo' => 'logo.png'];
        $service = app(SocialPreview::class);
        $path = $service->image($config);
        $this->assertSame([1200, 630], array_slice(getimagesize($path), 0, 2));
        $this->assertSame($path, $service->image($config));
        $image = imagecreatefrompng($path);
        $this->assertSame(0xf07814, imagecolorat($image, 210, 288) & 0xffffff);
        imagedestroy($image);
        $oldUrl = $service->url($config);
        $config->instituteName = 'Another Institution';
        $this->assertNotSame($oldUrl, $service->url($config));
        $this->assertNotSame($path, $service->image($config));
        Cache::forget('social-logo:'.hash('sha256', 'https://admin.example.test/upload/image/cultivation/logo.png'));
        Http::fake(['*' => Http::response('missing', 404)]);
        $this->assertNotSame($path, $service->image($config));
    }

    public function test_missing_and_broken_logo_generate_clean_text_only_images(): void
    {
        config(['cache.default' => 'array', 'media.public_base_url' => 'https://admin.example.test']);
        Http::fake(['*' => Http::response('not an image', 200)]);
        $service = app(SocialPreview::class);
        $name = 'A Different School';
        $missing = $service->image((object) ['instituteName' => $name, 'logo' => null]);
        $broken = $service->image((object) ['instituteName' => $name, 'logo' => 'broken.png']);
        $this->assertSame($missing, $broken);
        $this->assertSame([1200, 630], array_slice(getimagesize($missing), 0, 2));
    }

    public function test_metadata_uses_shared_png_not_favicon_and_current_page_url(): void
    {
        $config = (object) ['instituteName' => 'Sample & School', 'logo' => null];
        foreach (['/', '/about-us', '/login'] as $path) {
            $this->app->instance('request', \Illuminate\Http\Request::create('https://web.example.test'.$path));
            app('url')->setRequest($this->app['request']);
            $html = view('frontend.cultivation-v2.partials._social-meta', compact('config'))->render();
            $this->assertStringContainsString('content="Sample &amp; School"', $html);
            $this->assertStringContainsString('content="1200"', $html);
            $this->assertStringContainsString('content="630"', $html);
            $this->assertStringContainsString('summary_large_image', $html);
            $this->assertStringContainsString('https://web.example.test/social-preview.png?v=', $html);
            $this->assertStringContainsString('content="'.rtrim('https://web.example.test'.$path, '/').'"', $html);
            $this->assertStringNotContainsString('fav.png', $html);
            $this->assertStringNotContainsString('/public/public/', $html);
        }
    }

    public function test_public_image_endpoint_returns_png(): void
    {
        // Standalone OG verification must not require an uncommitted global schema helper.
        // Only the three authoritative columns used by this endpoint are needed.
        if (!\Illuminate\Support\Facades\Schema::hasTable('server_configs')) {
            \Illuminate\Support\Facades\Schema::create('server_configs', function ($table) {
                $table->id();
                $table->string('instituteName')->nullable();
                $table->string('logo')->nullable();
            });
            $this->ownsConfigFixture = true;
        }
        config(['cache.default' => 'array']);
        config(['app.url' => 'http://localhost']);
        $this->app['url']->forceRootUrl('http://localhost');
        Http::fake(['*' => Http::response('', 404)]);
        $this->withoutExceptionHandling();
        $this->get('/social-preview.png')->assertOk()->assertHeader('Content-Type', 'image/png');
    }

    public function test_long_institution_name_and_unconfigured_media_are_supported(): void
    {
        config(['media.public_base_url' => null]);
        Http::preventStrayRequests();
        $path = app(SocialPreview::class)->image((object) ['instituteName' => str_repeat('International Education ', 8), 'logo' => 'logo.png']);
        $this->assertSame([1200, 630], array_slice(getimagesize($path), 0, 2));
        Http::assertNothingSent();
    }
}
