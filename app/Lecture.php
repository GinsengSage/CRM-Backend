<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = [
        'id', 'name', 'discipline_id', 'text', 'date', 'image', 'file'
    ];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
