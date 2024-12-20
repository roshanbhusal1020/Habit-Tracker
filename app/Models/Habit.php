<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type']; 

    public function entries() {
        return $this->hasMany(HabitEntry::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
