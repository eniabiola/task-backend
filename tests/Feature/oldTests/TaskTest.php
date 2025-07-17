<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelperTrait;

class TaskTest extends TestCase
{
    use RefreshDatabase, TestHelperTrait;

    public function test_user_can_create_a_task()
    {
        $user = $this->authenticateUser();
        $this->createTaskStatus();

        $response = $this->postJson('/api/tasks', [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(10),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d\\TH:i'),
            'task_status_id' => TaskStatus::inRandomOrder()->first()->id,

        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'title',
                'description',
                'due_date',
                'status',
                'task_status_id',
                'status_badge_colour',
                'created_at',
            ],
        ]);
    }

    public function test_user_can_list_their_tasks()
    {
        $user = $this->authenticateUser();
        $this->createTask(['user_id' => $user->id]);
        $this->createTask(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_user_can_view_a_single_task()
    {
        ['user' => $user, 'task' => $task] = $this->createUserWithTask();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $task->id]);
    }

    public function test_user_can_update_task_status()
    {
        ['user' => $user, 'task' => $task] = $this->createUserWithTask();
        $newStatus = $this->createTaskNewStatus();

        $response = $this->patchJson("/api/tasks/{$task->id}/status", [
            'task_status_id' => $newStatus->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'due_date',
                    'status',
                    'task_status_id',
                    'status_badge_colour',
                    'created_at',
                ],
            ]);
        $responseData = $response->json('data');
        $this->assertEquals($newStatus->id, $responseData['task_status_id']);
    }

    public function test_user_can_delete_task()
    {
        ['user' => $user, 'task' => $task] = $this->createUserWithTask();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_unauthenticated_user_cannot_access_task_routes()
    {
        $task = $this->createTask();

        $this->getJson('/api/tasks')->assertUnauthorized();
        $this->postJson('/api/tasks', [])->assertUnauthorized();
        $this->getJson("/api/tasks/{$task->id}")->assertUnauthorized();
        $this->patchJson("/api/tasks/{$task->id}/status", [])->assertUnauthorized();
        $this->deleteJson("/api/tasks/{$task->id}")->assertUnauthorized();
    }

    public function test_user_cannot_access_another_users_task()
    {
        $user = $this->authenticateUser();
        $otherUser = $this->createUser();
        $otherUserTask = $this->createTask();
        $task = $this->createTask([
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->sentence(20),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'user_id' => $otherUser->id,
            'task_status_id' => TaskStatus::inRandomOrder()->first()->id,
        ]);

        $this->getJson("/api/tasks/{$otherUserTask->id}")->assertNotFound();
        $this->patchJson("/api/tasks/{$otherUserTask->id}/status", ['task_status_id' => 1])->assertNotFound();
        $this->deleteJson("/api/tasks/{$otherUserTask->id}")->assertNotFound();
    }

    public function test_create_task_requires_title_and_status_and_due_date()
    {
        $this->authenticateUser();

        $response = $this->postJson('/api/tasks', [
            'description' => 'Missing title and status',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'task_status_id', 'due_date']);
    }

    public function test_update_task_status_requires_valid_task_status_id()
    {
        ['task' => $task] = $this->createUserWithTask();

        $response = $this->patchJson("/api/tasks/{$task->id}/status", [
            'task_status_id' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['task_status_id']);
    }
}
