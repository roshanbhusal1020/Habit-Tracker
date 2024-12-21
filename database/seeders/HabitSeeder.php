<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Habit;
use App\Models\User;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
 

        public function run()
        {
            $habits = [

                ['name' => 'Productivity', 'type' => 'productivity'],
                ['name' => 'Mood', 'type' => 'mood'],
            ];

            $users = User::all(); 


            foreach($users as $user) {
                foreach($habits as $habit) {

                    $exists = Habit::where('user_id', $user->id)
                                    ->where('name', $habit['name'])
                                    ->exists();
                    if(!$exists) {
                        Habit::create(['user_id'=> $user->id, 'name'=>$habit['name'], 'type'=>$habit['type']]);

                    }
                }
            }

         
        }


    }

