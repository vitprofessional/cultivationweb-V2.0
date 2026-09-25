<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Services\PublicMediaUrl;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class NoticeMediaCompatibilityTest extends TestCase
{
    use DatabaseTransactions;

    private string $viewSandbox;

    public function createApplication()
    {
        $app = parent::createApplication(); $db = $app['db']->connection();
        if ($app->environment() !== 'testing' || $db->getDriverName() !== 'mysql'
            || ! in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)
            || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') {
            throw new \RuntimeException('Notice compatibility QA requires local cultivation_test.');
        }
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewSandbox = sys_get_temp_dir().'/cultivation-notice-web-'.bin2hex(random_bytes(12));
        \Illuminate\Support\Facades\File::ensureDirectoryExists($this->viewSandbox);
        config(['view.compiled' => $this->viewSandbox, 'logging.default' => 'stderr']);
        config(['app.url' => 'https://public.example.test', 'media.public_base_url' => 'https://admin.example.test/tenant/public', 'media.public_path_prefix' => 'public']);
        $this->app['url']->forceRootUrl('https://public.example.test');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (isset($this->viewSandbox)) (new \Illuminate\Filesystem\Filesystem())->deleteDirectory($this->viewSandbox);
    }

    public static function legacyPaths(): array
    {
        return [['upload/notice/meeting notes.pdf'], ['meeting notes.pdf'], ['public/upload/notice/meeting notes.pdf']];
    }

    #[DataProvider('legacyPaths')]
    public function test_existing_notice_uses_canonical_authority_on_all_active_consumers(string $path): void
    {
        $row = Notice::query()->forceCreate(['headline' => 'Meeting announcement', 'body' => 'Existing announcement', 'attachment' => $path]);
        $original = $row->fresh()->getAttributes();
        $expected = 'https://admin.example.test/tenant/public/upload/notice/meeting%20notes.pdf';
        $this->assertSame($expected, app(PublicMediaUrl::class)->notice($path));
        foreach (['/', '/notices', '/notices/'.$row->id] as $url) {
            $response = $this->get($url)->assertOk()->assertSee($expected, false)->assertDontSee('/public/public/', false)
                ->assertDontSee('https://public.example.test/public/upload/notice/', false);
            if ($url === '/notices') $response->assertSee(route('notice.show', $row), false);
        }
        $this->assertSame($original, $row->fresh()->getAttributes());
        $this->assertFileDoesNotExist(public_path('upload/notice/meeting notes.pdf'));
    }

    public function test_missing_media_config_never_falls_back_to_web_domain_or_changes_data(): void
    {
        config(['media.public_base_url' => null]);
        $row = Notice::query()->forceCreate(['headline' => 'Available text announcement', 'body' => 'Text remains available.', 'attachment' => 'upload/notice/synthetic.pdf']);
        $this->assertNull(app(PublicMediaUrl::class)->notice($row->attachment));
        foreach (['/', '/notices', '/notices/'.$row->id] as $url) {
            $this->get($url)->assertOk()->assertSee('Available text announcement')->assertDontSee('href="https://public.example.test/public/upload/notice/', false)
                ->assertDontSee('Open attachment')->assertDontSee('data-attachment-url=', false);
        }
        $this->assertSame('upload/notice/synthetic.pdf', $row->fresh()->attachment);
    }

    public static function unsafePaths(): array
    {
        return [[null], [''], ['../outside.pdf'], ['upload/notice/../outside.pdf'], ['https://other.test/x.pdf'], ['upload/notice/%2e%2e.pdf'], ['upload/notice/x.pdf?token=secret'], ['upload/notice/x.php'], ['other/path.pdf'], ['upload\\notice\\x.pdf']];
    }

    #[DataProvider('unsafePaths')]
    public function test_invalid_notice_media_fails_closed(?string $value): void
    {
        $this->assertNull(app(PublicMediaUrl::class)->notice($value));
    }

    public function test_notice_body_and_title_are_escaped_on_active_public_pages(): void
    {
        $attack = '<img src=x onerror=alert(1)>';
        $row = Notice::query()->forceCreate(['headline' => $attack, 'body' => $attack]);
        $this->get('/notices/'.$row->id)->assertOk()->assertSee($attack)->assertDontSee($attack, false);
        $this->get('/')->assertOk()->assertDontSee($attack, false);
    }

    public function test_legacy_notice_modal_consumes_only_server_resolved_attachment_links(): void
    {
        $source = file_get_contents(resource_path('views/frontend/include.blade.php'));
        $this->assertStringContainsString('const candidateUrls = typeof attachmentUrl', $source);
        $this->assertStringNotContainsString('APP_URL', $source);
        $this->assertStringNotContainsString('sanitizeRelativePath', $source);
        $alternate = file_get_contents(resource_path('views/frontend/index.blade.php'));
        $this->assertStringContainsString('PublicMediaUrl::class)->notice($ntc->attachment)', $alternate);
        $this->assertStringNotContainsString("url('/').'/public/'.\$ntc->attachment", $alternate);
    }
}
