<?php

namespace Tests\Feature;

use App\Models\HomeSlider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SliderMediaRenderTest extends TestCase
{
    use DatabaseTransactions;

    public function createApplication()
    {
        $app = parent::createApplication();
        $db = $app['db']->connection();
        if ($app->environment() !== 'testing' || $db->getDriverName() !== 'mysql'
            || $db->getDatabaseName() !== 'cultivation_test'
            || ! in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)
            || $db->selectOne('SELECT DATABASE() AS db')->db !== 'cultivation_test') {
            throw new \RuntimeException('Media rendering tests require local cultivation_test.');
        }

        return $app;
    }

    private function exportFixture(string $name, string $html): void
    {
        $directory = getenv('MEDIA_QA_EXPORT_DIR');
        if ($directory && is_dir($directory)) {
            file_put_contents($directory.'/'.$name.'.html', $html);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'https://school-a.example.test',
            'media.public_base_url' => 'https://school-a.cultivationapp.test',
            'media.public_path_prefix' => 'public']);
        $this->app['url']->forceRootUrl('https://school-a.example.test');
    }

    public function test_homepage_reads_admin_hosted_slider_without_a_web_local_copy(): void
    {
        $filename = '11111111-1111-4111-8111-111111111111.jpg';
        $slider = HomeSlider::query()->forceCreate(['avatar' => $filename, 'headLine' => 'Canonical synthetic slide', 'detail' => 'Synthetic caption']);
        $original = $slider->fresh()->getAttributes();
        $expected = 'https://school-a.cultivationapp.test/public/upload/image/webHomepage/'.$filename;
        $this->assertFileDoesNotExist(public_path('upload/image/webHomepage/'.$filename));
        $response = $this->get('/')->assertOk()->assertSee("background-image:url('".$expected."')", false);
        $response->assertSee('Canonical synthetic slide')->assertSee('Synthetic caption')
            ->assertDontSee('https://school-a.example.test/public/upload/image/webHomepage/'.$filename, false)
            ->assertDontSee('Illustrative education imagery');
        $this->assertSame($original, $slider->fresh()->getAttributes());
        $this->exportFixture('web-slider', $response->getContent());
    }

    public function test_missing_media_base_uses_existing_demo_fallback_not_web_domain_media(): void
    {
        $filename = '22222222-2222-4222-8222-222222222222.jpg';
        HomeSlider::query()->forceCreate(['avatar' => $filename, 'headLine' => 'Must not appear']);
        config(['media.public_base_url' => null]);
        $response = $this->get('/')->assertOk()->assertSee('Illustrative education imagery');
        $response->assertDontSee($filename)->assertDontSee('Must not appear');
        $this->exportFixture('web-missing-base', $response->getContent());
    }

    public function test_unsafe_reference_uses_existing_fallback_without_mutating_data(): void
    {
        $slider = HomeSlider::query()->forceCreate(['avatar' => '../outside.jpg', 'headLine' => 'Unsafe reference']);
        $this->get('/')->assertOk()->assertSee('Illustrative education imagery')->assertDontSee('outside.jpg');
        $this->assertSame('../outside.jpg', $slider->fresh()->avatar);
    }

    public function test_latest_five_query_order_and_carousel_behavior_are_preserved(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            HomeSlider::query()->forceCreate(['avatar' => 'synthetic-'.$i.'.jpg', 'headLine' => 'Slide '.$i]);
        }
        $response = $this->get('/')->assertOk()->assertDontSee('synthetic-1.jpg');
        $response->assertSeeInOrder(['synthetic-6.jpg', 'synthetic-5.jpg', 'synthetic-4.jpg', 'synthetic-3.jpg', 'synthetic-2.jpg']);
        $response->assertSee('data-loop="true"', false)->assertSee('data-autoplay="true"', false);
    }

    public function test_empty_data_still_renders_existing_fallback(): void
    {
        $this->assertSame(0, HomeSlider::count());
        $this->get('/')->assertOk()->assertSee('Illustrative education imagery');
    }
}
