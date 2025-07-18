<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_task_status()
    {
        $this->actingAs($user = User::factory()->create(), 'sanctum');

        $this->seed(TaskStatusSeeder::class);
        $oldStatus = TaskStatus::query()->first();
        $newStatus = TaskStatus::query()->skip(1)->first();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'task_status_id' => $oldStatus->id,
        ]);

        $payload = [
            'task_status_id' => $newStatus->id,
            'status_comment' => 'Moving forward',
        ];

        $response = $this->patchJson("/api/tasks/{$task->id}/status", $payload);

        $response->assertOk()
            ->assertJson([
                'message' => 'Task status updated',
                'data' => [
                    'id' => $task->id,
                    'task_status_id' => $newStatus->id,
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_status_id' => $newStatus->id,
        ]);

        $this->assertDatabaseHas('task_status_histories', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'old_status_id' => $oldStatus->id,
            'new_status_id' => $newStatus->id,
            'comment' => 'Moving forward',
        ]);
    }

    public function test_guest_cannot_update_task_status()
    {
        $task = Task::factory()->create();
        $status = TaskStatus::factory()->create();

        $this->patchJson("/api/tasks/{$task->id}/status", [
            'task_status_id' => $status->id,
        ])->assertUnauthorized();
    }

}
