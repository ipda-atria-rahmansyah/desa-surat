<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_page_is_displayed(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Login')
            ->assertSee('Silakan masuk dengan akun Anda')
            ->assertSee('Username');
    }

    public function test_login_requires_username_and_password(): void
    {
        $this->from('/login')
            ->post('/login')
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['username', 'password']);
    }
}
