<?php
namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class StaffCommitteePresentationTest extends TestCase
{
    public function test_profiles_omit_unsupported_fields_and_render_real_leadership_message(): void
    {
        $source = file_get_contents(resource_path('views/frontend/institute/teacher-show.blade.php'));
        $source = preg_replace('/@extends\([^\n]+\)|@section\([^\n]+\)|@endsection/', '', $source);
        foreach (['staff','committee'] as $profileKind) {
            $teacher = (object) ['fullName'=>'Real Person','designation'=>$profileKind === 'committee' ? 'President' : 'Office Assistant','avatar'=>null,'message'=>'Official saved message','mpoIndex'=>'PRIVATE_SENTINEL'];
            $html = Blade::render($source, compact('teacher','profileKind'));
            $this->assertStringContainsString('Real Person', $html);
            $this->assertStringNotContainsString('id="tp-contact"', $html);
            $this->assertStringNotContainsString('tp-personal">', $html);
            $this->assertStringNotContainsString('id="tp-about"', $html);
            $this->assertStringNotContainsString('PRIVATE_SENTINEL', $html);
            $this->assertStringContainsString($profileKind === 'committee' ? 'Leadership Information' : 'Professional Information', $html);
            if ($profileKind === 'committee') $this->assertStringContainsString('Official saved message', $html);
        }
    }
    public function test_real_roles_and_legacy_names_render_without_fake_profile_routes(): void
    {
        foreach (['staff', 'committee'] as $kind) {
            $records = collect([
                (object) ['id'=>1,'fullName'=>'Member One', 'firstName'=>'Old', 'lastName'=>'Name', 'designation'=>'Member', 'avatar'=>null, 'mobile'=>'12345', 'email'=>null],
                (object) ['id'=>2,'fullName'=>'Leader Name', 'designation'=>'Chairman', 'avatar'=>null, 'mobile'=>null, 'email'=>null],
                (object) ['id'=>3,'fullName'=>null, 'firstName'=>'Legacy Name', 'lastName'=>'Name', 'designation'=>'Support Staff', 'avatar'=>null, 'mobile'=>null, 'email'=>null],
            ]);
            $html = Blade::render('@include("frontend.institute.partials.people-directory", ["records"=>$records,"kind"=>$kind])', compact('records','kind'));
            $this->assertStringContainsString('Legacy Name', $html);
            $this->assertStringNotContainsString('Legacy Name Name', $html);
            $this->assertSame(3, substr_count($html, 'class="team-profile-link"'));
            $this->assertStringContainsString('data-people-portrait', $html);
            $this->assertSame($kind === 'committee' ? 1 : 0, substr_count($html, 'data-featured="true"'));
        }
    }
}
