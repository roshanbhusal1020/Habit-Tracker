

public function run()
{
    $habits = [
        ['name' => 'Working Hours', 'type' => 'time_range'],
        ['name' => 'Exercise', 'type' => 'boolean'],
        ['name' => 'Reading', 'type' => 'boolean'],
        ['name' => 'Mood', 'type' => 'mood'],
    ];

    foreach ($habits as $habit) {
        Habit::create($habit);
    }
}


I should add here all the ideas for now