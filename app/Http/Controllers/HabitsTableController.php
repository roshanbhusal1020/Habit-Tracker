<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;


class HabitsTableController extends Controller
{
    public function show()
    {
        $habits = Habit::where('user_id', auth()->id())->get();
        
        // Generate all dates for the current month
        $currentDate = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $dates = collect();
        
        while ($currentDate <= $endOfMonth) {
            $dates->push($currentDate->copy());
            $currentDate->addDay();
        }
        
        $entries = HabitEntry::where('user_id', auth()->id())
                    ->whereMonth('entry_date', now()->month)
                    ->whereYear('entry_date', now()->year)
                    ->get()
                    ->groupBy('entry_date');
    
        return view('habits.show', compact('habits', 'entries', 'dates'));
    }

    public function edit ($id) 
    {
    
    }

    public function store (Request $request ) 
    {
        $habit = new Habit;
        $habit->user_id = auth()->id();
        $habit->name = $request->name; 
        $habit->type = $request->type;
        $habit->save();

        return redirect()->route('habits.show');
    }

    public function storeEntry (Request $request) 
    {
        // dump($request);
        return response()->json([
            'message' => 'Entry successful',
            'debug_data' => $request->all()  // This will be visible in the browser console
        ]);    }
}
