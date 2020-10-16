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
    ];
}
