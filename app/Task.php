<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'creator_id',
        'assignee_id',
        'urgency',
        'is_private',
        'list_id',
        'task_name',
        'task_description',
    ];
}
