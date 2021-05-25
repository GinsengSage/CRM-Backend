<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = [
        'id', 'name', 'discipline_id', 'date', 'file', 'text'
    ];

    public $timestamps = false;

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
