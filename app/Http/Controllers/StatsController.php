<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitEntry;

use App\Models\Pomodoro;
use App\Models\Todo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StatsController extends Controller
{
    public function getPomodoroStats()
    {
        // 1. Total Focus Hours
        $totalFocusHours = Pomodoro::where('user_id', auth()->id())
            ->where('type', 'Focus Time')
            ->where('completed', true)
            ->sum(DB::raw('duration_minutes')) / 60;

        // 2. Most Productive Time Slots
        $productiveTimeSlots = Pomodoro::where('user_id', auth()->id())
            ->where('type', 'Focus Time')
            ->where('completed', true)
            ->select(DB::raw('HOUR(started_at) as hour'), DB::raw('COUNT(*) as session_count'))
            ->groupBy('hour')
            ->orderBy('session_count', 'desc')
            ->get();

        // 3. Break Patterns
        $breakPatterns = Pomodoro::where('user_id', auth()->id())
            ->whereIn('type', ['Short Break', 'Long Break'])
            ->where('completed', true)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        // 4. Session Completion Rates
        $totalSessions = Pomodoro::where('user_id', auth()->id())
            ->where('type', 'Focus Time')
            ->count();

        $completedSessions = Pomodoro::where('user_id', auth()->id())
            ->where('type', 'Focus Time')
            ->where('completed', true)
            ->count();

        $completionRate = $totalSessions > 0
            ? ($completedSessions / $totalSessions) * 100
            : 0;

        // 5. Task Types Distribution
        $taskDistribution = Pomodoro::where('user_id', auth()->id())
            ->where('type', 'Focus Time')
            ->where('completed', true)
            ->select('task_name', DB::raw('COUNT(*) as count'))
            ->groupBy('task_name')
            ->get();

        // 6. Focus Time Distribution by Day
        $focusTimeByDay = Pomodoro::where('user_id', auth()->id())
            ->where('type', 'Focus Time')
            ->where('completed', true)
            ->select(
                DB::raw('DATE(started_at) as date'),
                DB::raw('SUM(duration_minutes) as total_minutes')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // dump($completionRate);

        // dump($focusTimeByDay);
        // dump($completedSessions);
        // dump($totalSessions);
        // dump($breakPatterns); // I could add here showing that how every after 3 short breaks is long break.
        // dump($productiveTimeSlots);
        // dump($totalFocusHours);

        // !!VERY IMPORTANT!!
        // which dates tasks were completed + emotional/productivity state from the habit table
        // !!VERY IMPORTANT!!


        return view('stats.show', compact(
            'totalFocusHours',
            'productiveTimeSlots',
            'breakPatterns',
            'completionRate',
            'taskDistribution',
            'focusTimeByDay'
        ));
    }

    // public function showPomodoroStat() {
    //     return view('stats.show');
    // }
    public function getJournalStats()
    {
        //most talked theme
    }

    public function getHabitTableStats()
    {
        // !!VERY IMPORTANT!!
        // which dates tasks were completed + emotional/productivity state from the habit table
        // !!VERY IMPORTANT!!

        //streak

        /** @var \App\Models\User */
        $authUser = auth()->user();
        $inputDates = HabitEntry::query()
            ->select([
                DB::raw('DISTINCT(entry_date) AS entry_date'),
            ])
            ->where('user_id', $authUser->id)
            ->where(DB::raw('DATE_FORMAT(entry_date, \'%Y-%m\')'), now()->format('Y-m'))
            ->get()
            ->pluck('entry_date');
        $userHabits = $authUser->habits()
            ->where('type', 'checkbox')
            ->where(DB::raw('DATE_FORMAT(month_year, \'%Y-%m\')'), now()->format('Y-m'))
            ->whereNull('deleted_from')
            ->get()
            ->map(function (Habit $habit) use ($authUser, $inputDates) {
                $completedDates = $habit->entries()->where('value', '1')->get()->pluck('entry_date');
                $uncompletedDates = collect($inputDates)->diff($completedDates)->values();

                /** @var \App\Models\Habit */
                $productivityHabit = $authUser->habits()
                    ->where('type', 'productivity')
                    ->first();
                $habit['productivity'] = $this->calculateHabit($productivityHabit, $completedDates, $uncompletedDates);

                /** @var \App\Models\Habit */
                $moodHabit = $authUser->habits()
                    ->where('type', 'mood')
                    ->first();
                $habit['mood'] = $this->calculateHabit($moodHabit, $completedDates, $uncompletedDates);

                return $habit;
            });

        $habits = $authUser->habits;

        return view('stats.habitChart.show', compact('habits', 'userHabits'));

    }

    public function calculateHabit(Habit $habit, Collection $completedDates, Collection $uncompletedDates)
    {
        $collection = collect();
    
        $completed = $habit->entries()
            ->whereIn('entry_date', $completedDates)
            ->get()
            ->pluck('value');
    
        $avgCompleted = $completed->count() > 0 ? $completed->sum() / $completed->count() : 0;
    
        $uncompleted = $habit->entries()
            ->whereIn('entry_date', $uncompletedDates)
            ->get()
            ->pluck('value');
    
        $avgUncompleted = $uncompleted->count() > 0 ? $uncompleted->sum() / $uncompleted->count() : 0;
    
        $difference = $avgCompleted - $avgUncompleted;
    
        $percentageIncrease = $avgUncompleted != 0
            ? ($difference / $avgUncompleted) * 100
            : null;
    
        $collection
            ->put('completed', $completed)
            ->put('avg_completed', $avgCompleted)
            ->put('uncompleted', $uncompleted)
            ->put('avg_uncompleted', $avgUncompleted)
            ->put('difference', $difference)
            ->put('percentage_increase', $percentageIncrease);
    
        return $collection;
    }
    

    public function getTodoListStats()
    {
        //completion rate- total completed; percentage of tasks completed

        // amount of priority lists (a break down of the list)

        // how long it took to finish the list on average

        //days when the tasks were completed

        // % of tasks completed(or not) before or after due_date (Punchuality)

        //upcoming deadlines


        //completion rate
        $todos = Todo::where('user_id', auth()->id());

        $allTodos = $todos->count();
        $completedTodos = $todos->clone()->where('completed', true)->count();
        $completionRate = $allTodos > 0 ? ($completedTodos / $allTodos) * 100 : 0;


        // priority distribution

        $priorityDistribution = $todos->clone()
                                ->select('priority', DB::raw('COUNT(*) as count'))
                                ->groupBy('priority')
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    $priority = match($item->priority) { // this is a really useful function, I could reuse it later.
                                        0 => 'Low',
                                        1 => 'Medium',
                                        2 => 'High',
                                        default => 'Unknown'
                                    };
                                    return [$priority => $item->count];
                                });

        //punchuality

        $punchualityStats = [
            'onTime' => $todos->clone()
                ->where('completed', true)
                ->whereNotNull('due_date')
                ->whereColumn('updated_at', '<=', 'due_date')
                ->count(),
            'Late' => $todos->clone()
                ->where('completed', true)
                ->whereNotNull('due_date')
                ->whereColumn('updated_at', '>', 'due_date')
                ->count(),
            'Overdue' => $todos->clone()
                ->where('completed', false)
                ->whereNotNull('due_date')
                ->where('updated_at', '<', now())
                ->count()


        ];

        $completionTimeline = $todos->clone()
                ->where('completed', true)
                ->select(DB::raw('DATE(updated_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

        $upcomingDeadlines = $todos->clone()
                ->where('completed', false)
                ->whereNotNull('due_date')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();
        // dump($completionRate, $priorityDistribution, $punchualityStats, $upcomingDeadlines, $completionTimeline);

        return view('stats.todoChart.show', compact(
            'completionRate',
            'priorityDistribution',
            'punchualityStats',
            'completionTimeline',
            'upcomingDeadlines'
        ));

    }

    public function overallAnalysis()
    {

        // Chart to generate:
        // habit done- mood -> finished more or less (NOT REALLY :())
        // journal - mood
        // journal - productivity
        // pomodoro - producitivty

        // Mood over the month-week chart
        // productivity over the month-week chart

        //add notification for weekly/monthly report

        $startDate = '2024-12-01';
        $endDate = '2024-12-31';
        $userId = 2;

        // First, get all habits for the given month
        $habits = Habit::forMonth($startDate)
            ->where('user_id', $userId)
            ->where('type', '!=', 'mood')  // Exclude mood/productivity habits
            ->where('type', '!=', 'productivity')
            ->get();

        // Get all entries for the date range
        $entries = HabitEntry::where('user_id', $userId)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        // Transform the data
        $transformedData = [];
        $dates = Carbon::parse($startDate)->daysUntil($endDate);

        foreach ($dates as $date) {
            $dateStr = $date->format('Y-m-d');

            // Find mood rating for this date
            $moodEntry = $entries->first(function($entry) use ($dateStr) {
                return $entry->entry_date == $dateStr &&
                       $entry->habit->type == 'mood';
            });

            // Convert mood text to number
            $moodRating = 2; // default neutral
            if ($moodEntry) {
                switch($moodEntry->value) {
                    case 'positive': $moodRating = 3; break;
                    case 'negative': $moodRating = 1; break;
                }
            }

            // Find productivity rating
            $prodEntry = $entries->first(function($entry) use ($dateStr) {
                return $entry->entry_date == $dateStr &&
                       $entry->habit->type == 'productivity';
            });

            // Convert productivity text to number
            $prodRating = 2; // default neutral
            if ($prodEntry) {
                switch($prodEntry->value) {
                    case 'productive': $prodRating = 3; break;
                    case 'unproductive': $prodRating = 1; break;
                }
            }

            $row = [
                'date' => $dateStr,
                'user_id' => $userId,
                'mood_rating' => $moodRating,
                'productivity_rating' => $prodRating
            ];

            // Add habit completion status
            foreach ($habits as $habit) {
                $completed = $entries->contains(function($entry) use ($dateStr, $habit) {
                    return $entry->entry_date == $dateStr &&
                           $entry->habit_id == $habit->id &&
                           $entry->value == '1';
                });
                $row['habit_' . $habit->id . '_completed'] = $completed ? 1 : 0;
            }

            $transformedData[] = $row;

        }






        return view('stats.dashboard', compact('transformedData'));

    }

}
