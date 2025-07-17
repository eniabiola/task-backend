<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_own_task()
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('user');
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertOk()
            ->assertJson([
                'message' => 'Task returned',
                'data' => ['id' => $task->id],
            ]);
    }

    public function test_user_cannot_view_others_task()
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('user');
        $otherUser = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(400);
    }

    public function test_guest_cannot_view_task()
    {
        $task = Task::factory()->create();

        $this->getJson("/api/tasks/{$task->id}")
            ->assertUnauthorized();
    }
}
