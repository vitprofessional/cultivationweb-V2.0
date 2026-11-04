<?php

namespace Tests\Support;

use Illuminate\Support\Facades\Schema;

/** Test-only recovery of the Web application's externally-owned Admin schema. */
final class LegacyAdminSchema
{
    public static function ensure($app): void
    {
        $source = getenv('CULTIVATION_TEST_ADMIN_SOURCE');
        if (!$source) return;
        $db = $app['db']->connection();
        if (!$app->environment('testing') || $db->getDriverName() !== 'mysql'
            || $db->getConfig('host') !== '127.0.0.1' || $db->getDatabaseName() !== 'cultivation_test'
            || $db->selectOne('SELECT DATABASE() AS name')->name !== 'cultivation_test') {
            throw new \RuntimeException('Legacy schema recovery requires testing/mysql/127.0.0.1/cultivation_test.');
        }
        $tables = ['server_configs', 'student_managements', 'teacher_management', 'staff_management',
            'classes', 'departments', 'notices', 'managing_comittees', 'photo_galleries', 'video_galleries',
            'institute_details', 'placement_cells', 'needy_student_panels', 'principal_speeches',
            'syllabi', 'class_routines', 'exam_routines', 'semister_plans', 'new_admissions',
            'session_manages', 'class_manages', 'section_manages', 'home_infos', 'home_sliders', 'visitors'];
        $paths = glob(rtrim($source, '/\\').'/database/migrations/*.php');
        if (!$paths) throw new \RuntimeException('Missing committed Admin migration source.');
        sort($paths);
        foreach ($paths as $path) {
            $text = file_get_contents($path);
            if (preg_match('/Schema::create\(\s*[\'"]([^\'"]+)[\'"]/', $text, $match)
                && in_array($match[1], $tables, true) && !Schema::hasTable($match[1])) {
                (require $path)->up();
            }
        }
        $extensions = [
            '2024_10_06_000001_create_designations_table.php' => ['designations', null],
            '2026_05_19_000001_add_institute_type_and_active_theme_to_server_configs_table.php' => ['server_configs', 'instituteType'],
            '2026_09_27_000001_add_message_to_managing_comittees_table.php' => ['managing_comittees', 'message'],
            '2026_09_29_000001_add_full_name_to_teacher_and_staff_management.php' => ['teacher_management', 'fullName'],
            '2026_10_09_000001_add_homepage_layout_to_server_configs.php' => ['server_configs', 'homepage_layout'],
        ];
        foreach ($extensions as $filename => [$table, $column]) {
            if (!Schema::hasTable($table) || ($column && !Schema::hasColumn($table, $column))) {
                (require rtrim($source, '/\\').'/database/migrations/'.$filename)->up();
            }
        }
    }
}
