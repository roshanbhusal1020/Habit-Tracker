<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitEntry extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','habit_id', 'entry_date', 'value', 'note'];


    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
