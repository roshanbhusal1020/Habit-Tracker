<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    //
    public function show() 
    {
        $journal = Journal::all();
        return view('pages.journal.viewJournal', compact('journal'));
    }

    public function create()
    {
        $testingUser =    Auth::user();
        // dump($testingUser);
    
        return view('pages.journal.testingJournal', compact('testingUser'));
    }
    public function store(Request $request)
    {
       $test =  $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $existingJournal = Journal::where('user_id', auth()->id())
                            ->whereDate('created_at', now()->toDateString())
                            ->first();
        dump($existingJournal);

        if ($existingJournal) {
            $existingJournal-> update([
    
                'content' => $existingJournal->content . "\n" . $request->input('content')
            ]);
        } else {

            Journal::create([
                'user_id' => auth()->id(),
                'title' => $test['title'],
                'content' => $test['content'],
            ]);
        }


        
    
        // Assuming you have a Journal model
        // Journal::create([
        //     'user_id' => auth()->id(),
        //     'title' => $request->title,
        //     'content' => $request->content,
        // ]);
    
            dump($test);
            return redirect()->route('journal.create')->with('success', 'Journal entry created successfully.');

    }
    public function edit ($id)
    {
        dump($id);
        $journal = Journal::findOrFail($id);
        return view('pages.journal.editJournal', compact('journal'));
    }

    public function update (Request $request, $id) 
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);

        $journal = Journal::findOrFail($id);
        $journal-> update([
            'title' => $request->input('title'), 
            'content' => $request->input('content')
        ]);
        return redirect()->route('journal.show', ['journal' => $journal->id])->with('success', 'Journal entry created successfully.');

    }
}

