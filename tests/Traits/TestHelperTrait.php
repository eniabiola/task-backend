<?php

namespace Tests\Traits;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;

trait TestHelperTrait
{
    use WithFaker;

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function authenticateUser(array $attributes = []): User
    {
        $user = $this->createUser($attributes);
        Sanctum::actingAs($user);
        return $user;
    }

    protected function createTaskStatus(array $attributes = []): void
    {
        Artisan::call('db:seed', ['--class' => 'TaskStatusSeeder']);
    }

    protected function createTaskNewStatus(array $attributes = []): TaskStatus
    {
        return TaskStatus::factory()->create();
    }

    protected function createTask(array $attributes = []): Task
    {
        return Task::factory()->create($attributes);
    }

    protected function createUserWithTask(): array
    {

        $user = $this->authenticateUser();
        $this->createTaskStatus();
        $task = $this->createTask([
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->sentence(20),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'user_id' => $user->id,
            'task_status_id' => TaskStatus::inRandomOrder()->first()->id,
        ]);
        return compact('user', 'task');
    }
}
