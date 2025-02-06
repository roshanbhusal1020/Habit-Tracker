<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitEntry;

use App\Models\Pomodoro;
use App\Models\Todo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StatsController extends Controller
{
    public function getPomodoroStats()
    {
     
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



    }

    public function getTodoListStats()
    {
       

    }

    public function overallAnalysis()
    {

      

    }

}
