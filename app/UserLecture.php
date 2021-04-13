<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLecture extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'lecture_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
