<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        'title',
        'description',
        'time_creation',
        'time_conclusion',
        'status',
        'parent_task_id',
    ];
}
