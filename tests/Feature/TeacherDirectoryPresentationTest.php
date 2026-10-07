<?php
namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class TeacherDirectoryPresentationTest extends TestCase
{
    public function test_directory_shows_canonical_full_names_and_only_professional_card_fields(): void
    {
        $source = file_get_contents(resource_path('views/frontend/institute/teachers.blade.php'));
        $source = preg_replace('/@extends\([^\n]+\)|@section\([^\n]+\)|@endsection/', '', $source);
        $teachers = collect([
            (object) ['id'=>91, 'fullName'=>'Canonical Educator', 'firstName'=>'Old', 'lastName'=>'Alias', 'designation'=>'Senior Teacher', 'avatar'=>null, 'mobile'=>'private-phone', 'email'=>'private-email', 'religion'=>'private-religion', 'gender'=>'private-gender', 'bloodGroup'=>'private-blood'],
            (object) ['id'=>92, 'fullName'=>null, 'firstName'=>'Legacy Educator', 'lastName'=>'Educator', 'designation'=>'Assistant Teacher', 'avatar'=>null],
        ]);
        $html = Blade::render($source, ['Datakey'=>$teachers]);
        $this->assertStringContainsString('Canonical Educator', $html);
        $this->assertStringContainsString('Legacy Educator', $html);
        $this->assertStringNotContainsString('Educator Educator', $html);
        $this->assertStringNotContainsString('Old Alias', $html);
        foreach (['private-phone','private-email','private-religion','private-gender','private-blood','tel:','mailto:'] as $value) $this->assertStringNotContainsString($value, $html);
        $this->assertSame(2, substr_count($html, '>View Profile</a>'));
        $this->assertStringContainsString('OUR TEACHERS', $html);
        $this->assertStringContainsString('Meet Our <span>Teachers.</span>', $html);
        $this->assertStringContainsString('data-people-portrait', $html);
    }
}
