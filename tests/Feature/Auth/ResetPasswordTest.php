<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Event;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Password;
use Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeResetPasswordView(): void
    {
        $response = $this->get(route('password.reset', 'db0a1e9f6549'));

        $response->assertSuccessful();

        $response->assertViewIs('auth.reset-password');

        $response->assertSee('Reset Password');
        $response->assertSee('E-Mail Address');
        $response->assertSee('Reset');
    }

    public function testUserCanResetPassword(): void
    {
        Event::fake();

        $oldPassword = Str::random(16);

        /** @var User $user */
        $user = User::factory(['password' => bcrypt($oldPassword)])->create();

        $token = Password::broker()->createToken($user);

        $newPassword = Str::random(16);

        $response = $this
            ->from(route('password.reset', ['token' => $token]))
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

        $response->assertRedirect(route('login'));

        $response->assertSessionDoesntHaveErrors();

        $response->assertSessionHas('status', 'Your password has been reset!');

        $user->refresh();

        $this->assertFalse(Hash::check($oldPassword, $user->password));

        $this->assertTrue(Hash::check($newPassword, $user->password));

        Event::assertDispatched(PasswordReset::class);
    }

    public function testUserCannotResetPasswordWithInvalidToken(): void
    {
        Event::fake();

        $oldPassword = Str::random(16);

        /** @var User $user */
        $user = User::factory(['password' => bcrypt($oldPassword)])->create();

        $token = Str::random(36);

        $newPassword = Str::random(16);

        $response = $this
            ->from(route('password.reset', ['token' => $token]))
            ->post(route('password.update'),
                   [
                       'email' => $user->email,
                       'password' => $newPassword,
                       'password_confirmation' => $newPassword,
                   ]
            );

        $response->assertRedirect(route('password.reset', ['token' => $token]));

        $response->assertSessionHasErrors('token');

        $user->refresh();

        $this->assertTrue(Hash::check($oldPassword, $user->password));

        $this->assertFalse(Hash::check($newPassword, $user->password));

        Event::assertNotDispatched(PasswordReset::class);
    }

}
