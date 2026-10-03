<?php

namespace Tests\Feature;

use App\Models\ClassRoutine;
use App\Models\ClassRoutineItem;
use App\Models\classManage;
use App\Models\CultivationAdmin;
use App\Models\Department;
use App\Models\Room;
use App\Models\sectionManage;
use App\Models\sessionManage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class PublicClassRoutinePresentationTest extends TestCase
{
    public function test_academic_period_renders_teacher_and_period_room_override(): void
    {
        $routine = $this->routine();
        $entry = new ClassRoutineItem(['class_day' => 'Sunday', 'start_time' => '08:00:00', 'end_time' => '08:45:00', 'subject_id' => 1, 'subject_name' => 'Mathematics']);
        $teacher = new CultivationAdmin();
        $teacher->adminName = 'Teacher One';
        $room = new Room();
        $room->name = 'Science Lab';
        $entry->setRelation('teacher', $teacher);
        $entry->setRelation('room', $room);
        $inherited = new ClassRoutineItem(['class_day' => 'Monday', 'start_time' => '08:00:00', 'end_time' => '08:45:00', 'subject_id' => 2, 'subject_name' => 'English']);
        $inherited->setRelation('teacher', null);
        $inherited->setRelation('room', null);
        $routine->setRelation('entries', new Collection([$entry, $inherited]));

        $html = View::make('frontend.academic.partials._classRoutineGrid', [
            'routine' => $routine,
            'entries' => $routine->entries,
            'institutionName' => 'Example Academy',
        ])->render();

        $this->assertStringContainsString('Example Academy', $html);
        $this->assertStringContainsString('Mathematics', $html);
        $this->assertStringContainsString('Teacher One', $html);
        $this->assertStringContainsString('Science Lab', $html);
        $this->assertStringContainsString('Default Room', $html);
    }

    public function test_legacy_missing_values_and_activity_render_explicit_dashes(): void
    {
        $routine = $this->routine();
        $activity = new ClassRoutineItem(['class_day' => 'Monday', 'start_time' => '09:00:00', 'end_time' => '09:20:00', 'subject_id' => null, 'subject_name' => 'Assembly']);
        $activity->setRelation('teacher', null);
        $activity->setRelation('room', null);
        $routine->setRelation('entries', new Collection([$activity]));

        $html = View::make('frontend.academic.partials._classRoutineGrid', [
            'routine' => $routine,
            'entries' => $routine->entries,
            'institutionName' => null,
        ])->render();

        $this->assertStringContainsString('Assembly', $html);
        $this->assertStringContainsString('<b>Teacher</b>-', $html);
        $this->assertStringContainsString('<b>Room</b>-', $html);
        $this->assertStringContainsString('<b>Section</b>All sections', $html);
    }

    private function routine(): ClassRoutine
    {
        $routine = new ClassRoutine();
        $routine->title = 'Weekly Schedule';
        $class = new classManage(); $class->className = 'Six';
        $section = new sectionManage(); $section->section = 'A';
        $department = new Department(); $department->departmentName = 'Science';
        $session = new sessionManage(); $session->session = '2026';
        $room = new Room(); $room->name = 'Default Room';
        $routine->setRelation('class', $class);
        $routine->setRelation('section', $section);
        $routine->setRelation('department', $department);
        $routine->setRelation('session', $session);
        $routine->setRelation('defaultRoom', $room);

        return $routine;
    }
}
