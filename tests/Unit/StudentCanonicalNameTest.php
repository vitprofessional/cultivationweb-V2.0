<?php

namespace Tests\Unit;

use App\Models\newAdmission;
use PHPUnit\Framework\TestCase;

class StudentCanonicalNameTest extends TestCase
{
    public function test_canonical_full_name_is_normalized(): void
    {
        $student = new newAdmission();
        $student->setRawAttributes(['fullName' => '  Amina   Rahman  ']);

        $this->assertSame('Amina Rahman', $student->student_name);
    }

    public function test_legacy_split_name_is_joined_without_duplicate_suffix(): void
    {
        $split = new newAdmission();
        $split->setRawAttributes(['fullName' => 'Amina', 'sureName' => 'Rahman']);
        $full = new newAdmission();
        $full->setRawAttributes(['fullName' => 'Amina Rahman', 'sureName' => 'Rahman']);

        $this->assertSame('Amina Rahman', $split->student_name);
        $this->assertSame('Amina Rahman', $full->student_name);
    }

    public function test_surname_fallback_and_empty_fallback_are_safe(): void
    {
        $legacy = new newAdmission();
        $legacy->setRawAttributes(['sureName' => 'Rahman']);
        $empty = new newAdmission();

        $this->assertSame('Rahman', $legacy->student_name);
        $this->assertSame('N/A', $empty->student_name);
    }
}
