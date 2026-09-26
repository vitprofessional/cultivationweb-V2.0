<?php

namespace Tests\Feature;

use App\Models\HomeInfo;
use App\Models\PrincipalSpeech;
use App\Models\ServerConfig;
use App\Services\PrincipalProfile;
use App\Services\PublicMediaUrl;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PrincipalAuthorityCompatibilityTest extends TestCase
{
    use DatabaseTransactions;
    private string $sandbox;
    public function createApplication()
    {
        $app=parent::createApplication(); $db=$app['db']->connection();
        if ($app->environment() !== 'testing' || $db->getDriverName() !== 'mysql'
            || !in_array($db->getConfig('host'),['127.0.0.1','localhost'],true)
            || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') throw new \RuntimeException('Principal QA requires isolated cultivation_test.');
        return $app;
    }
    protected function setUp(): void
    {
        parent::setUp(); $this->sandbox=sys_get_temp_dir().'/principal-web-'.bin2hex(random_bytes(12)); File::ensureDirectoryExists($this->sandbox);
        config(['view.compiled'=>$this->sandbox,'logging.default'=>'stderr','app.url'=>'https://web.example.test','media.public_base_url'=>'https://admin.example.test/tenant/public','media.public_path_prefix'=>'public']);
        $this->app['url']->forceRootUrl('https://web.example.test'); $this->app['url']->forceScheme('https');
    }
    protected function tearDown(): void
    {
        parent::tearDown(); if (isset($this->sandbox)) File::deleteDirectory($this->sandbox);
    }
    private function identity(): ServerConfig
    {
        return ServerConfig::query()->forceCreate(['principalName'=>'Canonical Principal','avatar'=>'canonical portrait.jpg','principalDesignation'=>'Head Teacher','instituteName'=>'Synthetic QA Academy']);
    }
    private function surfaces(): array
    {
        $this->get(route('principalSpeechPage'))->assertRedirect(route('headOfInstituteMessagePage'));
        return [
            $this->get('/')->assertOk()->getContent(),
            $this->get(route('headOfInstituteMessagePage'))->assertOk()->getContent(),
            view('frontend.sideInfo')->render(), view('frontend.mobileSidebox')->render(),
        ];
    }
    public function test_all_public_surfaces_share_the_canonical_name_photo_and_live_updates(): void
    {
        $identity=$this->identity();
        $legacy=HomeInfo::query()->forceCreate(['principalName'=>'Obsolete Principal','principalImg'=>'obsolete.jpg','principalDetail'=>'Legacy detail']);
        PrincipalSpeech::query()->forceCreate(['importantSpeech'=>'A welcome from our Principal','generalSpeech'=>"First paragraph.\nSecond paragraph."]);
        $before=$legacy->fresh()->getAttributes();
        foreach (['Canonical Principal','Updated Principal'] as $name) {
            if ($name==='Updated Principal') { $identity->principalName=$name; $identity->avatar='updated.jpg'; $identity->save(); }
            $url='https://admin.example.test/tenant/public/upload/image/cultivation/'.rawurlencode($identity->avatar);
            foreach ($this->surfaces() as $html) {
                $this->assertStringContainsString($name,$html); $this->assertStringContainsString($url,$html);
                $this->assertStringNotContainsString('Obsolete Principal',$html); $this->assertStringNotContainsString('obsolete.jpg',$html);
                $this->assertStringNotContainsString('/public/public/',$html); $this->assertStringNotContainsString('https://web.example.test/public/upload/image/cultivation/'.$identity->avatar,$html);
            }
        }
        $this->assertSame($before,$legacy->fresh()->getAttributes());
        $this->assertDatabaseCount('server_configs',1); $this->assertDatabaseCount('home_infos',1); $this->assertDatabaseCount('principal_speeches',1);
    }
    public function test_legacy_homepage_uses_same_profile_without_legacy_identity_fallback(): void
    {
        $identity=$this->identity();
        $html=view('frontend.index',['config'=>$identity,'insData'=>(object)['insHeadline'=>'QA','insDetails'=>'QA','mission'=>'QA','vision'=>'QA'],'noticeBoard'=>collect(),'gallery'=>collect(),'sliderData'=>collect()])->render();
        $this->assertStringContainsString('Canonical Principal',$html);
        $this->assertStringContainsString('https://admin.example.test/tenant/public/upload/image/cultivation/canonical%20portrait.jpg',$html);
    }
    public function test_messages_use_existing_speech_table_and_plain_text_line_breaks(): void
    {
        $this->identity(); $attack='<script>alert("unsafe")</script>';
        PrincipalSpeech::query()->forceCreate(['importantSpeech'=>$attack,'generalSpeech'=>"Line one\n".$attack]);
        $this->get(route('headOfInstituteMessagePage'))->assertOk()->assertSee($attack)->assertDontSee($attack,false)->assertSee('Line one<br />',false);
        $this->get('/')->assertOk()->assertSee($attack)->assertDontSee($attack,false);
    }
    public function test_absent_or_ambiguous_identity_never_invents_a_principal(): void
    {
        HomeInfo::query()->forceCreate(['principalName'=>'Unconfirmed legacy identity','principalImg'=>'old.jpg']);
        foreach ([0,2] as $count) {
            if ($count===2) { $this->identity(); $this->identity(); }
            $profile=app(PrincipalProfile::class)->read(); $this->assertNull($profile['name']); $this->assertNull($profile['photoUrl']);
            foreach ($this->surfaces() as $html) { $this->assertStringContainsString('Principal profile not added',$html); $this->assertStringNotContainsString('Unconfirmed legacy identity',$html); $this->assertStringNotContainsString('Engr. Abu Yousuf',$html); }
        }
    }
    public function test_duplicate_speeches_do_not_select_an_arbitrary_message(): void
    {
        $this->identity(); foreach (['First secret message','Second secret message'] as $message) PrincipalSpeech::query()->forceCreate(['generalSpeech'=>$message]);
        $this->assertNull(app(PrincipalProfile::class)->read()['message']);
        $this->get(route('headOfInstituteMessagePage'))->assertOk()->assertDontSee('First secret message')->assertDontSee('Second secret message');
    }
    public static function unsafePortraits(): array { return [['../private.jpg'],['https://evil.test/x.jpg'],['C:\\secret.jpg'],['x.svg'],['//evil.test/x.jpg'],['x.jpg?query=1']]; }
    #[DataProvider('unsafePortraits')]
    public function test_unsafe_portrait_reference_never_becomes_public_url(string $value): void
    {
        $this->assertNull(app(PublicMediaUrl::class)->principal($value));
    }
    public function test_absent_media_configuration_does_not_use_web_app_url(): void
    {
        $this->identity(); config(['media.public_base_url'=>'']);
        $this->assertNull(app(PrincipalProfile::class)->read()['photoUrl']);
        foreach ($this->surfaces() as $html) $this->assertStringNotContainsString('upload/image/cultivation/canonical',$html);
    }
}
