<?php

namespace Database\Factories;

use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Habit::class; 

     public function definition()
    {
        return [
            'habitName'=>$this->faker->word,
        ];
    }
}
