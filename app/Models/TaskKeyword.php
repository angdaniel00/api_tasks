<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskKeyword extends Model
{
    protected $table = 'task_keywords';

    protected $fillable = [
        'task_id',
        'keyword_id',
    ];
}
