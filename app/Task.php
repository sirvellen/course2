<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task_name',
        'task_description',
        'creator_id',
        'assignee_id',
        'urgency',
        'is_private',
        'deadline',
        'project_id',
        'estimated_time',
        'done_time'
    ];

    protected $casts = [
        'id' => 'int',
        'creator_id' => 'int',
        'assignee_id' => 'int',
        'urgency' => 'int',
        'is_private' => 'bool',
        'deadline' => 'string',
        'project_id' => 'int',
        'status' => 'int',
    ];
}
