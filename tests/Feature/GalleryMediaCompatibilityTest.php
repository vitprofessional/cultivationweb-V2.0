<?php

namespace Tests\Feature;

use App\Models\PhotoGallery;
use App\Models\VideoGallery;
use App\Services\PublicMediaUrl;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GalleryMediaCompatibilityTest extends TestCase
{
    use DatabaseTransactions;
    private string $viewSandbox;

    public function createApplication()
    {
        $app=parent::createApplication(); $db=$app['db']->connection();
        if ($app->environment() !== 'testing' || $db->getDriverName() !== 'mysql'
            || !in_array($db->getConfig('host'),['127.0.0.1','localhost'],true)
            || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') throw new \RuntimeException('Gallery compatibility QA requires local cultivation_test.');
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewSandbox=sys_get_temp_dir().'/cultivation-gallery-web-'.bin2hex(random_bytes(12));
        File::ensureDirectoryExists($this->viewSandbox);
        config(['view.compiled'=>$this->viewSandbox,'logging.default'=>'stderr','app.url'=>'https://web.example.test','media.public_base_url'=>'https://admin.example.test/tenant/public','media.public_path_prefix'=>'public']);
        $this->app['url']->forceRootUrl('https://web.example.test'); $this->app['url']->forceScheme('https');
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        if (isset($this->viewSandbox)) (new \Illuminate\Filesystem\Filesystem())->deleteDirectory($this->viewSandbox);
    }

    public function test_admin_photo_filename_renders_on_home_and_gallery_without_web_copy(): void
    {
        $row=PhotoGallery::query()->forceCreate(['title'=>'Synthetic public school photograph','avatar'=>'synthetic school photo.JPG']);
        $before=$row->fresh()->getAttributes();
        $expected='https://admin.example.test/tenant/public/upload/image/PhotoGallery/synthetic%20school%20photo.JPG';
        $this->assertSame($expected,app(PublicMediaUrl::class)->galleryPhoto($row->avatar));
        $this->assertFileDoesNotExist(public_path('upload/image/PhotoGallery/synthetic school photo.JPG'));
        foreach (['/',route('imagePage')] as $url) $this->get($url)->assertOk()->assertSee($expected,false)->assertSee($row->title)->assertDontSee('/public/public/',false)->assertDontSee('https://web.example.test/public/upload/image/PhotoGallery/',false);
        $this->assertSame($before,$row->fresh()->getAttributes());
    }

    public function test_real_video_heading_details_and_attachment_are_the_public_authority(): void
    {
        $row=VideoGallery::query()->forceCreate(['galleryHeading'=>'Synthetic assembly video','details'=>'Existing short description.','attachment'=>'school assembly.mp4']);
        $before=$row->fresh()->getAttributes();
        $expected='https://admin.example.test/tenant/public/upload/image/VideoGallery/school%20assembly.mp4';
        $this->get(route('videoPage'))->assertOk()->assertSee($row->galleryHeading)->assertSee($row->details)->assertSee('data-source="'.$expected.'"',false)->assertSee('data-type="file"',false)->assertDontSee('/public/public/',false)->assertDontSee('https://web.example.test/public/upload/image/',false);
        $this->assertSame($before,$row->fresh()->getAttributes()); $this->assertFileDoesNotExist(public_path('upload/image/VideoGallery/school assembly.mp4'));
    }

    public function test_each_photo_card_keeps_its_own_avatar_and_only_missing_photos_use_fallback(): void
    {
        $rows = collect([
            PhotoGallery::query()->forceCreate(['title'=>'Science exhibition','avatar'=>'science.jpg']),
            PhotoGallery::query()->forceCreate(['title'=>'School assembly','avatar'=>'assembly.png']),
            PhotoGallery::query()->forceCreate(['title'=>'Archive without photograph','avatar'=>null]),
        ]);
        $before = $rows->map(fn ($row) => $row->fresh()->getAttributes())->all();
        $response = $this->get(route('imagePage'))->assertOk()->assertDontSee('/public/public/', false);
        $dom = new \DOMDocument();
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $expected = [
            'https://admin.example.test/tenant/public/upload/image/PhotoGallery/science.jpg',
            'https://admin.example.test/tenant/public/upload/image/PhotoGallery/assembly.png',
            'https://web.example.test/public/img/campus.jpeg',
        ];
        foreach ($rows as $index => $row) {
            $cards = $xpath->query('//button[@data-title="'.$row->title.'"]');
            $this->assertGreaterThan(0, $cards->length);
            foreach ($cards as $card) {
                $this->assertSame($expected[$index], $card->getAttribute('data-image'));
                $this->assertSame($expected[$index], $xpath->query('.//img', $card)->item(0)->getAttribute('src'));
            }
        }
        $this->assertNotSame($expected[0], $expected[1]);
        $this->assertSame($before, $rows->map(fn ($row) => $row->fresh()->getAttributes())->all());
        if ($directory = getenv('GALLERY_WEB_QA_DIR')) {
            File::ensureDirectoryExists($directory);
            File::put($directory.'/photos.html', $response->getContent());
        }
    }

    public function test_missing_media_origin_does_not_misrepresent_valid_photos_as_default_images(): void
    {
        PhotoGallery::query()->forceCreate(['title'=>'Science exhibition','avatar'=>'science.jpg']);
        PhotoGallery::query()->forceCreate(['title'=>'School assembly','avatar'=>'assembly.png']);
        PhotoGallery::query()->forceCreate(['title'=>'Archive without photograph','avatar'=>null]);
        config(['media.public_base_url'=>null]);
        $response = $this->get(route('imagePage'))->assertOk()->assertSee('Image preview unavailable');
        $dom = new \DOMDocument();
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        foreach (['Science exhibition','School assembly'] as $title) {
            foreach ($xpath->query('//button[@data-title="'.$title.'"]') as $card) {
                $this->assertSame('', $card->getAttribute('data-image'));
                $this->assertSame(0, $xpath->query('.//img', $card)->length);
            }
        }
        foreach ($xpath->query('//button[@data-title="Archive without photograph"]') as $card) {
            $this->assertSame('https://web.example.test/public/img/campus.jpeg', $card->getAttribute('data-image'));
        }
        $response->assertDontSee('/PhotoGallery/science.jpg', false)->assertDontSee('/PhotoGallery/assembly.png', false);
        if ($directory = getenv('GALLERY_WEB_QA_DIR')) {
            File::ensureDirectoryExists($directory);
            File::put($directory.'/photos-unconfigured.html', $response->getContent());
        }
    }

    public function test_youtube_and_uploaded_videos_render_from_the_same_attachment_authority(): void
    {
        $youtube=VideoGallery::query()->forceCreate(['galleryHeading'=>'YouTube school event','details'=>"Highlights from the school science exhibition.\nStudents share their projects with families and teachers.",'attachment'=>'https://youtu.be/dQw4w9WgXcQ?t=12']);
        $upload=VideoGallery::query()->forceCreate(['galleryHeading'=>'Uploaded school event','details'=>"Our annual assembly brings the school community together.\nWatch the presentation and student performances.",'attachment'=>'public/upload/image/VideoGallery/assembly.mp4']);
        $response=$this->get(route('videoPage'))->assertOk();
        $response->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',false)->assertSee('data-type="embed"',false)
            ->assertSee('https://admin.example.test/tenant/public/upload/image/VideoGallery/assembly.mp4',false)->assertSee('data-type="file"',false)
            ->assertDontSee('/public/public/',false)->assertDontSee('https://web.example.test/public/upload/image/',false);
        $this->assertSame($youtube->attachment,$youtube->fresh()->attachment); $this->assertSame($upload->attachment,$upload->fresh()->attachment);
        config(['media.public_base_url'=>null]);
        $this->get(route('videoPage'))->assertOk()->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',false)->assertDontSee('/VideoGallery/assembly.mp4',false);
        if ($directory=getenv('GALLERY_WEB_QA_DIR')) { File::ensureDirectoryExists($directory); File::put($directory.'/both-modes.html',$response->getContent()); }
    }

    public function test_arbitrary_external_and_malicious_attachments_are_never_public_links(): void
    {
        foreach (['https://evil.test/movie.mp4','https://youtube.com.evil.test/watch?v=dQw4w9WgXcQ','javascript:alert(1)','<iframe src="https://evil.test"></iframe>','../outside.mp4'] as $value) {
            VideoGallery::query()->forceCreate(['galleryHeading'=>'Protected video','attachment'=>$value]);
            $this->assertNull(\App\Services\GalleryVideoSource::resolve($value,app(PublicMediaUrl::class))['url']);
        }
        $this->get(route('videoPage'))->assertOk()->assertDontSee('evil.test',false)->assertDontSee('javascript:alert',false)->assertDontSee('../outside.mp4',false);
    }

    public function test_missing_media_base_never_falls_back_to_web_upload_paths(): void
    {
        PhotoGallery::query()->forceCreate(['title'=>'Photo text remains','avatar'=>'private.jpg']);
        VideoGallery::query()->forceCreate(['galleryHeading'=>'Video text remains','attachment'=>'private.mp4']);
        config(['media.public_base_url'=>null]);
        $this->assertNull(app(PublicMediaUrl::class)->galleryPhoto('private.jpg')); $this->assertNull(app(PublicMediaUrl::class)->galleryVideo('private.mp4'));
        foreach (['/',route('imagePage'),route('videoPage')] as $url) $this->get($url)->assertOk()->assertDontSee('/upload/image/PhotoGallery/private.jpg',false)->assertDontSee('/upload/image/VideoGallery/private.mp4',false);
    }

    public static function invalidFilenames(): array
    {
        return [[null],[''],['../private.jpg'],['/private.mp4'],['upload/image/PhotoGallery/x.jpg'],['https://other.test/x.mp4'],['a\\x.jpg'],['x%2ejpg'],['x.jpg?token=x'],['x.svg'],['x.php'],['x.jpg#fragment']];
    }
    #[DataProvider('invalidFilenames')]
    public function test_invalid_filenames_cannot_escape_canonical_gallery_contract(?string $name): void
    {
        $this->assertNull(app(PublicMediaUrl::class)->galleryPhoto($name)); $this->assertNull(app(PublicMediaUrl::class)->galleryVideo($name));
    }

    public function test_titles_and_descriptions_remain_escaped_and_reading_does_not_mutate_rows(): void
    {
        $attack='<img src=x onerror=alert(1)>';
        PhotoGallery::query()->forceCreate(['title'=>$attack,'avatar'=>'synthetic.jpg']);
        VideoGallery::query()->forceCreate(['galleryHeading'=>$attack,'details'=>$attack,'attachment'=>'synthetic.mp4']);
        foreach (['/',route('imagePage'),route('videoPage')] as $url) $this->get($url)->assertOk()->assertSee($attack)->assertDontSee($attack,false);
        $this->assertSame($attack,PhotoGallery::sole()->title); $this->assertSame($attack,VideoGallery::sole()->details);
    }

    public function test_existing_empty_gallery_demo_fallbacks_are_not_rewritten_by_media_fix(): void
    {
        $this->assertSame(0,PhotoGallery::count()); $this->assertSame(0,VideoGallery::count());
        $this->get(route('imagePage'))->assertOk()->assertSee('Annual Science Fair');
        $this->get(route('videoPage'))->assertOk()->assertSee('Orientation Day Highlights');
    }
}
