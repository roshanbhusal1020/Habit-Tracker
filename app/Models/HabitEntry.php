<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitEntry extends Model
{
    use HasFactory;

    protected $fillable = ['habit_id', 'entry_date', 'value']; 


    public function habit() {
        return $this->belongsTo(Habit::class); 
    }
}
