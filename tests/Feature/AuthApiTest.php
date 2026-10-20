<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;

class AuthApiTest extends ApiTestCase
{
    public function test_register_success(): void
    {
        $res = $this->postJson('/api/auth/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $res->assertStatus(Response::HTTP_CREATED);
        $res->assertJsonStructure(['success', 'message', 'data' => ['id', 'name', 'email', 'access_token']]);

        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $res = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $res->assertStatus(Response::HTTP_UNAUTHORIZED);
        $res->assertJsonStructure(['success', 'errors']);
    }

    public function test_login_returns_authenticated_user(): void
    {
        $res = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $res->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.id', $this->user->id)
            ->assertJsonPath('data.name', $this->user->name)
            ->assertJsonPath('data.email', $this->user->email)
            ->assertJsonPath(
                'data.access_token',
                fn ($token) => is_string($token) && $token !== ''
            );
    }

    public function test_logout_success(): void
    {
        $res = $this->postAuth('/api/auth/logout');
        $res->assertStatus(Response::HTTP_OK);
        $res->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_logged_out_token_cannot_access_protected_routes(): void
    {
        $logoutResponse = $this->postAuth('/api/auth/logout');
        $logoutResponse->assertStatus(Response::HTTP_OK);

        $protectedResponse = $this->getAuth('/api/clients');

        $protectedResponse
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'success' => false,
            ]);
    }
}
