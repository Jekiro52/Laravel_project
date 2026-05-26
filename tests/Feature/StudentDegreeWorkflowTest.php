<?php

namespace Tests\Feature;

use App\Models\Degree;
use App\Models\Student;
use App\Models\UserAccounts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class StudentDegreeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_store_validates_and_redirects_back_when_invalid(): void
    {
        $response = $this->withSession($this->authenticatedSession())
            ->from(route('students.create'))
            ->post(route('students.store'), [
                'first_name' => '',
                'last_name' => '',
                'address' => '',
                'contact' => 'ABC123',
                'email' => 'not-an-email',
                'degree_id' => 99999,
            ]);

        $response->assertRedirect(route('students.create'));
        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'address',
            'contact',
            'email',
            'degree_id',
        ]);
    }

    public function test_student_create_redirects_to_show_and_logs_the_event(): void
    {
        Log::spy();
        $degree = Degree::query()->create(['title' => 'BS Computer Science']);

        $response = $this->withSession($this->authenticatedSession())
            ->post(route('students.store'), [
                'first_name' => 'Anne',
                'middle_name' => 'Faye',
                'last_name' => 'Lopez',
                'address' => 'Manila, Philippines',
                'contact' => '+63 912 345 6789',
                'email' => 'anne.lopez@example.com',
                'degree_id' => $degree->id,
            ]);

        $student = Student::query()->firstOrFail();

        $response->assertRedirect(route('students.show', $student));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'email' => 'anne.lopez@example.com',
            'degree_id' => $degree->id,
        ]);

        Log::shouldHaveReceived('info')
            ->once()
            ->with('student.created', Mockery::on(function (array $context) use ($student, $degree): bool {
                return $context['student_id'] === $student->id
                    && $context['degree_id'] === $degree->id
                    && $context['email'] === 'anne.lopez@example.com'
                    && isset($context['ip']);
            }));
    }

    public function test_student_account_password_is_hashed_with_argon2id(): void
    {
        $degree = Degree::query()->create(['title' => 'BS Information Systems']);

        $response = $this->withSession($this->authenticatedSession())
            ->post(route('students.store'), [
                'first_name' => 'Jericho',
                'middle_name' => null,
                'last_name' => 'Santos',
                'address' => 'Makati City',
                'contact' => '09171234567',
                'email' => 'jericho.santos@example.com',
                'degree_id' => $degree->id,
                'username' => 'jericho',
                'password' => 'TempPass123',
            ]);

        $response->assertRedirect();

        $account = UserAccounts::query()->where('username', 'jericho')->firstOrFail();

        $this->assertTrue(Str::startsWith($account->password, '$argon2id$'));
        $this->assertTrue(Hash::check('TempPass123', $account->password));
        $this->assertTrue($account->must_change_password);
    }

    public function test_degree_show_displays_related_students(): void
    {
        $degree = Degree::query()->create(['title' => 'BS Information Technology']);
        $student = Student::query()->create([
            'first_name' => 'Miguel',
            'middle_name' => null,
            'last_name' => 'Reyes',
            'address' => 'Cebu City',
            'contact' => '09123456789',
            'email' => 'miguel.reyes@example.com',
            'degree_id' => $degree->id,
        ]);

        $response = $this->withSession($this->authenticatedSession())
            ->get(route('degrees.show', $degree));

        $response->assertOk();
        $response->assertSee($degree->title);
        $response->assertSee($student->full_name);
        $response->assertSee($student->email);

        $this->assertTrue($student->degree->is($degree));
        $this->assertEquals(1, $degree->students()->count());
    }

    public function test_degree_delete_is_blocked_if_students_exist_and_logs_warning(): void
    {
        Log::spy();
        $degree = Degree::query()->create(['title' => 'BS Accountancy']);
        Student::query()->create([
            'first_name' => 'Paolo',
            'middle_name' => null,
            'last_name' => 'Cruz',
            'address' => 'Quezon City',
            'contact' => '09121231234',
            'email' => 'paolo.cruz@example.com',
            'degree_id' => $degree->id,
        ]);

        $response = $this->withSession($this->authenticatedSession())
            ->delete(route('degrees.destroy', $degree));

        $response->assertRedirect(route('degrees.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('degrees', ['id' => $degree->id]);

        Log::shouldHaveReceived('warning')
            ->once()
            ->with('degree.delete_blocked', Mockery::on(function (array $context) use ($degree): bool {
                return $context['degree_id'] === $degree->id
                    && $context['title'] === $degree->title
                    && $context['students_count'] === 1
                    && isset($context['ip']);
            }));
    }

    private function authenticatedSession(): array
    {
        $user = UserAccounts::query()->create([
            'username' => 'tester'.UserAccounts::query()->count(),
            'email' => 'tester'.UserAccounts::query()->count().'@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
            'must_change_password' => false,
        ]);

        return [
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_login_confirmed' => true,
        ];
    }
}
