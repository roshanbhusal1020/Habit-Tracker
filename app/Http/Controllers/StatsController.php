<?php

namespace App\Http\Controllers;

use App\Models\Pomodoro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

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

    public function getHabitTableStats() {
        
    }

    public function getTodoListStats() {
        //completion rate- total completed; percentage of tasks completed

        // amount of priority lists (a break down of the list)

        // how long it took to finish the list on average

        //days when the tasks were completed

        // % of tasks completed(or not) before or after due_date (Punchuality)

        //upcoming deadlines

        // !!VERY IMPORTANT!!         
        // which dates tasks were completed + emotional/productivity state from the habit table
        // !!VERY IMPORTANT!!         
    }
}
