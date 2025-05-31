<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\dd;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::all(); 
        
        return response()->json($habits);
        // Log::info(response()->json($habits));

    }

    public function getUserHabits($userId )
    {
   
        $user = DB::table('users')
                ->where('id', $userId)
                ->select('name', 'id', 'email')
                ->first();

        if (!$user) {
            return response()->json(['message'=>'User not found'], 404);
        }

        $habits = DB::table('table_habit')
                  ->where('user_id', $userId)
                  ->select('id', 'habitName', 'created_at', 'updated_at')
                  ->whereYear('created_at',2024)
                  ->whereMonth('created_at', 3)
                  ->get();


        $habitLogs = DB::table('habit_logs')
                    -> join('table_habit', 'habit_logs.habit_id', '=', 'table_habit.id')
                    ->where('table_habit.user_id', $userId)
                    ->select('habit_logs.id','habit_logs.habit_id', 'habit_logs.completed', 'habit_logs.created_at', 'habit_logs.updated_at')
                    ->get();


        $year =2024; 
        $month = 03;


        $structureData = [
            'name' =>$user->name,
            'email'=>$user->email, 
            'id'=>$user->id,
            'tableHabit'=>$habits->map(function($habit) use ($habitLogs){
                return [
                    'habitName'=>$habit->habitName, 
                    'id' =>$habit->id, 
                    'created_at' =>$habit->created_at, 
                    'updated_at' =>$habit->updated_at, 
                    'habit_logs'=>$habitLogs->where('habit_id', $habit->id)->values()->toArray()
                ];
            })
        ];

      
        dd( $structureData);
        // Log:: info('Fetched Habits:', $habits->toArray());


        return response()->json($structureData);


    }

    public function createAndLog(Request $request)
    {
        
       $currentDate = Carbon::now()->format('Y-m-d');

       $habitName= $request->input('habit.habitName');
    //    dd($habitName);                               // dd() here doesnt work because I am not sending data instead receiving and posting
       $exitingHabit = Habit::where('habitName', $habitName)->first();

        if($exitingHabit){
            return response()->json(['message'=> "HabitName '$habitName' already exists"], 409);
        }


       
        $userEmail = $request->all()['habit']['email'];
        $user = User::where('email', $userEmail)->firstOrFail();    // if I dont use it like this and instead use findadnfail it gives error

        $habit = $user->habits()->create([
            'habitName' => $request->all()['habit']['habitName']    
        ]);

        $habit->logs()->create([
            'date' => $currentDate,
            'completed' => $request->all()['habit']['completed']
        ]);

        return response()->json(['message' => 'Habit created and logged successfully'], 201);
    }



    public function update ($id)    // this doesnt work, working on this 
    {
        $newhabit = Habit::where('id', $habitId);

        $newhabit ->habitName = input('habit.habitName');   /// this 



    }



    public function destroy($habitId)
    {
        $habit = Habit::where('id', $habitId);

        $habit-> delete();

        return response()->json(['message'=>"habit 'habit' deleted successfully"], 200);

    }
}
