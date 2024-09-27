<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bucketlist extends Model
{
    use HasFactory;
    protected $table = 'bucket_list';
    protected $fillable = ['user_id', 'item', 'completed'];
}
