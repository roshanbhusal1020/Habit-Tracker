<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Pomodoro;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public function getJournalStats() {
        //most talked theme
    }

    public function getHabitTableStats() {
        // !!VERY IMPORTANT!!         
        // which dates tasks were completed + emotional/productivity state from the habit table
        // !!VERY IMPORTANT!!  
        
        //streak

        

        $habits = Habit::where('user_id', auth()->id())->get();

        return view('stats.habitChart.show', compact('habits'));

    }

    public function getTodoListStats() {
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
                                ->mapWithKeys(function($item){
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
        dump($completionRate, $priorityDistribution, $punchualityStats, $upcomingDeadlines, $completionTimeline );

        return view('stats.todoChart.show', compact('completionRate',
                                                    'priorityDistribution',
                                                    'punchualityStats',
                                                    'completionTimeline', 
                                                    'upcomingDeadlines'
                                                ));
      
    }

    public function overallAnalysis () {
        $userId = auth()->id();  
        $moodHabitId = DB::table('habits')
        ->where('name', 'Mood')
        ->value('id');
    
 
    
    $userId = auth()->id();
    $startOfMonth =  now()->startOfMonth()->subMonth();
    $endOfMonth =  now()->startOfMonth()->subDay();
    
    $data = DB::table('habit_entries as he1')
        ->join('habits as h1', 'he1.habit_id', '=', 'h1.id')
        ->join('habit_entries as he2', function ($join) use ($userId, $moodHabitId) {
            $join->on('he1.entry_date', '=', 'he2.entry_date')
                ->where('he2.user_id', '=', $userId)
                ->where('he2.habit_id', '=', $moodHabitId);
        })
        ->select(
            DB::raw('SUM(CASE WHEN he1.value = "yes" THEN 1 ELSE 0 END) as total_habits_completed'),  
            'he2.value as mood', // Mood rating
            'he1.entry_date'
        )
        ->where('he1.user_id', $userId)
        ->whereBetween('he1.entry_date', [$startOfMonth, $endOfMonth]) 
        ->groupBy('he1.entry_date', 'he2.value')
        ->get();
    

        


        return view('stats.dashboard', compact('data'));

        }

}
