<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PomodoroController extends Controller
{

    public function show()
    {
        $user = auth()->user();
        return view('pages/pomodoro/show', compact('user'));

    }
}
