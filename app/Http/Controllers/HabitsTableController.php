<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserNotificationController;


class HabitsTableController extends Controller
{
    public function show(Request $request)
    {

        // dump(auth()->id());

        $targetDate = $request->date ? Carbon::parse($request->date) : now();

        // dump($targetDate);

        $habits = Habit::where('user_id', auth()->id())
        ->get()
        ->filter(function ($habit) use ($targetDate) {
            if ($habit->deleted_from && Carbon::parse($habit->deleted_from)->lte($targetDate)) {
                return false; //excludes habits that are deleted
            }

            if (in_array($habit->name, ['Mood', 'Productivity', 'Note'])) {
                return true;
            }

            $habitMonth = $habit->month_year ? Carbon::parse($habit->month_year)->format('Y-m') : null;
            $targetMonth = $targetDate->format('Y-m');

            return $habitMonth === null || $habitMonth === $targetMonth; //basically habit is included if $habitMonth is null or is equal $targetDate
        });
        // Generate all dates for the current month
        // $currentDate = now()->startOfMonth();
        // $endOfMonth = now()->endOfMonth();

        $startOfMonth = $targetDate->copy()->startOfMonth();
        $daysInMonth = $targetDate->daysInMonth;
        // dump($daysInMonth);
        $dates = collect();


        for ($day = 0; $day < $daysInMonth; $day++) {
            $date = $startOfMonth->copy()->addDays($day);

            $dates->push([
                'day' => $day,
                'formatted' => $date->format('D j'),
                'full_date' => $date->format('Y-m-d')
            ]);
            // dd($dates);
        }

        $moodHabit = Habit::where('user_id', auth()->id())
                        ->where('name', 'Mood')
                        ->first();

        $productivityHabit = Habit::where('user_id', auth()->id())
                ->where('name', 'Productivity')
                ->first();

        $noteHabit = Habit::where('user_id', auth()->id())
                ->where('name', 'Note')
                ->first();


        $entries = HabitEntry::where('user_id', auth()->id())
                    ->whereMonth('entry_date', $targetDate->month)
                    ->whereYear('entry_date', $targetDate->year)
                    ->get()
                    ->groupBy('entry_date');



        $previousMonth = $targetDate->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $targetDate->copy()->addMonth()->format('Y-m-d');
        $currentMonthDisplay = $targetDate->format('F Y');


        $userNotification = new UserNotificationController(); 
        $userNotification->create('2', 'test', 'hello I am just testing', 'localhost/tesitng', 0, 'idk');


        return view('habits.show', compact('habits', 'entries', 'dates', 'productivityHabit', 'moodHabit', 'noteHabit', 'previousMonth', 'nextMonth', 'currentMonthDisplay', 'targetDate'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'target_date' => 'required|date'  // Add this to receive the target month
        ]);

        try {
            $targetDate = Carbon::parse($validated['target_date']);
            $monthYear = $targetDate->startOfMonth();

            // Check for existing habit in the target month
            $existingHabit = Habit::where('user_id', auth()->id())
                ->where('name', $request->name)
                ->where('month_year', $monthYear)
                ->first();

            if ($existingHabit) {
                if ($existingHabit->deleted_from) {
                    $existingHabit->update([
                        'deleted_from' => null,
                        'type' => $request->type
                    ]);

                    return redirect()->route('habits.show', ['date' => $targetDate->format('Y-m-d')])
                        ->with('success', 'Habit restored successfully');
                }

                return redirect()->route('habits.show', ['date' => $targetDate->format('Y-m-d')])
                    ->with('error', 'A habit with this name already exists for this month');
            }

            // Special habits (Mood, Productivity, Note) should be global (no month_year)
            $isGlobalHabit = in_array($request->name, ['Mood', 'Productivity', 'Note']);

            $habit = new Habit();
            $habit->user_id = auth()->id();
            $habit->name = $request->name;
            $habit->type = $request->type;
            $habit->month_year = $isGlobalHabit ? null : $monthYear;
            $habit->save();

            return redirect()->route('habits.show', ['date' => $targetDate->format('Y-m-d')])
                ->with('success', 'Habit created successfully');

        } catch (\Exception $e) {
            return redirect()->route('habits.show', ['date' => $targetDate->format('Y-m-d')])
                ->with('error', 'Failed to create habit');
        }
    }


    public function storeEntry(Request $request)
    {

        $validated = $request->validate([
            'habit_id' => 'required|exists:habits,id',
            'date' => 'required|date',
            'value' => 'required'
        ]);



        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $existingEntry = HabitEntry::where([
            'user_id' => auth()->id(),
            'habit_id' => $validated['habit_id'],
            'entry_date' => $validated['date']
            ])->first();

            if ($request->name == 'note') {
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



    public function destroy(Request $request, Habit $habit)
    {
        try {

            if ($habit->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            DB::beginTransaction();

            // Only mark as deleted if it's not a global habit
            if ($habit->month_year) {
                $habit->update([
                    'deleted_from' => now()->startOfMonth()->format('Y-m-d')
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Habit successfully deleted'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to delete habit',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
