<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id', 'name', 'discipline_id', 'text', 'date_start', 'date_end', 'image', 'file'
    ];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
