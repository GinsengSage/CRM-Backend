<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = [
        'id', 'name', 'status', 'date', 'image'
    ];

    public function disicpline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function lectureUsers()
    {
        return $this->hasMany(UserDiscipline::class);
    }
}
