<?php

namespace App\Http\Requests;

use App\Http\DTO\UpdateTaskStatusDTO;
use App\Models\Task;
use App\Rules\ValidTaskStatusTransition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaskStatusUpdateRequest extends FormRequest
{
    protected ?Task $task = null;

    protected function prepareForValidation(): void
    {
        // Safely fetch the route-bound Task model
        $this->task = $this->route('task');
    }

    public function rules(): array
    {
        $task = $this->route('task');
        return [
            'status_comment' => 'nullable|string',
            'task_status_id' => [
                'required',
                'exists:task_statuses,id',
                Auth::user()->hasRole('admin') ? null : new ValidTaskStatusTransition($task),
            ],
        ];
    }

    public function toDTO(): UpdateTaskStatusDTO
    {
        $task = $this->route('task');
        $validated = $this->validated();
        $validated['user_id'] = Auth::id();
        $validated['previous_status_id'] = $task->task_status_id;
        return UpdateTaskStatusDTO::fromArray($validated);
    }
}
