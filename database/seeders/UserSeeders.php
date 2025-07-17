<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $user = User::query()->create([
            'name' => $faker->name,
            'email' => "cilo@mailinator.com",
            'password' => Hash::make('password'), // Or bcrypt('password')
        ]);

        $user->assignRole('admin');
    }
}
