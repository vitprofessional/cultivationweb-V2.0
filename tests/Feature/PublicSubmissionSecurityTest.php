<?php

namespace Tests\Feature;

use App\Models\PlacementCell;
use App\Models\needyStudentPanel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class PublicSubmissionSecurityTest extends TestCase
{
    use DatabaseTransactions;

    private string $uploadRoot;
    private string $sandboxRoot;

    public function createApplication()
    {
        $app = parent::createApplication();
        $connection = $app['db']->connection();
        if ($app->environment() !== 'testing'
            || $connection->getDriverName() !== 'mysql'
            || $connection->getDatabaseName() !== 'cultivation_test'
            || !in_array($connection->getConfig('host'), ['localhost', '127.0.0.1'], true)
            || $connection->selectOne('SELECT DATABASE() AS db')->db !== 'cultivation_test') {
            throw new RuntimeException('Public submission tests require local cultivation_test.');
        }

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.debug' => false, 'app.url' => 'http://localhost']);
        $this->app['url']->forceRootUrl('http://localhost');
        $this->sandboxRoot = sys_get_temp_dir().'/cultivation-public-submission-'.bin2hex(random_bytes(12));
        $this->uploadRoot = $this->sandboxRoot.'/public';
        File::ensureDirectoryExists($this->uploadRoot);
        File::ensureDirectoryExists($this->sandboxRoot.'/views');
        config(['view.compiled' => $this->sandboxRoot.'/views', 'logging.default' => 'stderr']);
        $this->app->usePublicPath($this->uploadRoot);
    }

    protected function tearDown(): void
    {
        $directory = $this->sandboxRoot ?? null;
        parent::tearDown();
        // Release request-held upload handles before deleting files on Windows.
        \Illuminate\Support\Facades\Facade::clearResolvedInstances();
        gc_collect_cycles();
        if ($directory !== null) {
            (new \Illuminate\Filesystem\Filesystem())->deleteDirectory($directory);
            $this->assertDirectoryDoesNotExist($directory);
        }
    }

    public static function flows(): array
    {
        return [
            'placement' => [PlacementCell::class, '/placementCell/save'],
            'job seeker' => [needyStudentPanel::class, '/jobNeedyStudentPanel/save'],
        ];
    }

    private function payload(string $model): array
    {
        $data = [
            'fullName' => 'Synthetic Applicant',
            'email' => 'synthetic@gmail.com',
            'mobile' => '01700000000',
            'sessionYear' => '2025-2026',
            'rollNumber' => '123456',
            'avatar' => UploadedFile::fake()->image('photo.png'),
        ];
        if ($model === PlacementCell::class) {
            $data += ['companyName' => 'Synthetic Company', 'designation' => 'Engineer', 'jobDetails' => 'Synthetic job details'];
        } else {
            $data['attachment'] = UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\nSynthetic CV\n%%EOF");
        }

        return $data;
    }

    #[DataProvider('flows')]
    public function test_guest_cannot_overwrite_an_existing_submission(string $model, string $uri): void
    {
        $target = new $model();
        $target->fullName = 'Protected synthetic record';
        $target->email = 'protected@example.invalid';
        $target->avatar = 'original.png';
        if ($model === needyStudentPanel::class) {
            $target->attachment = 'original.pdf';
        }
        $target->save();
        $directory = $model === PlacementCell::class ? 'placementCell' : 'neddyStudent';
        File::ensureDirectoryExists($this->uploadRoot.'/upload/image/'.$directory);
        $originalFile = $this->uploadRoot.'/upload/image/'.$directory.'/original.png';
        File::put($originalFile, 'Original synthetic attachment');
        if ($model === needyStudentPanel::class) {
            File::put(dirname($originalFile).'/original.pdf', 'Original synthetic CV');
        }
        $fileCount = count(File::allFiles($this->uploadRoot));
        $original = $target->fresh()->getAttributes();
        $count = $model::count();

        $response = $this->post($uri, $this->payload($model) + ['itemId' => $target->id]);

        $this->assertSame($original, $target->fresh()->getAttributes(), 'A guest must not overwrite a request-selected existing record.');
        $response->assertForbidden();
        $this->assertSame($count, $model::count());
        $this->assertCount($fileCount, File::allFiles($this->uploadRoot));
        $this->assertSame('Original synthetic attachment', File::get($originalFile));
        if ($model === needyStudentPanel::class) {
            $this->assertSame('Original synthetic CV', File::get(dirname($originalFile).'/original.pdf'));
        }
        $response->assertDontSee('Protected synthetic record')->assertDontSee('protected@example.invalid');
    }

    public static function invalidTargets(): array
    {
        $cases = [];
        foreach (self::flows() as $flow => [$model, $uri]) {
            foreach (['missing record' => 999999999, 'empty' => '', 'null' => null, 'zero' => 0, 'array' => ['id' => 1], 'sql-shaped' => "1 OR 1=1"] as $label => $id) {
                $cases[$flow.' '.$label] = [$model, $uri, $id];
            }
        }
        return $cases;
    }

    #[DataProvider('invalidTargets')]
    public function test_all_update_intent_is_denied_before_validation_or_lookup(string $model, string $uri, mixed $id): void
    {
        config(['app.debug' => true]);
        $queries = [];
        DB::listen(function ($query) use (&$queries) { $queries[] = $query->sql; });
        $this->postJson($uri, ['itemId' => $id, 'token' => 'forged', 'signature' => 'forged'])
            ->assertForbidden()->assertContent('Forbidden');
        $table = (new $model())->getTable();
        $this->assertSame([], array_values(array_filter($queries, fn ($query) => str_contains($query, $table))));
        $this->assertCount(0, File::allFiles($this->uploadRoot));
    }

    #[DataProvider('flows')]
    public function test_query_string_target_is_not_edit_authority(string $model, string $uri): void
    {
        $this->post($uri.'?itemId=123', [])->assertForbidden()->assertContent('Forbidden');
        $this->assertCount(0, File::allFiles($this->uploadRoot));
    }

    #[DataProvider('flows')]
    public function test_public_create_ignores_protected_fields_and_cannot_grant_later_edit_rights(string $model, string $uri): void
    {
        $before = $model::count();
        $protected = ['id' => 888888, 'record_id' => 888888, 'submission_id' => 888888, 'owner_id' => 99,
            'status' => 'approved', 'created_at' => '2000-01-01', 'updated_at' => '2000-01-01', 'token' => 'forged'];
        $this->from('/job/placement-cell')->post($uri, $this->payload($model) + $protected)
            ->assertRedirect('/job/placement-cell')->assertSessionHasNoErrors()->assertSessionHas('success');
        $a = $model::latest('id')->firstOrFail();
        $this->assertNotEquals(888888, $a->id);
        $this->assertSame('Synthetic Applicant', $a->fullName);
        $this->assertNotEquals('2000-01-01', $a->getRawOriginal('created_at'));
        $this->assertArrayNotHasKey('owner_id', $a->getAttributes());
        $this->assertArrayNotHasKey('status', $a->getAttributes());
        $this->assertMatchesRegularExpression('/^[a-f0-9-]{36}\.png$/', $a->avatar);
        if ($model === needyStudentPanel::class) {
            $this->assertMatchesRegularExpression('/^[a-f0-9-]{36}\.pdf$/', $a->attachment);
        }
        $this->assertSame($before + 1, $model::count());

        $this->post($uri, $this->payload($model))->assertSessionHasNoErrors()->assertSessionHas('success');
        $b = $model::latest('id')->firstOrFail();
        $aBefore = $a->fresh()->getAttributes();
        $bBefore = $b->fresh()->getAttributes();
        $fileCount = count(File::allFiles($this->uploadRoot));
        foreach ([$a->id, $b->id] as $targetId) {
            $this->post($uri, $this->payload($model) + ['itemId' => $targetId, 'email' => $a->email, 'token' => 'forged'])
                ->assertForbidden()->assertContent('Forbidden');
        }
        $this->assertSame($aBefore, $a->fresh()->getAttributes());
        $this->assertSame($bBefore, $b->fresh()->getAttributes());
        $this->assertSame($before + 2, $model::count());
        $this->assertCount($fileCount, File::allFiles($this->uploadRoot));
    }

    public static function invalidFields(): array
    {
        $cases = [];
        foreach (self::flows() as $flow => [$model, $uri]) {
            foreach (['fullName' => ['bad'], 'sessionYear' => str_repeat('x', 256), 'rollNumber' => 'bad', 'email' => 'not-email', 'avatar' => null] as $field => $value) {
                $cases[$flow.' '.$field] = [$model, $uri, $field, $value];
            }
        }
        return $cases;
    }

    #[DataProvider('invalidFields')]
    public function test_validation_happens_before_submission_or_file_writes(string $model, string $uri, string $field, mixed $value): void
    {
        $before = $model::count();
        $payload = $this->payload($model);
        $payload[$field] = $value;
        $this->post($uri, $payload)->assertSessionHasErrors($field);
        $this->assertSame($before, $model::count());
        $this->assertCount(0, File::allFiles($this->uploadRoot));
    }

    public static function unsafeUploads(): array
    {
        $cases = [];
        foreach (self::flows() as $flow => [$model, $uri]) {
            foreach (['executable extension', 'wrong mime', 'oversized'] as $kind) {
                $cases[$flow.' '.$kind] = [$model, $uri, $kind];
            }
        }
        return $cases;
    }

    #[DataProvider('unsafeUploads')]
    public function test_unsafe_uploads_are_rejected(string $model, string $uri, string $kind): void
    {
        $payload = $this->payload($model);
        $payload['avatar'] = match ($kind) {
            'executable extension' => UploadedFile::fake()->image('photo.html')->mimeType('image/jpeg'),
            'wrong mime' => UploadedFile::fake()->createWithContent('photo.png', '<?php echo "not an image";')->mimeType('text/x-php'),
            'oversized' => UploadedFile::fake()->image('photo.png')->size(5121),
        };
        $before = $model::count();
        $this->post($uri, $payload)->assertSessionHasErrors('avatar');
        $this->assertSame($before, $model::count());
        $this->assertCount(0, File::allFiles($this->uploadRoot));
    }

    public function test_cv_cannot_use_an_executable_extension(): void
    {
        $payload = $this->payload(needyStudentPanel::class);
        $payload['attachment'] = UploadedFile::fake()->createWithContent('cv.html', "%PDF-1.4\nSynthetic CV\n%%EOF")->mimeType('application/pdf');
        $this->post('/jobNeedyStudentPanel/save', $payload)->assertSessionHasErrors('attachment');
        $this->assertCount(0, File::allFiles($this->uploadRoot));
    }

    #[DataProvider('flows')]
    public function test_real_file_content_is_validated_and_filename_is_server_generated(string $model, string $uri): void
    {
        $payload = $this->payload($model);
        $path = $this->sandboxRoot.'/input-image';
        File::put($path, File::get($payload['avatar']->getRealPath()));
        $payload['avatar'] = new UploadedFile($path, 'untrusted.client.name.jpg', 'text/html', null, true);
        $this->post($uri, $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $record = $model::latest('id')->firstOrFail();
        // Content is PNG despite both a different client extension and a false client MIME.
        $this->assertMatchesRegularExpression('/^[a-f0-9-]{36}\.png$/', $record->avatar);

        File::put($path, '<?php echo "synthetic";');
        $payload = $this->payload($model);
        $payload['avatar'] = new UploadedFile($path, 'photo.png', 'image/png', null, true);
        $before = $model::count();
        $this->post($uri, $payload)->assertSessionHasErrors('avatar');
        $this->assertSame($before, $model::count());
    }

    public function test_placement_pdf_upload_remains_supported(): void
    {
        $payload = $this->payload(PlacementCell::class);
        $path = $this->sandboxRoot.'/input-pdf';
        File::put($path, "%PDF-1.4\nSynthetic PDF\n%%EOF");
        $payload['avatar'] = new UploadedFile($path, 'profile.pdf', 'application/pdf', null, true);
        $payload['joinDate'] = '2025-01-01';
        $this->post('/placementCell/save', $payload)->assertSessionHasNoErrors()->assertSessionHas('success');
        $record = PlacementCell::latest('id')->firstOrFail();
        $this->assertMatchesRegularExpression('/^[a-f0-9-]{36}\.pdf$/', $record->avatar);
        $this->assertSame('2025-01-01', $record->joinDate);
    }

    public function test_placement_validation_summary_displays_non_file_errors(): void
    {
        $errors = (new \Illuminate\Support\ViewErrorBag())->put('default', new \Illuminate\Support\MessageBag([
            'fullName' => 'The full name field is required.',
        ]));
        $this->withSession(['errors' => $errors])
            ->get('/job/placement-cell')->assertOk()->assertSee('The full name field is required.');
    }

    public function test_existing_homepage_smoke_check_with_transaction_isolation(): void
    {
        $this->app->usePublicPath(base_path('public'));
        $this->get('/')->assertOk();
    }

    #[DataProvider('flows')]
    public function test_csrf_and_non_get_mutation_contract_are_preserved(string $model, string $uri): void
    {
        $this->get($uri)->assertStatus(405);
        $middleware = new class($this->app, $this->app['encrypter']) extends ValidateCsrfToken {
            protected function runningUnitTests() { return false; }
        };
        $this->app->instance(ValidateCsrfToken::class, $middleware);
        $this->post($uri, $this->payload($model))->assertStatus(419);
        $this->assertCount(0, File::allFiles($this->uploadRoot));
        $this->withSession(['_token' => 'synthetic-csrf'])->post($uri, $this->payload($model) + ['_token' => 'synthetic-csrf'])
            ->assertSessionHasNoErrors()->assertSessionHas('success');
    }

    #[DataProvider('flows')]
    public function test_public_views_escape_submission_text_and_offer_create_only(string $model, string $uri): void
    {
        $record = new $model();
        $attack = '\"><img src=x onerror=alert(1)>';
        $record->fullName = $attack;
        $record->sessionYear = $attack;
        $record->email = $attack;
        $record->mobile = $attack;
        $record->rollNumber = $attack;
        if ($model === PlacementCell::class) {
            $record->companyName = $attack;
            $record->designation = $attack;
        }
        $record->save();
        $path = $model === PlacementCell::class ? '/job/placement-cell' : '/job/needy-student';
        $response = $this->get($path)->assertOk();
        $response->assertSee(e($attack), false)->assertDontSee($attack, false)
            ->assertSee('name="_token"', false)->assertDontSee('name="itemId"', false);
    }

    public function test_existing_needy_spam_controls_and_throttle_are_retained(): void
    {
        $this->post('/jobNeedyStudentPanel/save', ['website' => 'bot'])->assertSessionHas('error', 'Invalid submission detected.');
        $this->post('/jobNeedyStudentPanel/save', ['form_ts' => time()])->assertSessionHas('error', 'Please wait a moment before submitting.');
        $this->post('/jobNeedyStudentPanel/save', ['fullName' => 'https://spam.invalid'])->assertSessionHas('error', 'Links are not allowed in this form.');
        $route = $this->app['router']->getRoutes()->getByName('saveNeedyStdPanel');
        $this->assertContains('throttle:10,1', $route->gatherMiddleware());
        for ($i = 0; $i < 7; $i++) {
            $this->post('/jobNeedyStudentPanel/save', ['itemId' => 1])->assertForbidden();
        }
        $this->post('/jobNeedyStudentPanel/save', ['itemId' => 1])->assertStatus(429);
        $this->assertCount(0, File::allFiles($this->uploadRoot));
    }
}
