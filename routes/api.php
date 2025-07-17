<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\TaskAPIController;
use App\Http\Controllers\API\TaskStatusAPIController;
use App\Http\Controllers\API\TaskStatusHistoryAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\UserRolePermissionAPIController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('tasks', TaskAPIController::class);
    Route::get('tasks/admin/user/{user}', [TaskAPIController::class, 'singleUserTasks'])->name('tasks.admin.user')->middleware('role:admin');
    Route::get('tasks/admin/all', [TaskAPIController::class, 'indexForAdmin'])->name('tasks.admin.all')->middleware('role:admin');
    Route::patch('tasks/{task}/status', [TaskAPIController::class, 'updateStatus']);
    Route::prefix('task-statuses')->middleware('role:admin')->group(function () {
        Route::resource('', TaskStatusAPIController::class)->except(['index', 'show']);
    });
    Route::resource('task-statuses', TaskStatusAPIController::class)->only(['index', 'show']);
    Route::get('task-status-histories/{taskId}', [TaskStatusHistoryAPIController::class, 'index']);
    Route::get('users', [UserAPIController::class, 'indexOfUsers'])->name('users.index')->middleware('role:admin');
    Route::patch('users/{user}/role', [UserRolePermissionAPIController::class, 'updateUserRole']);
    Route::get('roles', RoleAPIController::class)->name('roles.index')->middleware('role:admin');
});
