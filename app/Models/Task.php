<?php

namespace App\Models;

use App\Models\Scopes\UserTasksScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Task extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = ['task_status_id', 'user_id', 'title', 'description', 'task_status_id', 'user_id', 'due_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taskStatus(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new UserTasksScope);
    }
}
