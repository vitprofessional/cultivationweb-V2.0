<?php

namespace Tests\Feature;

use App\Models\newAdmission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PublicStudentDraftExclusionTest extends TestCase
{
    use DatabaseTransactions;

    public function createApplication()
    {
        $app = parent::createApplication();
        $db = $app['db']->connection();
        if ($app->environment() !== 'testing' || $db->getDriverName() !== 'mysql'
            || ! in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)
            || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') {
            throw new \RuntimeException('Public Student QA requires local cultivation_test.');
        }

        return $app;
    }

    public function test_public_student_directory_and_profile_exclude_records_without_a_valid_class(): void
    {
        URL::forceRootUrl('http://localhost');

        $validClassId = DB::table('class_manages')->insertGetId(['className' => 'Draft QA Class']);
        $noClassId = DB::table('class_manages')->insertGetId(['className' => 'No Class']);
        $activeId = DB::table('new_admissions')->insertGetId([
            'stdId' => '93810001', 'fullName' => 'Public Active Student', 'className' => (string) $validClassId,
        ]);
        DB::table('new_admissions')->insert([
            ['stdId' => '93810002', 'fullName' => 'Public Missing Class Draft', 'className' => null],
            ['stdId' => '93810003', 'fullName' => 'Public No Class Draft', 'className' => (string) $noClassId],
            ['stdId' => '93810004', 'fullName' => 'Public Unresolved Class Draft', 'className' => '999999999'],
        ]);

        $directory = $this->get('/student');
        $directory->assertOk()
            ->assertSee('Public Active Student')
            ->assertDontSee('Public Missing Class Draft')
            ->assertDontSee('Public No Class Draft')
            ->assertDontSee('Public Unresolved Class Draft');

        $homepage = $this->get('/')->assertOk();
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$homepage->getContent());
        $xpath = new \DOMXPath($document);
        $studentMetric = $xpath->query('//div[@data-source="new_admissions COUNT(*)"]/div[contains(@class, "stat-body")]/h2')->item(0);
        $this->assertNotNull($studentMetric);
        $this->assertSame('1', trim($studentMetric->textContent));

        $profile = $this->get('/student/'.$activeId)->assertOk();
        $this->get('/student/'.($activeId + 1))->assertNotFound();

        $fixtureDirectory = getenv('PUBLIC_STUDENT_DRAFT_QA_DIR');
        if ($fixtureDirectory) {
            $temporaryRoot = realpath(sys_get_temp_dir());
            $resolvedDirectory = realpath($fixtureDirectory);
            if (! $temporaryRoot || ! $resolvedDirectory
                || ! str_starts_with($resolvedDirectory, $temporaryRoot.DIRECTORY_SEPARATOR)) {
                throw new \RuntimeException('Public Student browser fixtures must stay under the system temp directory.');
            }

            File::put($resolvedDirectory.'/homepage.html', $homepage->getContent());
            File::put($resolvedDirectory.'/student-list.html', $directory->getContent());
            File::put($resolvedDirectory.'/student-profile.html', $profile->getContent());
        }
    }
}
