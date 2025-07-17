<?php

namespace App\Http\DTO;

class UpdateTaskStatusDTO
{

    public function __construct(
        public int $task_status_id,
        public int $previous_status_id,
        public int $user_id,
        public ?string $status_comment
    )
    {
    }

    public static function fromArray(array $validated): self
    {
        return new self(
            task_status_id: (int)$validated['task_status_id'],
            previous_status_id: (int)$validated['previous_status_id'],
            user_id: (int)$validated['user_id'],
            status_comment: $validated['status_comment'] ?? null,
        );
    }
}

