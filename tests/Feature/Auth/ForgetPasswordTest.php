<?php

namespace Tests\Feature\Auth;


use App\Models\User;
use Exception;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Notification;
use Tests\TestCase;

class ForgetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewResetPasswordView(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertSuccessful();

        $response->assertViewIs('auth.forgot-password');
    }

    public function testAuthenticatedUserRedirectToApp(): void
    {
        $user = $this->authenticate();

        $response = $this->get(route('password.request'));

        $response->assertStatus(302);

        $response->assertRedirect(route('app'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @throws Exception
     */
    public function testUserCanRequestResetEmailLink(): void
    {
        Notification::fake();

        /* @var $user User */
        $user = User::factory()->create();

        $response = $this->from(route('password.request'))
            ->post(route('password.request'), ['email' => $user->email]);

        $response->assertRedirect(route('password.request'));

        $reset = DB::table('password_resets')
            ->where('email', $user->email)
            ->first();

        $this->assertNotNull($reset);

        Notification::assertSentTo($user, ResetPassword::class);

        $response->assertSessionHas('status', 'We have emailed your password reset link!');

        $this->assertGuest();
    }

    public function testUserCannotRequestRestPasswordWithNoEmail(): void
    {
        Notification::fake();

        $response = $this->from(route('password.request'))
            ->post(
                route('password.request'),
                [
                    'email' => '',
                ]
            );

        $response->assertRedirect(route('password.request'));

        $response->assertSessionHasErrors('email');

        Notification::assertNothingSent();

        $this->assertGuest();
    }

    /*This test hits just the validation*/
    public function testUserCannotRequestRestPasswordWithInvalidEmail(): void
    {
        Notification::fake();

        $response = $this->from(route('password.request'))
            ->post(
                route('password.request'),
                [
                    'email' => 'invalid-email',
                ]
            );

        $response->assertRedirect(route('password.request'));

        $response->assertSessionHasErrors('email');

        Notification::assertNothingSent();

        $this->assertGuest();
    }

    public function testUserCannotRequestResetPasswordWithNonExistentEmail(): void
    {
        Notification::fake();

        $response = $this->from(route('password.request'))
            ->post(route('password.request'), ['email' => 'invalid-email@gmail.com']);

        $response->assertRedirect(route('password.request'));

        $response->assertSessionHasErrors('email');

        Notification::assertNothingSent();

        $this->assertGuest();
    }
}
