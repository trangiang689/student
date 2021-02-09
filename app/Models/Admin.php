<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'admins';
    protected $fillable = [
        'full_name',
        'birth_date',
        'home_town',
        'phone_number',
        'adress',
        'user_id',
        'avatar',
    ];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
