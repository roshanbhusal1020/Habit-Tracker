<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


class Habitstable extends Controller
{
    public function show () {
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('y');   
        $daysInMonth = Carbon::now()->daysInMonth; 
        // dd($currentMonth);
    
        return view('table', compact('currentMonth', 'currentYear', 'daysInMonth'));
    }
}
