<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date'  => Carbon::parse($this->due_date)->format('d F Y'),
            'status'    => $this->taskstatus->name,
            'task_status_id' => $this->task_status_id,
            'status_badge_colour' => $this->taskstatus->badge_colour,
            'created_at'        => Carbon::parse($this->created_at)->format('d F Y'),
            'created_by'        => $this->when(auth()->user()->hasrole('admin'), $this->user->name)
        ];
    }
}
