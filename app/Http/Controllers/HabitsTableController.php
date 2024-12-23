<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;  



class HabitsTableController extends Controller
{
    public function show()
    {

        dump(auth()->id()); 

        $habits = Habit::where('user_id', auth()->id())->get();
        
        // Generate all dates for the current month
        $currentDate = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
        // dump($daysInMonth);
        $dates = collect();
        

        for($day = 0; $day<$daysInMonth; $day++){
            $date = $startOfMonth->copy()->addDays($day); 
            
            $dates->push([
                'day'=> $day,
                'formatted'=>$date->format('D j'),
                'full_date'=>$date->format('Y-m-d')
            ]); 
            // dd($dates);
        }
        
        $moodHabit = Habit::where('user_id', auth()->id())
                        ->where('name', 'Mood')
                        ->first();
        
        $productivityHabit = Habit::where('user_id', auth()->id())
                ->where('name', 'Productivity')
                ->first();

        $NoteHabit = Habit::where('user_id', auth()->id())
                ->where('name', 'Note')
                ->first();


        $entries = HabitEntry::where('user_id', auth()->id())
                    ->whereMonth('entry_date', now()->month)
                    ->whereYear('entry_date', now()->year)
                    ->get()
                    ->groupBy('entry_date');
        dump($entries);
        dump($dates);
        dump($habits);
        dump($moodHabit);
        dump($productivityHabit);
        
    
        return view('habits.show', compact('habits', 'entries', 'dates', 'productivityHabit', 'moodHabit', 'NoteHabit'));
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

    public function storeEntry(Request $request) 
    {
        // First validate the request
        $validated = $request->validate([
            'habit_id' => 'required|exists:habits,id',
            'date' => 'required|date',
            'value' => 'required'
        ]);

    
        // Check authentication
        if(!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        try {
            $existingEntry = HabitEntry::where([
            'user_id' => auth()->id(), 
            'habit_id' => $validated['habit_id'], 
            'entry_date'=> $validated['date']
            ])->first();

            if($request->name == 'note') {
                $habitEntry = HabitEntry::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'habit_id' => $validated['habit_id'],
                        'entry_date' => $validated['date']  // Note: changed from 'date' to 'entry_date'
                    ],
                    [
                        'note' => $validated['value'], // !!Attention!!  This is extremly important, if its value instead of note then other habit dropdowns will be overwritten. I twill cause problem 
                        'value' => $existingEntry ? $existingEntry->value : "",
                        ]
                );
            } else {
                $habitEntry = HabitEntry::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'habit_id' => $validated['habit_id'],
                        'entry_date' => $validated['date']  // Note: changed from 'date' to 'entry_date'
                    ],
                    [
                        'value' => $validated['value'], 
                        'note' => $existingEntry ? $existingEntry->note : null,
                    ]
                );
            }

    
            return response()->json([
                'success' => true,
                'message' => 'Entry saved successfully',
                'entry' => $habitEntry  
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'error' => 'Failed to save entry',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
