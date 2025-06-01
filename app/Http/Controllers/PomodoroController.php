<?php

namespace App\Http\Controllers;

use App\Models\Pomodoro;
use Illuminate\Http\Request;

class PomodoroController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('pages/pomodoro/show', compact('user'));

    }

    public function start(Request $request)
    {
        $session = Pomodoro::create([
        'user_id' => auth()->id(),
        'task_name' => $request->task_name,
        'type' => $request->type,
        'started_at' => now(),
        'duration_minutes' => $request->duration]);

        return response()->json($session);


    }

    public function complete(Request $request)
    {
        $session = Pomodoro::find($request->session_id);
        $session->update([
            'completed_at' => now(),
            'completed' => true,
            'actual_duration' => $request->actual_duration
        ]);

        return response()->json($session);
    }
}
