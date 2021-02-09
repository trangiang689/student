<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;
    protected $table = 'students';
    protected $fillable = [
        'full_name',
        'birth_date',
        'home_town',
        'phone_number',
        'class_id',
        'user_id',
        'avatar',
    ];

    protected $hidden = [
    'password',
    ];

    protected static function newFactory()
    {
        return StudentFactory::new();
    }

    public function class(){
        return $this->belongsTo('App\Models\Classes', 'class_id', 'id');
    }

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject', 'scores', 'student_id', 'subject_id')->withTimestamps()->withPivot('score');
    }
}
