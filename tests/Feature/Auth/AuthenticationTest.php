<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_valid_login_redirects_to_dashboard(): void
    {
        $user = User::factory()->operator()->create(['username' => 'operator-test', 'password' => 'password']);

        $this->post('/login', ['username' => $user->username, 'password' => 'password'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();
    }

    public function test_invalid_login_uses_general_message(): void
    {
        $this->from('/login')->post('/login', ['username' => 'unknown', 'password' => 'wrong'])
            ->assertRedirect('/login')->assertSessionHasErrors('username');
    }

    public function test_authenticated_user_is_redirected_from_login_and_can_logout(): void
    {
        $user = User::factory()->operator()->create();
        $this->actingAs($user)->get('/login')->assertRedirect(route('dashboard'));
        $this->actingAs($user)->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
