<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    //
    public function index() 
    {
        
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
        Journal::create([
            'user_id' => auth()->id(),
            'title' => $test['title'],
            'content' => $test['content'],
        ]);
        
    
        // Assuming you have a Journal model
        // Journal::create([
        //     'user_id' => auth()->id(),
        //     'title' => $request->title,
        //     'content' => $request->content,
        // ]);
    
            dump($test);
            return redirect()->route('journal.create')->with('success', 'Journal entry created successfully.');

    }
}

