<?php

namespace Tests\Feature;

use App\Models\ServerConfig;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\{DB, File, URL};
use Tests\TestCase;

class HomepageLayoutTest extends TestCase
{
    use DatabaseTransactions;

    public function test_modern_presentation_excludes_known_stale_copy_and_uses_real_excerpt(): void
    {
        $slider = (new \App\Models\HomeSlider())->forceFill(['avatar' => 'campus.jpg', 'headLine' => 'Welcome to sonar bangla college']);
        $institution = (new \App\Models\InstituteDetails())->forceFill(['insDetails' => str_repeat('Real institution content. ', 40)]);
        $data = app(\App\Services\ModernHomepagePresentation::class)->build(
            (new ServerConfig())->forceFill(['instituteName' => 'Our School']), $institution, collect([$slider]), collect(), collect(),
            collect([['label' => 'Classes & Programs', 'value' => 7, 'icon' => 'fa-book']]), collect(), ['person' => null, 'ambiguous' => false]
        );
        $this->assertCount(0, $data['slides']);
        $this->assertLessThanOrEqual(423, mb_strlen($data['aboutText']));
        $this->assertStringStartsWith('Real institution content.', $data['aboutText']);
        $this->assertSame('Classes', $data['metrics']->first()['label']);
        $this->assertSame('Welcome to sonar bangla college', $slider->headLine, 'Stored values stay untouched.');
    }

    public function createApplication()
    {
        $app = parent::createApplication();
        $db = $app['db']->connection();
        if (!$app->environment('testing') || $db->getDatabaseName() !== 'cultivation_test'
            || !in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)) {
            throw new \RuntimeException('Homepage verification requires local cultivation_test.');
        }
        return $app;
    }

    public function test_latest_configuration_selects_homepage_with_safe_fallback_and_no_view_queries(): void
    {
        URL::forceRootUrl('http://localhost');
        $config = ServerConfig::query()->forceCreate(['instituteName' => 'Homepage QA Institution', 'homepage_layout' => null]);
        foreach ([null, 'classic', 'invalid', 'modern', 'classic'] as $layout) {
            $config->homepage_layout = $layout; $config->save();
            $response = $this->get('/')->assertOk()->assertViewIs($layout === 'modern' ? 'frontend.cultivation-v2.homepage-modern' : 'frontend.cultivation-v2.homepage');
            if ($layout === 'modern') {
                $response->assertSee('modern-homepage')->assertSee('Institutional Key Statistics')->assertSee('Academic Information')
                    ->assertDontSee('Upcoming Events')->assertDontSee('Illustrative education imagery');
                if ($directory = getenv('HOMEPAGE_QA_DIR')) {
                    $this->assertStringStartsWith(realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR, realpath($directory));
                    File::put($directory.'/modern.html', $response->getContent());
                }
                DB::flushQueryLog(); DB::enableQueryLog();
                $response->original->render();
                $this->assertCount(0, DB::getQueryLog(), 'Modern Blade must not query the database.');
                DB::disableQueryLog();
            } elseif ($layout === 'classic' && ($directory = getenv('HOMEPAGE_QA_DIR'))) {
                File::put($directory.'/classic.html', $response->getContent());
            }
        }
        ServerConfig::query()->forceCreate(['instituteName' => 'Latest Classic', 'homepage_layout' => null]);
        $this->get('/')->assertOk()->assertViewIs('frontend.cultivation-v2.homepage');
        DB::table('server_configs')->delete();
        $this->get('/')->assertOk()->assertViewIs('frontend.cultivation-v2.homepage');
    }
}
