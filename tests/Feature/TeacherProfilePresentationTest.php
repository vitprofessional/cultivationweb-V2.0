<?php
namespace Tests\Feature;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
class TeacherProfilePresentationTest extends TestCase
{
    public function test_profile_uses_real_identity_and_omits_invented_bio(): void
    {
        $source = file_get_contents(resource_path('views/frontend/institute/teacher-show.blade.php'));
        $source = preg_replace('/@extends\([^\n]+\)|@section\([^\n]+\)|@endsection/', '', $source);
        $teacher = (object) ['fullName'=>'Canonical Name','firstName'=>'Old','lastName'=>'Alias','designation'=>'Senior Teacher','avatar'=>null,'mpoIndex'=>'PRIVATE_MPO_SENTINEL','pdsId'=>'PRIVATE_PDS_SENTINEL'];
        $html = Blade::render($source, compact('teacher'));
        $this->assertStringContainsString('Canonical Name', $html);
        $this->assertStringNotContainsString('Old Alias', $html);
        $this->assertStringNotContainsString('id="tp-about"', $html);
        $this->assertStringContainsString('Professional Information', $html);
        foreach (['MPO Index','PDS ID','PRIVATE_MPO_SENTINEL','PRIVATE_PDS_SENTINEL'] as $private) $this->assertStringNotContainsString($private, $html);
        $this->assertStringContainsString('Personal Information', $html);
        $this->assertStringContainsString('<dd>-</dd>', $html);
        $teacher->description='Real biography';
        $html = Blade::render($source, compact('teacher'));
        $this->assertStringContainsString('Real biography', $html);
        $this->assertStringContainsString('id="tp-about"', $html);
    }
}
