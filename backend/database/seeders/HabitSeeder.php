<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HabitLog;
use App\Models\Habit;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found, skipping habit seeding.');
            return;
        }

        foreach ($users as $user) {
            $habits = Habit::factory()
                ->count(2)
                ->create(["user_id"=>$user->id]);


                foreach ($habits as $habit) {
                    HabitLog::factory()
                        ->count(5)
                        ->create(["habit_id"=>$habit->id]);
                }
        }


    }
}
