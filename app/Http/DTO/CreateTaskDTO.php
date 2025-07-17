<?php

namespace App\Http\DTO;

use Carbon\Carbon;

class CreateTaskDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public int $task_status_id,
        public string $due_date,
        public int $userId,
    ) {}

    public static function fromRequest(array $validated, int $userId): self
    {
        return new self(
            title: $validated['title'],
            description: $validated['description'] ?? null,
            task_status_id: 1,
            due_date: Carbon::parse($validated['due_date'])->format('Y-m-d H:i:s'),
            userId: $userId,
        );
    }
}

