<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subjects';
    protected $fillable = [
        'name',
        'description',
    ];
    public function faculty()
    {
        return $this->belongsToMany('App\Models\Faculty', 'faculties_subjects', 'subject_id', 'faculty_id')->withTimestamps();
    }
    public function students()
    {
        return $this->belongsToMany('App\Models\Students', 'scores', 'subject_id', 'student_id')->withTimestamps();
    }
}
