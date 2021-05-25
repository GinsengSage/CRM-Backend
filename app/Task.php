<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id', 'name', 'discipline_id', 'date_start', 'date_end', 'file', 'text'
    ];

    public $timestamps = false;

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
