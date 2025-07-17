<?php

namespace App\Http\Service;

use App\Models\Task;
use App\Models\TaskStatusHistory;
use App\Http\DTO\UpdateTaskDTO;
use App\Http\DTO\UpdateTaskStatusDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function createTask(array $data): Task
    {
        return Task::query()->create($data);
    }

    public function updateTask(Task $task, UpdateTaskDTO $dto): Task
    {
        $task->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'task_status_id' => $dto->task_status_id,
            'due_date' => $dto->due_date,
        ]);

        return $task;
    }

    public function updateTaskStatus(Task $task, UpdateTaskStatusDTO $dto): Task
    {
        return DB::transaction(function () use ($task, $dto) {
            $oldStatusId = $task->task_status_id;

            $task->update([
                'task_status_id' => $dto->task_status_id,
            ]);

            $task_history = TaskStatusHistory::query()->create([
                'task_id'       => $task->id,
                'user_id'       => $dto->user_id,
                'old_status_id' => $oldStatusId,
                'new_status_id' => $dto->task_status_id,
                'changed_at'    => now(),
                'comment'       => $dto->status_comment,
            ]);

            return $task;
        });
    }

}
