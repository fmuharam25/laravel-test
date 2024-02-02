<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_login_with_valid_email()
    {
        $response = $this->postJson('api/auth/login', [
            'username' => 'admin@growmodo.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Authorized']);
    }

    public function test_user_login_with_valid_username()
    {
        $response = $this->postJson('api/auth/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Authorized']);
    }
    
    public function test_user_login_with_invalid_creadentials()
    {
        $response = $this->postJson('api/auth/login', [
            'username' => 'invalid',
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized']);
    }
}
