<?php

namespace Tests\Feature;

use App\Models\UserAccounts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserOnboardingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_is_redirected_to_student_welcome_after_successful_login_when_password_change_is_not_required(): void
    {
        $user = $this->createUser(mustChangePassword: false);

        $response = $this->post(route('login.attempt'), [
            'username' => $user->username,
            'password' => 'TempPass123',
        ]);

        $response->assertRedirect(route('student.welcome'));
        $response->assertSessionHas('auth_user_id', $user->id);
    }

    public function test_admin_is_redirected_to_user_accounts_page_after_successful_login(): void
    {
        $user = $this->createUser(role: 'admin', mustChangePassword: false);

        $response = $this->post(route('login.attempt'), [
            'username' => $user->username,
            'password' => 'TempPass123',
        ]);

        $response->assertRedirect(route('useraccounts.index'));
        $response->assertSessionHas('auth_user_id', $user->id);
        $response->assertSessionHas('success', 'Successful login.');
    }

    public function test_student_is_redirected_to_change_password_after_first_time_login(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->post(route('login.attempt'), [
            'username' => $user->username,
            'password' => 'TempPass123',
        ]);

        $response->assertRedirect(route('student.password.form'));
        $response->assertSessionHas('auth_user_id', $user->id);
        $response->assertSessionHas('info', 'Student login successful. Please change your password first.');
    }

    public function test_student_login_url_redirects_to_main_login(): void
    {
        $response = $this->get(route('student.login'));

        $response->assertRedirect(route('login'));
    }

    public function test_main_login_page_renders(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Login');
        $response->assertSee('Admin, teacher, and student accounts can use this login page.');
    }

    public function test_main_login_page_clears_existing_session(): void
    {
        $user = $this->createUser(mustChangePassword: false);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ])->get(route('login'));

        $response->assertOk();
        $response->assertSessionMissing('auth_user_id');
        $response->assertSee('Login');
    }

    public function test_protected_pages_reject_stale_sessions_without_confirmed_login(): void
    {
        $user = $this->createUser(role: 'admin', mustChangePassword: false);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
        ])->get(route('students.index'));

        $response->assertRedirect(route('login'));
        $response->assertSessionMissing('auth_user_id');
    }

    public function test_invalid_login_shows_incorrect_credentials_message(): void
    {
        $response = $this->from(route('login'))
            ->post(route('login.attempt'), [
                'username' => 'jericho',
                'password' => 'TempPass123',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'username' => 'Incorrect username or password.',
        ]);
    }

    public function test_main_login_accepts_student_accounts(): void
    {
        $user = $this->createUser(mustChangePassword: false);

        $response = $this->from(route('login'))
            ->post(route('login.attempt'), [
                'username' => $user->username,
                'password' => 'TempPass123',
            ]);

        $response->assertRedirect(route('student.welcome'));
        $response->assertSessionHas('auth_user_id', $user->id);
    }

    public function test_teacher_is_redirected_to_teacher_dashboard_after_successful_login(): void
    {
        $user = $this->createUser(role: 'teacher', mustChangePassword: false);

        $response = $this->post(route('login.attempt'), [
            'username' => $user->email,
            'password' => 'TempPass123',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $response->assertSessionHas('auth_user_id', $user->id);
    }

    public function test_logout_returns_user_to_main_login_page(): void
    {
        $user = $this->createUser(role: 'admin', mustChangePassword: false);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ])->post(route('logout'));

        $response->assertRedirect(route('login'));
        $response->assertSessionMissing('auth_user_id');
    }

    public function test_change_password_page_renders_for_new_student(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ])->get(route('student.password.form'));

        $response->assertOk();
        $response->assertSee('Update your student password');
        $response->assertSee('Change Password First');
        $response->assertSee('Change Password');
    }

    public function test_user_can_change_password_and_continue_to_student_welcome_page(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->followingRedirects()
            ->withSession([
                'auth_user_id' => $user->id,
                'auth_username' => $user->username,
                'auth_user_role' => $user->role,
                'auth_login_confirmed' => true,
            ])->post(route('student.password.update'), [
                'current_password' => 'TempPass123',
                'password' => 'NewPass123',
                'password_confirmation' => 'NewPass123',
            ]);

        $this->assertTrue(Hash::check('NewPass123', $user->fresh()->password));
        $this->assertFalse($user->fresh()->must_change_password);
        $response->assertOk();
        $response->assertSee('Password changed successfully. Welcome to the web page.');
        $response->assertSee('Welcome Student0');
        $response->assertSee('Your student account is ready. You can now continue using the student portal with your updated credentials.');
        $response->assertSee(route('student.password.form'), false);
        $response->assertSee(route('logout'), false);
    }

    public function test_wrong_current_password_shows_error_message(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->from(route('student.password.form'))
            ->withSession([
                'auth_user_id' => $user->id,
                'auth_username' => $user->username,
                'auth_user_role' => $user->role,
                'auth_login_confirmed' => true,
            ])->post(route('student.password.update'), [
                'current_password' => 'WrongPass123',
                'password' => 'NewPass123',
                'password_confirmation' => 'NewPass123',
            ]);

        $response->assertRedirect(route('student.password.form'));
        $response->assertSessionHasErrors([
            'current_password' => 'Wrong password.',
        ]);
    }

    public function test_wrong_login_password_shows_incorrect_credentials_message(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->from(route('login'))
            ->post(route('login.attempt'), [
                'username' => $user->username,
                'password' => 'WrongPass123',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'username' => 'Incorrect username or password.',
        ]);
    }

    public function test_password_confirmation_mismatch_shows_error_message(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->from(route('student.password.form'))
            ->withSession([
                'auth_user_id' => $user->id,
                'auth_username' => $user->username,
                'auth_user_role' => $user->role,
                'auth_login_confirmed' => true,
            ])->post(route('student.password.update'), [
                'current_password' => 'TempPass123',
                'password' => 'NewPass123',
                'password_confirmation' => 'DifferentPass123',
            ]);

        $response->assertRedirect(route('student.password.form'));
        $response->assertSessionHasErrors([
            'password_confirmation' => 'Password not match.',
        ]);
    }

    public function test_student_welcome_page_redirects_back_to_change_password_until_done(): void
    {
        $user = $this->createUser(mustChangePassword: true);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ])->get(route('student.welcome'));

        $response->assertRedirect(route('student.password.form'));
    }

    public function test_student_welcome_page_renders_after_password_has_been_changed(): void
    {
        $user = $this->createUser(mustChangePassword: false);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ])->get(route('student.welcome'));

        $response->assertOk();
        $response->assertSee('Welcome Student0');
        $response->assertSee(route('student.password.form'), false);
        $response->assertSee(route('logout'), false);
    }

    public function test_logged_in_management_navigation_hides_welcome_and_change_password_links(): void
    {
        $user = $this->createUser(role: 'admin', mustChangePassword: false);

        $response = $this->withSession([
            'auth_user_id' => $user->id,
            'auth_username' => $user->username,
            'auth_user_role' => $user->role,
            'auth_login_confirmed' => true,
        ])->get(route('students.index'));

        $response->assertOk();
        $response->assertDontSee(route('welcome'), false);
        $response->assertDontSee(route('password.change.form'), false);
        $response->assertSee(route('students.index'), false);
        $response->assertSee(route('degrees.index'), false);
        $response->assertSee(route('logout'), false);
        $response->assertSee('Admin');
    }

    private function createUser(string $role = 'student', bool $mustChangePassword = false): UserAccounts
    {
        return UserAccounts::query()->create([
            'username' => $role.UserAccounts::query()->count(),
            'email' => $role.UserAccounts::query()->count().'@example.com',
            'password' => Hash::make('TempPass123'),
            'role' => $role,
            'must_change_password' => $mustChangePassword,
        ]);
    }
}
