<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {
            Task::query()->create([
                'user_id' => 1,
                'title' => $faker->sentence(4),
                'description' => $faker->sentence(8),
                'task_status_id' => $faker->numberBetween(1,4),
                'due_date' => $faker->dateTimeBetween('now', '+1 month')
            ]);
        }
    }
}
