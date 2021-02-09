<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'faculty_id',
    ];

    public function faculty(){
        return $this->belongsTo('App\Models\Faculty', 'faculty_id', 'id');
    }
    public function students(){
        return $this->hasMany('App\Models\Students', 'class_id', 'id');
    }
}
