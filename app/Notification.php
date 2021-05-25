<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'id',
        'name',
        'discipline_id',
        'task_id',
        'student_id',
        'user_id',
        'text',
        'checked',
        'rated',
        'date'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
