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


    public function edit (Todo $todo) {


        // return redirect()->route('pages.todo.edit', compact('todo'));
        return view('pages.todo.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo) {



        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'due_date' => 'nullable|date', 
            'priority' =>'required|integer|between:0,2'
        ]);

        $todo->update($validated);

        return redirect()->route('todo.index')->with(['success'=> $todo]);
        // return response()->json(['request' => $request, 'updatedInfo'=> $todo]);
    }
   
    public function toggle(Todo $todo) 
    // this seems better than Request $request, because this directly fetches the Class data. 
    // if it were for $request, I would have to put some input with id so that I get that id in the backend and do findOrFail and get from the Todo class,
    //  a long process. Without sending input id like currently what I am doing will just give empty attribute value, because nothing been sent to the backend. toggle{id}toggle, is url so it doesnt send the id to the backend
    {
        $todo->update(['completed' => !$todo->complete]);

        return redirect()->route('todo.index')->with('success', $todo);
    }

    public function destroy(Todo $todo) 
    {

        // dd($todo);
        // $this->authorize('delete', $todo);

        $todo->delete();
        return response()->json(['request'=> $todo]);
        // return redirect()->route('todo.index')->with('success', 'Task deleted successfully');
    }

}
