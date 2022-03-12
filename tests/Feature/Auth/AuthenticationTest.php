<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthenticatedUserRedirectToLogin(): void
    {
        $response = $this->get(route('app'));

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function testUserCanViewLoginView(): void
    {
        $response = $this->get(route('login'));

        $response->assertSuccessful();

        $response->assertViewIs('auth.login');

        $this->assertGuest();
    }

    public function testUserCanRegisterInstead(): void
    {
        $response = $this->get(route('login'));

        $response->assertSee('Register');
    }

    public function testAuthenticatedUserRedirectToApp(): void
    {
        $user = $this->authenticate();

        $response = $this->get(route('login'));

        $response->assertStatus(302);

        $response->assertRedirect(route('app'));

        $this->assertAuthenticatedAs($user);
    }

    public function testUserCanAuthenticateWithCorrectCredentials(): void
    {
        /* @var $user User */
        $user = User::factory()->create(['password' => bcrypt($password = 'secret')]);

        $response = $this->post(route('login'), ['email' => $user->email, 'password' => $password,]);

        $response->assertRedirect(route('app'));

        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotAuthenticateWithIncorrectPassword(): void
    {
        /* @var $user User */
        $user = User::factory()->create(['password' => bcrypt($password = 'secret')]);

        $response = $this->from(route('login'))->post(
            route('login'),
            [
                'email' => $user->email,
                'password' => 'invalid-password',
            ]
        );

        $response->assertRedirect(route('login'));

        $response->assertSessionHasErrors('email');

        self::assertTrue(session()->hasOldInput('email'));

        self::assertFalse(session()->hasOldInput('password'));

        $this->assertGuest();
    }
}
