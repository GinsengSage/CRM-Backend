<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{

    protected $fillable = [
        'id',
        'task_id',
        'user_id',
        'status',
        'score',
        'file'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
