<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;


class HabitsTableController extends Controller
{
    public function show () 
    {
        $habits = Habit::where('user_id', auth()->id())->get(); 
        $entries = HabitEntry::where('user_id', auth()->id())
                    ->whereMonth('entry_date', now()->month)
                    ->whereMonth('entry_date', now()->year)
                    ->get()
                    ->groupBy('entry_date');

        dump(auth()->id());            
        return view('habits.show', compact('habits', 'entries'));  

    }

    public function edit ($id) 
    {
    
    }

    public function store (Request $request ) 
    {
        dump($request);


        return response()->json(['success'=> true]); 
    }

}
