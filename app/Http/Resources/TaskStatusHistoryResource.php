<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskStatusHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'new_status_name' => $this->newStatus->name,
            'new_status_badge' => $this->newStatus->badge_colour,
            'old_status_name' => $this->oldStatus->name,
            'old_status_badge' => $this->oldStatus->badge_colour,
            'changed_at'       => $this->changed_at,
            'comment'          => $this->comment,
            'status_change_by' => $this->user->name ?? "",
        ];
    }
}
