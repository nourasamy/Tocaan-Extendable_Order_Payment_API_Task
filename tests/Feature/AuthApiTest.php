<?php

namespace Tests\Feature;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthApiTest extends ApiTestCase
{
    public function test_register_success(): void
    {
        $res = $this->postJson('/api/auth/register', [
            'name'                  => 'New User',
            'email'                 => 'new@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $res->assertStatus(Response::HTTP_CREATED);
        $res->assertJsonStructure(['success', 'message', 'data' => ['id', 'name', 'email', 'access_token']]);

        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $res = $this->postJson('/api/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $res->assertStatus(Response::HTTP_UNAUTHORIZED);
        $res->assertJsonStructure(['success', 'errors']);
    }

    public function test_logout_success(): void
    {
        $res = $this->postAuth('/api/auth/logout');
        $res->assertStatus(Response::HTTP_OK);
        $res->assertJsonStructure(['success', 'message', 'data']);
    }
}
