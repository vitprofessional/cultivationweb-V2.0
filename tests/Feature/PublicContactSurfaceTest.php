<?php

namespace Tests\Feature;

use App\Models\InstituteDetails;
use App\Models\ServerConfig;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PublicContactSurfaceTest extends TestCase
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
            throw new \RuntimeException('Public contact QA requires local cultivation_test.');
        }
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewSandbox = sys_get_temp_dir().'/cultivation-contact-views-'.bin2hex(random_bytes(8));
        File::ensureDirectoryExists($this->viewSandbox);
        config(['view.compiled' => $this->viewSandbox]);
        $this->app['url']->forceRootUrl('https://public.example.test');
        $this->app['url']->forceScheme('https');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (isset($this->viewSandbox)) File::deleteDirectory($this->viewSandbox);
    }

    public function test_contact_and_institution_pages_use_latest_saved_contact_authority_only(): void
    {
        ServerConfig::query()->forceCreate([
            'instituteName' => 'Older Contact Authority', 'address' => 'Old address',
            'officeMobile' => '01000000000', 'officeEmail' => 'old@school.example.test',
            'facebookPage' => 'https://facebook.com/legacy-contact',
        ]);
        ServerConfig::query()->forceCreate([
            'instituteName' => 'Current Contact Authority', 'address' => 'Current Institute Road',
            'officeMobile' => '+8801700000000', 'officeEmail' => 'office@current-school.edu',
            'facebookPage' => 'https://facebook.com/current-school',
            'twitterLink' => 'https://x.com/current-school',
            'youtubeChanel' => 'https://youtube.com/@current-school',
            'linkedIn' => 'https://linkedin.com/company/current-school',
            'mapEmbed' => 'https://maps.google.com/?q=current-school',
        ]);
        InstituteDetails::query()->forceCreate(['insHeadline' => 'Institution profile', 'insDetails' => 'Profile contact QA']);

        foreach (['/contact-us', '/about-us'] as $path) {
            $response = $this->get($path)->assertOk()
                ->assertSee('Current Institute Road')
                ->assertSee('+8801700000000')
                ->assertSee('office@current-school.edu')
                ->assertSee('https://facebook.com/current-school')
                ->assertSee('https://youtube.com/@current-school')
                ->assertSee('https://maps.google.com/?q=current-school')
                ->assertDontSee('Old address')
                ->assertDontSee('01000000000')
                ->assertDontSee('legacy-contact')
                ->assertDontSee('class="fa fa-globe"', false);
        }
        $this->get('/contact-us')->assertSee('https://x.com/current-school')->assertSee('https://linkedin.com/company/current-school');
        $this->get('/contact-us')->assertSee('Reach the support team')->assertSee('Submit an inquiry');
    }

    public function test_about_presentation_preserves_content_and_does_not_invent_core_values_or_statistics(): void
    {
        ServerConfig::query()->forceCreate(['instituteName' => 'About QA Institution']);
        InstituteDetails::query()->forceCreate([
            'insDetails' => 'AUTHORED_ABOUT_CONTENT',
            'mission' => 'AUTHORED_MISSION_CONTENT',
            'vision' => 'AUTHORED_VISION_CONTENT',
            'establishDate' => '1990', 'landSize' => '1.81 Acres',
        ]);
        $html = $this->get('/about-us')->assertOk()
            ->assertSee('About QA Institution')->assertSee('1990')->assertSee('1.81 Acres')
            ->assertDontSee('Our Core Values')
            ->assertSee('about-hero-visual')->assertSee('about-journey-visual')
            ->assertDontSee('class="about-contact"', false)
            ->assertSee(route('supportPage'), false)->assertSee(route('imagePage'), false)
            ->assertDontSee('Academic Excellence')->assertDontSee('Moral and Ethical Development')
            ->getContent();
        foreach (['AUTHORED_ABOUT_CONTENT', 'AUTHORED_MISSION_CONTENT', 'AUTHORED_VISION_CONTENT'] as $content) {
            $this->assertSame(1, substr_count($html, $content));
        }
    }

    public function test_missing_contact_and_placeholder_social_values_are_not_rendered_as_fake_details(): void
    {
        ServerConfig::query()->forceCreate([
            'officeMobile' => 'N/A', 'officeEmail' => 'info@cultivation.local',
            'address' => '-', 'facebookPage' => 'https://facebook.example/demo',
            'twitterLink' => 'not a URL', 'youtubeChanel' => 'javascript:alert(1)',
        ]);
        $response = $this->get('/contact-us')->assertOk()
            ->assertSee('Submit an inquiry')
            ->assertDontSee('N/A')->assertDontSee('cultivation.local')
            ->assertDontSee('facebook.example')->assertDontSee('javascript:alert');
    }

    public function test_homepage_and_about_page_share_the_canonical_institution_about_image(): void
    {
        config(['media.public_base_url' => 'https://admin.example.test/tenant/public', 'media.public_path_prefix' => 'public']);
        InstituteDetails::query()->forceCreate([
            'insHeadline' => 'Canonical profile',
            'insDetails' => 'Shared About content',
            'mission' => 'Mission remains unchanged',
            'vision' => 'Vision remains unchanged',
            'heroImg' => 'about-handoff.jpg',
        ]);

        $expected = 'https://admin.example.test/tenant/public/upload/image/cultivation/about-handoff.jpg';
        foreach (['/', '/about-us'] as $path) {
            $response = $this->get($path)->assertOk()->assertSee($expected, false);
            $this->assertStringNotContainsString('/public/public/', $response->getContent());
        }
    }
}
