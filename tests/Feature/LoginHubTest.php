<?php
namespace Tests\Feature;
use App\Http\Controllers\LoginHubController;
use Tests\TestCase;

class LoginHubTest extends TestCase
{
    public function test_production_requires_https_without_rewriting_configured_urls(): void
    {
        app()->detectEnvironment(fn () => 'production');
        config(['portals'=>['base_url'=>'http://admin.example.test']]);
        $this->assertSame([null,null,null,null],array_column(app(LoginHubController::class)()->getData()['portals'],'url'));
        config(['portals.base_url'=>'https://admin.example.test']);
        $this->assertCount(4,array_filter(array_column(app(LoginHubController::class)()->getData()['portals'],'url')));
    }

    public function test_cards_render_real_links_and_disabled_missing_configuration(): void
    {
        $directory=getenv('LOGIN_HUB_QA_DIR') ?: sys_get_temp_dir().'/login-hub-'.bin2hex(random_bytes(8));
        \Illuminate\Support\Facades\File::ensureDirectoryExists($directory.'/compiled');
        config(['view.compiled'=>$directory.'/compiled']);
        app('blade.compiler')->setPath($directory.'/compiled');
        $source=file_get_contents(resource_path('views/frontend/cultivation-v2/login.blade.php'));
        $source=preg_replace('/@extends\([^\n]+\)|@section\([^\n]+\)|@endsection/', '', $source);
        foreach (['configured','missing'] as $state) {
            config(['portals'=>['base_url'=>$state==='configured'?'https://admin.example.test':null]]);
            $data=app(LoginHubController::class)()->getData()+['config'=>(object)['logo'=>null,'instituteName'=>'QA Institution']];
            $html=\Illuminate\Support\Facades\Blade::render($source,$data);
            $this->assertSame(4,substr_count($html,'data-portal='));
            $this->assertSame($state==='configured'?4:0,substr_count($html,'aria-label="'));
            $this->assertSame($state==='missing'?4:0,substr_count($html,'disabled>Not configured'));
            $this->assertStringNotContainsString('localhost',$html);
            if(getenv('LOGIN_HUB_QA_DIR')) file_put_contents($directory.'/'.$state.'.html','<!doctype html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"></head><body>'.$html.'</body></html>');
        }
        if(!getenv('LOGIN_HUB_QA_DIR')) \Illuminate\Support\Facades\File::deleteDirectory($directory);
    }
    public function test_real_portals_use_explicit_admin_origin(): void
    {
        config(['portals.base_url'=>'https://admin.example.test/school','app.url'=>'https://web.example.test']);
        $portals=app(LoginHubController::class)()->getData()['portals'];
        $this->assertSame(['admin','teacher','student','guardian'],array_column($portals,'key'));
        $this->assertSame(['https://admin.example.test/school/login','https://admin.example.test/school/teacher/login','https://admin.example.test/school/portal/login','https://admin.example.test/school/portal/login'],array_column($portals,'url'));
        $this->assertSame('loginHub',app('router')->getRoutes()->match(\Illuminate\Http\Request::create('/login'))->getName());
    }
    public function test_missing_or_unsafe_urls_never_invent_portals(): void
    {
        config(['portals'=>['base_url'=>null,'admin'=>'javascript:alert(1)','teacher'=>'https://user:secret@example.test/login']]);
        $this->assertSame([null,null,null,null],array_column(app(LoginHubController::class)()->getData()['portals'],'url'));
    }
    public function test_individual_override_preserves_shared_portal_compatibility(): void
    {
        config(['portals.base_url'=>'https://admin.example.test','portals.teacher'=>'https://teachers.example.test/login']);
        $portals=app(LoginHubController::class)()->getData()['portals'];
        $this->assertSame('https://teachers.example.test/login',$portals[1]['url']);
        $this->assertSame($portals[2]['url'],$portals[3]['url']);
    }
}
