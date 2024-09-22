<?php

namespace App\Http\Controllers;

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
        dump($testingUser);
    
        return view('pages.journal.testingJournal', compact('testingUser'));
    }
}

