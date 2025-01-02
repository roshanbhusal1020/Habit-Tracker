<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'deleted_from', 'deleted_at']; 
    protected $dates = ['deleted_at', 'deleted_from'];

    public function entries() {
        return $this->hasMany(HabitEntry::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
