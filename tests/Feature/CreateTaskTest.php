<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task()
    {
        $this->actingAs($user = User::factory()->create(), 'sanctum');

        $status = TaskStatus::factory()->create();
        $payload = [
            'title' => 'My First Task',
            'description' => 'Optional task description',
            'due_date' => now()->addDays(3)->format('Y-m-d\TH:i'),
            'task_status_id' => $status->id,
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertCreated();
        $response->assertJson([
            'message' => 'Task created',
            'data' => [
                'title' => $payload['title'],
                'description' => $payload['description'],
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_task()
    {
        $payload = [
            'title' => 'Guest Task',
            'due_date' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'task_status_id' => 1,
        ];

        $this->postJson('/api/tasks', $payload)
            ->assertUnauthorized();
    }

    public function test_validation_errors_on_invalid_task_payload()
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'due_date']);
    }
}
