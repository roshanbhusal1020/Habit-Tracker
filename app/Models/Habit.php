<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'deleted_from', 'month_year']; 
    protected $casts = [
        'deleted_from' => 'datetime',
        'month_year' => 'datetime'
    ];
    public function entries() {
        return $this->hasMany(HabitEntry::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
        // Scope to get habits for a specific month
        public function scopeForMonth($query, $date)
        {
            return $query->where(function($q) use ($date) {
                $q->whereNull('month_year')  // Get global habits (like Mood, Productivity)
                  ->orWhere('month_year', Carbon::parse($date)->startOfMonth());
            });
        }
}
