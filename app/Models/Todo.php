<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $table = 'todos';
    protected $fillable = ['task', 'completed', 'due_date', 'priority'];

    // protected $cast = ['completed'=>'boolean', 'due_date'=>'date']


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

