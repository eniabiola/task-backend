<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.view.any') || $task->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('task.create');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.update.any') ||
            ($user->hasPermissionTo('task.update.own') && $task->user_id === $user->id);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.delete.any') ||
            ($user->hasPermissionTo('task.delete.own') && $task->user_id === $user->id);
    }

    public function changeStatus(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.change_status');
    }
}
