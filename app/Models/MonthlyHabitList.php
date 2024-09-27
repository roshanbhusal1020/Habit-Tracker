<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyHabitList extends Model
{
    use HasFactory;
    protected $table = "_list_of_monthly_habits";
    protected $fillable = ['user_id', 'HabitName']; 
}

