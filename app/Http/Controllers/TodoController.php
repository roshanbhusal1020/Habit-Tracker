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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'required|integer|between:0,2',
        ]);

        $todos = auth()->user()->todos()->create($validated);
        return redirect()->route('todo.index')->with(['success' => 'Task added successfully']);
    }


    public function edit(Todo $todo)
    {
        return view('pages.todo.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {



        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'required|integer|between:0,2'
        ]);

        $todo->update($validated);

        return redirect()->route('todo.index')->with(['success' => $todo]);
    }

    public function toggle(Todo $todo)
    {
        $todo->update(['completed' => !$todo->complete]);

        return redirect()->back()->with('success', 'Todo updated successfully');
    }

    public function destroy(Todo $todo)
    {


        $todo->delete();
        return redirect()->route('todo.index')->with('success', 'Task deleted successfully!');
    }

}
