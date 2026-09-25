<?php

namespace Tests\Feature;

use App\Models\Notice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicNoticeViewerTest extends TestCase
{
    use DatabaseTransactions;

    private string $viewSandbox;

    public function createApplication()
    {
        $app = parent::createApplication();
        $db = $app['db']->connection();
        if ($app->environment() !== 'testing' || $db->getDriverName() !== 'mysql'
            || ! in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)
            || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') {
            throw new \RuntimeException('Public Notice QA requires local cultivation_test.');
        }
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewSandbox = sys_get_temp_dir().'/cultivation-notice-viewer-'.bin2hex(random_bytes(12));
        File::ensureDirectoryExists($this->viewSandbox);
        config(['view.compiled' => $this->viewSandbox, 'logging.default' => 'stderr',
            'app.url' => 'https://public.example.test',
            'media.public_base_url' => 'https://admin.example.test/tenant/public',
            'media.public_path_prefix' => 'public']);
        $this->app['url']->forceRootUrl('https://public.example.test');
        $this->app['url']->forceScheme('https');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (isset($this->viewSandbox)) (new \Illuminate\Filesystem\Filesystem())->deleteDirectory($this->viewSandbox);
    }

    private function notice(array $values = []): Notice
    {
        return Notice::query()->forceCreate(array_merge([
            'headline' => 'School reopening announcement',
            'body' => "Dear students and guardians,\n\nClasses resume on Sunday at 9:00 AM.\nPlease bring your books and student ID.\n\nবিদ্যালয়ে সবাইকে স্বাগতম।\n\nSchool Administration",
            'created_at' => '2026-09-20 09:00:00', 'updated_at' => '2026-09-20 09:00:00',
        ], $values));
    }

    private function export(string $name, string $html): void
    {
        $directory = getenv('NOTICE_VIEWER_QA_DIR');
        if (! $directory) return;
        $target = realpath($directory);
        $root = realpath(base_path());
        if (! $target || ! is_dir($target) || str_starts_with(strtolower($target), strtolower($root))) {
            throw new \RuntimeException('Notice QA exports must use an existing external directory.');
        }
        file_put_contents($target.'/'.$name.'.html', $html);
    }

    public function test_list_and_homepage_offer_one_shared_viewer_with_direct_route_fallback(): void
    {
        $notice = $this->notice();
        foreach (['/notices', '/'] as $url) {
            $response = $this->get($url)->assertOk()->assertSee('data-public-notice-open="'.$notice->id.'"', false)
                ->assertSee('href="'.route('notice.show', $notice).'"', false)
                ->assertSee('aria-haspopup="dialog"', false)->assertSee('Official announcement')
                ->assertSee('Text notice')->assertSee('datetime="2026-09-20"', false);
            $this->assertSame(1, substr_count($response->getContent(), '<dialog '));
            $this->assertSame(1, substr_count($response->getContent(), '<script data-public-notice-script>'));
        }
        $this->get('/notices/'.$notice->id)->assertOk()->assertSee($notice->headline)->assertDontSee('data-public-notice-open=', false);
    }

    public function test_plain_text_is_escaped_once_and_preserves_line_breaks(): void
    {
        $title = 'Class & community <script>window.noticeXSS=1</script>';
        $body = "First line\n\n<img src=x onerror=window.noticeXSS=1>\nবাংলা পাঠ্য";
        $this->notice(['headline' => $title, 'body' => $body]);
        foreach (['/notices', '/'] as $url) {
            $response = $this->get($url)->assertOk()->assertSee(e($title), false)->assertSee(e($body), false)
                ->assertDontSee($title, false)->assertDontSee('<img src=x onerror=', false)
                ->assertDontSee('Class &amp;amp;', false)->assertSee('white-space: pre-wrap', false);
        }
    }

    public static function attachments(): array
    {
        return [
            ['upload/notice/school image.png', 'Image notice', 'Open image', true],
            ['public/upload/notice/agenda.pdf', 'PDF document', 'Open PDF', false],
            ['agenda.pdf', 'PDF document', 'Open PDF', false],
        ];
    }

    #[DataProvider('attachments')]
    public function test_attachments_use_existing_canonical_resolver_without_local_copies(string $path, string $type, string $action, bool $image): void
    {
        $notice = $this->notice(['attachment' => $path]);
        $url = 'https://admin.example.test/tenant/public/upload/notice/'.rawurlencode(basename($path));
        foreach (['/notices', '/'] as $page) {
            $response = $this->get($page)->assertOk()->assertSee($type)->assertSee($action)
                ->assertSee('href="'.$url.'"', false)->assertSee('rel="noopener noreferrer"', false)
                ->assertDontSee('/public/public/', false)->assertDontSee('https://public.example.test/public/upload/notice/', false);
            if ($image) $response->assertSee('src="'.$url.'"', false);
            else $response->assertDontSee('<iframe', false);
        }
        $this->assertSame($path, $notice->fresh()->attachment);
        $this->assertFileDoesNotExist(public_path('upload/notice/'.basename($path)));
    }

    public function test_missing_configuration_and_unsafe_path_fail_closed_but_keep_text(): void
    {
        $notice = $this->notice(['attachment' => 'upload/notice/agenda.pdf']);
        config(['media.public_base_url' => null]);
        $response = $this->get('/notices')->assertOk()->assertSee('The attachment is currently unavailable.')
            ->assertSee($notice->headline)->assertDontSee('data-attachment-url=', false);
        $this->export('missing-media', $response->getContent());
        config(['media.public_base_url' => 'https://admin.example.test/tenant/public']);
        $notice->forceFill(['attachment' => 'https://attacker.invalid/private.pdf'])->save();
        $this->get('/notices')->assertOk()->assertSee('The attachment is currently unavailable.')
            ->assertDontSee('href="https://attacker.invalid/', false)->assertDontSee('src="https://attacker.invalid/', false);
    }

    public function test_empty_board_does_not_render_nonfunctional_modal(): void
    {
        $this->assertSame(0, Notice::count());
        foreach (['/notices', '/'] as $url) $this->get($url)->assertOk()->assertDontSee('<dialog ', false);
    }

    public function test_existing_pagination_and_latest_five_are_unchanged(): void
    {
        $rows = [];
        for ($i = 1; $i <= 17; $i++) $rows[] = $this->notice(['headline' => 'Announcement '.$i]);
        $homepage = $this->get('/')->assertOk();
        $page1 = $this->get('/notices')->assertOk();
        $page2 = $this->get('/notices?page=2')->assertOk();
        foreach ([[$homepage, 5], [$page1, 15], [$page2, 2]] as [$response, $expected]) {
            $this->assertSame($expected, substr_count($response->getContent(), '<template data-public-notice-template='));
        }
        $homepage->assertSeeInOrder(['Announcement 17', 'Announcement 16', 'Announcement 15', 'Announcement 14', 'Announcement 13'])
            ->assertDontSee('Announcement 12');
        $page1->assertSee('Announcement 3')->assertDontSee('>Announcement 2<', false);
        $page2->assertSee('Announcement 2')->assertSee('Announcement 1')->assertDontSee('Announcement 3');
        $this->assertSame(17, Notice::count());
    }

    public function test_read_only_viewer_preserves_rows_and_exports_synthetic_browser_fixtures(): void
    {
        $this->notice();
        $this->notice(['headline' => 'Term calendar · notice image', 'body' => 'Please refer to the academic calendar below.', 'attachment' => 'upload/notice/synthetic-notice.png']);
        $this->notice(['headline' => 'Examination schedule', 'body' => 'The approved examination timetable is attached for students and guardians.', 'attachment' => 'public/upload/notice/examination schedule.pdf']);
        $this->notice(['headline' => 'Detailed school guidelines', 'body' => implode("\n\n", array_fill(0, 55, 'Please read these school guidelines carefully. শিক্ষার্থী ও অভিভাবকদের জন্য নির্দেশনা।'))."\n\n".str_repeat('LongReference', 45)]);
        $this->notice(['headline' => 'Safety & <script>window.noticeXSS=1</script>', 'body' => '<img src=x onerror=window.noticeXSS=1>'."\n".'<script>window.noticeXSS=1</script>']);
        $before = Notice::orderBy('id')->get()->toArray();
        foreach (['list' => '/notices', 'home' => '/'] as $name => $url) {
            $response = $this->get($url)->assertOk();
            $this->export($name, $response->getContent());
        }
        $this->assertSame($before, Notice::orderBy('id')->get()->toArray());
        $this->assertFileDoesNotExist(public_path('upload/notice/synthetic-notice.png'));
    }

    public function test_viewer_has_no_fetch_or_unescaped_html_construction(): void
    {
        $source = file_get_contents(resource_path('views/frontend/notice/_viewer.blade.php'));
        foreach (['innerHTML', 'insertAdjacentHTML', 'fetch(', '{!!', 'APP_URL', 'asset('] as $unsafe) $this->assertStringNotContainsString($unsafe, $source);
        $this->assertStringContainsString('template.content.cloneNode(true)', $source);
        $this->assertStringContainsString('PublicMediaUrl::class)->notice(', $source);
    }
}
