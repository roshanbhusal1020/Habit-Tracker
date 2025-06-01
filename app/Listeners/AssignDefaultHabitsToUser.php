<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Habit;
use Illuminate\Support\Facades\Log;

class AssignDefaultHabitsToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        Log::error($user);
        Log::error($user->id);

        if (!$user || !$user->id) {
            Log::error('User ID not available in AssignDefaultHabitsToUser event');
            return;
        }
        $habits = [
            ['name' => 'Productivity', 'type' => 'productivity'],
            ['name' => 'Mood', 'type' => 'mood'],
            ['name' => 'Note', 'type' => 'note'],
        ];
        
        foreach ($habits as $habit) {
            // Use associative array to ensure all fields are properly set


                $habitz = new Habit();
                $habitz->user_id = $user->id;
                $habitz->name = $habit['name'];
                $habitz->type = $habit['type'];
                // $habit->month_year = $isGlobalHabit ? null : $monthYear;
                $habitz->save();
     
        }
    }
}