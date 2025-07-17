<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskStatusHistory extends Model
{
    protected $fillable = ['task_id', 'old_status_id', 'new_status_id', 'user_id', 'changed_at', 'comment'];

    public function oldStatus() : BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'old_status_id');
    }
    public function newStatus() : BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'new_status_id');
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'new_status_id');
    }
}
