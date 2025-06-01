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

class ManageHabitActivities extends Command
{
    protected $signature = 'habits:check';
    protected $description = 'Check habits for inactivity and streaks';

    public function handle(): void
    {
        $inactivityThreshold = 3;
        $streakThreshold = 7;
        $streakCooldown = 7;

        $month = now()->month;
        $year = now()->year;

        $users = User::all();

        foreach ($users as $user) {

            $habits = Habit::where('user_id', $user->id)
            ->where('type', '!=', 'note')
            ->whereYear('month_year', $year)
            ->whereMonth('month_year', $month)
            ->with(['entries' => function ($query) {
                $query->orderBy('entry_date', 'desc');
            }])
            ->get();

            if ($user->id == 3) {
                $habits = Habit::where('user_id', $user->id)
                ->where('type', '!=', 'note')
                ->whereYear('month_year', $year)
                ->whereMonth('month_year', $month)
                ->with(['entries' => function ($query) {
                    $query->orderBy('entry_date', 'desc');
                }])
                ->get();

                $month = now()->month;
                $year = now()->year;


            }
            if ($habits->isEmpty()) {
                $user->notify(new HabitInactivityReminder(null, true));
                continue;
            }

            $inactiveHabits = [];

            foreach ($habits as $habit) {
                $latestEntry = $habit->entries->first();


                if (!$latestEntry || Carbon::parse($latestEntry->entry_date)->diffInDays(now()) >= $inactivityThreshold) {
                    $inactiveHabits[] = $habit->name;
                }


                $streakEntries = HabitEntry::where('habit_id', $habit->id)
                    ->whereDate('entry_date', '>=', now()->subDays($streakThreshold))
                    ->orderBy('entry_date', 'asc')
                    ->pluck('entry_date')
                    ->toArray();

                if (count($streakEntries) >= $streakThreshold) {

                    $recentNotification = DatabaseNotification::where('notifiable_id', $user->id)
                        ->where('notifiable_type', User::class)
                        ->where('type', HabitStreakNotification::class)
                        ->whereRaw("JSON_UNQUOTE(data->>'$.message') LIKE ?", ["%$habit->name%"])
                        ->where('created_at', '>=', now()->subDays($streakCooldown))
                        ->exists();

                    if (!$recentNotification) {
                        $user->notify(new HabitStreakNotification($habit->name, count($streakEntries)));
                    }
                }
            }

            if (!empty($inactiveHabits)) {
                $user->notify(new HabitInactivityReminder($inactiveHabits, false));
            }
        }
    }
}
