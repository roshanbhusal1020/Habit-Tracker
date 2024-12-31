<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = auth()->user()->todos()
            ->orderBy('completed')
            ->orderBy('due_date')
            ->orderBy('priority', 'desc')
            ->get();

        return view('pages.todo.show', compact('todos'));
    }

    public function store(Request $request) {
        // dump($request);

        //I should validate this data. 

        // $todos= auth()->user()->todos()->create()
        return response()->json(['request'=> $request]);
    }
   
    public function toggle(Request $request) {
 

        dump($request->id);
        return response()->json([
            'all' => $request->all(),
            'route_parameters' => $request->route(),
        ]);


    }

//     public function toggle(Todo $todo)
// {
//     $todo->update(['complete' => !$todo->complete]);

//     return redirect()->route('todo.index')->with('success', $todo);
// }
}
