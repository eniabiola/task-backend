<?php

namespace Tests\Unit;

use App\Http\DTO\UpdateTaskStatusDTO;
use App\Http\Service\TaskService;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
    }

    public function test_create_task()
    {
        $status = TaskStatus::factory()->create();
        $user = User::factory()->create();


        $data = [
            'title' => 'New Task',
            'description' => 'Task description',
            'task_status_id' => $status->id,
            'due_date' => now()->addDays(1),
            'user_id' => $user->id,
        ];

        $task = $this->service->createTask($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('New Task', $task->title);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

/*    public function test_update_task()
    {
        $status = TaskStatus::factory()->create();

        $task = Task::factory()->create([
            'title' => 'Old Title',
            'description' => 'Old description',
            'task_status_id' => 1,
            'due_date' => now()->addDays(1),
        ]);

        $dto = UpdateTaskDTO::fromArray([
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'task_status_id' => 2,
            'due_date' => now()->addDays(2)->toDateTimeString(),
            'userId' => $task->user_id,
        ]);

        $updatedTask = $this->service->updateTask($task, $dto);

        $this->assertEquals('Updated Title', $updatedTask->title);
        $this->assertEquals('Updated Description', $updatedTask->description);
        $this->assertEquals(2, $updatedTask->task_status_id);
    }*/

    public function test_update_task_status_creates_history_and_updates_status()
    {
        $status = TaskStatus::factory()->count(5)->create();
        $first_status = $status->first();
        $second_status = $status->last();
        $third_status = $status->skip(2)->first();
        $task = Task::factory()->create(['task_status_id' => $first_status->id]);
        $user =  User::factory()->create();

        $dto = UpdateTaskStatusDTO::fromArray([
            'task_status_id' => $second_status->id,
            'user_id' => $user->id,
            'previous_status_id' => $first_status->id,
            'status_comment' => 'Status updated',
        ]);

        $updatedTask = $this->service->updateTaskStatus($task, $dto);

        $this->assertEquals($second_status->id, $updatedTask->task_status_id);

        $this->assertDatabaseHas('task_status_histories', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'old_status_id' => $first_status->id,
            'new_status_id' => $second_status->id,
            'comment' => 'Status updated',
        ]);
    }

}

