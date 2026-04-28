<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();
        $statuses = ['to_do', 'in_progress', 'completed'];

        if ($users->isEmpty() || $categories->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            for ($i = 1; $i <= 10; $i++) {
                Task::create([
                    'title' => 'Task ' . $i . ' for ' . $user->name,
                    'description' => fake()->sentence(10),
                    'status' => fake()->randomElement($statuses),
                    'due_date' => fake()->optional()->dateTimeBetween('-5 days', '+10 days'),
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id,
                ]);
            }
        }
    }
}