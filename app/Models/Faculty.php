<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Faculty extends Model
{
    use HasFactory,Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $table = 'faculties';
    protected $fillable = [
        'slug'
    ];
    public function classes(){
        return $this->hasMany('App\Models\Classes', 'faculty_id', 'id');
    }
    public function subject()
    {
        return $this->belongsToMany('App\Models\Subject', 'faculties_subjects', 'faculty_id', 'subject_id')->withTimestamps();;
    }
}
