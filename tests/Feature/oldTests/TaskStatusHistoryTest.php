<?php

namespace Tests\Feature;

use App\Models\TaskStatusHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelperTrait;

class TaskStatusHistoryTest extends TestCase
{
    use RefreshDatabase, TestHelperTrait;

    public function test_user_can_view_task_status_history_for_their_task()
    {
        ['user' => $user, 'task' => $task] = $this->createUserWithTask();

        TaskStatusHistory::factory()->count(2)->create(['task_id' => $task->id]);

        $response = $this->getJson("/api/task-status-histories/{$task->id}");

        $response->assertStatus(200);
        $responseData = $response->json('data');
        $this->assertCount(2, $responseData);
    }

    public function test_user_cannot_view_status_history_of_others_tasks()
    {
        $this->authenticateUser();
        $otherTask = $this->createTask();

        $response = $this->getJson("/api/task-status-histories/{$otherTask->id}");

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_task_status_history()
    {
        $task = $this->createTask();

        $response = $this->getJson("/api/task-status-histories/{$task->id}");

        $response->assertUnauthorized();
    }
}
