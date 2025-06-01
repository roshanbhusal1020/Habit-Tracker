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

        return response()->json($session); // so backend creates this session id, sends to frontend ,specifically startimer after it sends its post data to the backend then it receives this id then it saves as currentSessionId. tehn moveToNextState takes this and send to backend or specifically complete() function then from that previously id sent from the backend it updates that specific session.


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
