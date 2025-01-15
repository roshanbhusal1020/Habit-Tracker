<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pomodoro extends Model
{
    use HasFactory;
    protected $table = 'pomodoro_sessions';

    protected $fillable = ['user_id','task_name', 'type', 'duration_minutes', 'started_at', 'completed_at', 'completed']; 

}
