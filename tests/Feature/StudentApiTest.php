<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\StudentManagement;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_student()
    {
        $payload = [
            'name' => 'Test Student',
            'roll' => 'R123',
            'email' => 'student@example.com',
            'mobile' => '+12345678901',
            'session' => '2025-26',
            'department' => 'Science'
        ];

        $res = $this->postJson('/api/students', $payload);
        $res->assertStatus(201)->assertJsonFragment([
            'name' => 'Test Student',
            'roll' => 'R123'
        ]);
        $this->assertDatabaseHas('student_managements',[ // may need actual table name; adjust if different
            'fullName' => 'Test Student',
            'rollNumber' => 'R123'
        ]);
    }

    /** @test */
    public function it_lists_students_with_pagination()
    {
        StudentManagement::factory()->count(3)->create([
            'fullName' => 'Alpha',
            'rollNumber' => 'A1'
        ]);
        $res = $this->getJson('/api/students');
        $res->assertOk()->assertJsonStructure([
            'data', 'meta' => ['search','limit','total','page','pages']
        ]);
    }

    /** @test */
    public function it_validates_on_create()
    {
        $res = $this->postJson('/api/students', [ 'name' => 'A', 'roll' => '' ]);
        $res->assertStatus(422)->assertJsonStructure(['message','errors']);
    }
}
