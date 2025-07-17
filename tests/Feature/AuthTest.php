<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register()
    {

        $email = $this->faker->unique()->email;
        $name = $this->faker->name;
        $response = $this->postJson('/api/register', [
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseHas('users', ['name' => $name]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'message',
                    'data' => [
                        'user' => ['id', 'name', 'email'],
                        'token',
                    ],
                ]);
    }

    public function test_user_cannot_login_with_wrong_credentials()
    {
        User::factory()->create([
            'email' => 'wrong@example.com',
            'password' => bcrypt('correct'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'incorrect',
        ]);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/logout');
        $response->assertStatus(204);
    }

    public function test_guest_cannot_logout()
    {
        $response = $this->postJson('/api/logout');

        $response->assertUnauthorized();
    }
}
