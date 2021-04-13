<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDiscipline extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'discipline_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
