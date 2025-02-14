<?php

namespace App\Console\Commands;

use App\Models\Habit;
use App\Models\User;
use App\Models\HabitEntry;
use App\Notifications\HabitInactivityReminder;
use App\Notifications\HabitStreakNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class ManageHabitActivities extends Command {
    protected $signature = 'habits:check';
    protected $description = 'Check habits for inactivity and streaks';

    public function handle(): void
    {
        $inactivityThreshold = 3; // days
        $streakThreshold = 7; // days
        $streakCooldown = 7; // days

        $month = now()->month; // Current month (e.g., 2 for February)
        $year = now()->year;   // Current year (e.g., 2025

        $users = User::all(); // Get all users

        foreach ($users as $user) {
            // Get user's habits excluding "note" type
            $habits = Habit::where('user_id', $user->id)
            ->where('type', '!=', 'note')
            ->whereYear('month_year', $year)
            ->whereMonth('month_year', $month)
            ->with(['entries' => function ($query) {
                $query->orderBy('entry_date', 'desc');
            }])
            ->get();

if($user->id ==3){
    // dump($habits);
                                $habits = Habit::where('user_id', $user->id)
                                ->where('type', '!=', 'note')
                                ->whereYear('month_year', $year)
                                ->whereMonth('month_year', $month)
                                ->with(['entries' => function ($query) {
                                    $query->orderBy('entry_date', 'desc');
                                }])
                                ->get();
    dump($habits);

    $month = now()->month; // Current month (e.g., 2 for February)
    $year = now()->year;   // Current year (e.g., 2025

    // dump($month);
    // dump($year);

}
            // If user has no habits, send a default reminder
            if ($habits->isEmpty()) {
                $user->notify(new HabitInactivityReminder(null, true));
                continue; // Skip to the next user
            }

            $inactiveHabits = [];

            foreach ($habits as $habit) {
                $latestEntry = $habit->entries->first(); // Get the latest entry

                // **Check for inactivity**
                if (!$latestEntry || Carbon::parse($latestEntry->entry_date)->diffInDays(now()) >= $inactivityThreshold) {
                    $inactiveHabits[] = $habit->name;
                }

                // **Check if the last $streakThreshold days have the same mood**
                $streakEntries = HabitEntry::where('habit_id', $habit->id)
                    ->whereDate('entry_date', '>=', now()->subDays($streakThreshold))
                    ->orderBy('entry_date', 'asc')
                    ->pluck('entry_date')
                    ->toArray();

                if (count($streakEntries) >= $streakThreshold) {

                    dump("here");

                    // Check if streak notification was recently sent
                    $recentNotification = DatabaseNotification::where('notifiable_id', $user->id)
                        ->where('notifiable_type', User::class)
                        ->where('type', HabitStreakNotification::class)
                        ->whereRaw("JSON_UNQUOTE(data->>'$.message') LIKE ?", ["%$habit->name%"])
                        ->where('created_at', '>=', now()->subDays($streakCooldown))
                        ->exists();

                    if (!$recentNotification) {
                        $user->notify(new HabitStreakNotification($habit->name, count($streakEntries))); // so here HabitStreakNotification just creates the notification in the background but only sends when notify() is called
                    }
                }
            }

            if (!empty($inactiveHabits)) {
                $user->notify(new HabitInactivityReminder($inactiveHabits, false));
            }
        }
    }
}
