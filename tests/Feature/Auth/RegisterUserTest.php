<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUserCanSeeRegisterView(): void
    {
        $response = $this->get(route('register'));

        $response->assertSuccessful();

        $response->assertViewIs('auth.register');

        $response->assertSee('Register');
        $response->assertSee('Name');
        $response->assertSee('Password');
        $response->assertSee('Confirm Password');
    }

    public function testUserCanAuthenticateInstead(): void
    {
        $response = $this->get(route('register'));

        $response->assertSee('Sign in');
    }

    public function testAuthenticatedUserRedirectToApp(): void
    {
        $user = $this->authenticate();

        $response = $this->get(route('register'));

        $response->assertStatus(302);

        $response->assertRedirect(route('app'));

        $this->assertAuthenticatedAs($user);
    }

    public function testUserCanRegister(): void
    {
        Event::fake();

        $password = Str::random(16);
        $name = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();

        $request = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('register'), $request);

        $response->assertRedirect(route('app'));

        $response->assertSessionDoesntHaveErrors();

        $this->assertAuthenticated();

        Event::assertDispatched(Registered::class);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function testUserCannotRegisterWithoutName(): void
    {
        Event::fake();

        $password = Str::random(16);
        $email = $this->faker->unique()->safeEmail();

        $request = [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('register'), $request);

        $response->assertSessionHasErrors('name');

        $this->assertGuest();

        Event::assertNotDispatched(Registered::class);

        $this->assertDatabaseMissing('users', ['email' => $email]);
    }


    public function testUserCannotRegisterWithoutEmail(): void
    {
        Event::fake();

        $password = Str::random(16);
        $name = $this->faker->name();

        $request = [
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('register'), $request);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();

        Event::assertNotDispatched(Registered::class);

        $this->assertDatabaseMissing('users', ['name' => $name]);
    }

    public function testUserCannotRegisterWithAlreadyUsedEmail(): void
    {
        Event::fake();

        /** @var User $alreadyUser */
        $alreadyUser = User::factory()->create();

        $name = $this->faker->name();
        $password = Str::random(16);

        $request = [
            'name' => $name,
            'email' => $alreadyUser->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $response = $this->post(route('register'), $request);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();

        Event::assertNotDispatched(Registered::class);

        $this->assertDatabaseMissing('users', ['name' => $name]);
    }

    public function testUserCannotRegisterWithMismatchedPassword(): void
    {
        Event::fake();

        $name = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();

        $request = [
            'name' => $name,
            'email' => $email,
            'password' => Str::random(16),
            'password_confirmation' => Str::random(16)
        ];

        $response = $this->post(route('register'), $request);

        $response->assertSessionHasErrors('password');

        $this->assertGuest();

        Event::assertNotDispatched(Registered::class);

        $this->assertDatabaseMissing('users', [
            'name' => $name,
            'email' => $email
        ]);
    }
}
