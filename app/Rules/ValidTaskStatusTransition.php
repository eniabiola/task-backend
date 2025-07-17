<?php

namespace App\Rules;

use App\Models\Task;
use App\Models\TaskStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidTaskStatusTransition implements ValidationRule
{

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentStatusId = $this->task->task_status_id;

        $targetStatus = TaskStatus::query()->find($value);

        if (! $targetStatus) {
            $fail("The selected status does not exist.");
            return;
        }

        $transitionExists = DB::table('status_transitions')
            ->where('from_status_id', $currentStatusId)
            ->where('to_status_id', (int)$value)
            ->exists();

        if (!$transitionExists) {
            $fromLabel = optional($this->task->taskStatus)->name ?? 'current';
            $fail("Task Status cannot be changed from $fromLabel to {$targetStatus->name}");
        }
    }

}
