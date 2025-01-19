<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Models\Habit;
use Carbon\Carbon;
use App\Models\HabitEntry;

class HomepageController extends Controller
{
    
    public function show() {
        $targetDate = now();
        
        $habits = Habit::where('user_id', auth()->id())
            ->get()
            ->filter(function($habit) use ($targetDate) {
                if ($habit->deleted_from && Carbon::parse($habit->deleted_from)->lte($targetDate)) {
                    return false;
                }
                
                if (in_array($habit->name, ['Mood', 'Productivity', 'Note'])) {
                    return true;
                }
                
                $habitMonth = $habit->month_year ? Carbon::parse($habit->month_year)->format('Y-m') : null;
                $targetMonth = $targetDate->format('Y-m');
                
                return $habitMonth === null || $habitMonth === $targetMonth;
            });   
    
        // Fetch entries for today
        $entries = HabitEntry::where('user_id', auth()->id())
            ->whereDate('entry_date', $targetDate)
            ->get()
            ->groupBy('entry_date');
    
        $todos = auth()->user()->todos()
            ->where('due_date','<=', now())
            ->orderBy('completed')
            ->orderBy('due_date')
            ->orderBy('priority', 'desc')
            ->get();
    
        // Get special habits
        $moodHabit = Habit::where('user_id', auth()->id())
            ->where('name', 'Mood')
            ->first();
        
        $productivityHabit = Habit::where('user_id', auth()->id())
            ->where('name', 'Productivity')
            ->first();
    
        $noteHabit = Habit::where('user_id', auth()->id())
            ->where('name', 'Note')
            ->first();
    
        return view('dashboard', compact(
            'todos', 
            'habits', 
            'entries',
            'moodHabit',
            'productivityHabit',
            'noteHabit'
        ));
    }
}
