<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'id',
        'name',
        'text',
        'image',
    ];

    public function disciplineUsers()
    {
        return $this->hasMany(UserDiscipline::class);
    }

    public function disciplineLectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function disciplineTasks()
    {
        return $this->hasMany(Task::class);
    }

    public function disciplineNotifications()
    {
        return $this->hasMany(Notification::class);
    }
}
