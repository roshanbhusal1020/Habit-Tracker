<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JournalController extends Controller
{
    public function show(Request $request)
    {
        $userId = auth()->id();

        $currentDate = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // Get today's entries
        $todayEntries = Journal::where('user_id', $userId)
            ->whereDate('created_at', $currentDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the previous and next dates that have entries
        $previousDate = Journal::where('user_id', $userId)
            ->whereDate('created_at', '<', $currentDate)
            ->orderBy('created_at', 'desc')
            ->first()?->created_at; // "?" is a safeguard so that it doesnt give error if there is nothing, so instead of giving error it will give null

        $nextDate = Journal::where('user_id', $userId)
            ->whereDate('created_at', '>', $currentDate)
            ->orderBy('created_at', 'asc')
            ->first()?->created_at;

        // Get all dates with entries for the calendar
        $datesWithEntries = Journal::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })->toJson();

        return view('pages.journal.show', compact(
            'todayEntries',
            'currentDate',
            'previousDate',
            'nextDate',
            'datesWithEntries'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $journal = Journal::create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Journal entry saved!');
    }
}
