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
        dump($request);

        // $todos= auth()->user()->todos()->create()
        return response()->json(['request'=> $request]);
    }
   
}
