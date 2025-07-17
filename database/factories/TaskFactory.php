<?php

namespace Database\Factories;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!TaskStatus::query()->exists())
        {
            Artisan::call('db:seed', ['--class' => 'TaskStatusSeeder']);
        }

        return [
            'task_status_id' => TaskStatus::inRandomOrder()->first()->id,
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'), // Random due date within the next month
        ];
    }
}
