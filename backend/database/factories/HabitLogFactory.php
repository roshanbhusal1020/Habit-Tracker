<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\HabitLog;    
class HabitLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = HabitLog::class;

     public function definition()
    {


        return [
            'date'=>$this->faker->dateTimeBetween('2024-03-01', '2024-03-29')->format('Y-m-d'),
            'completed'=>$this->faker->boolean,
        ];
    }
}
