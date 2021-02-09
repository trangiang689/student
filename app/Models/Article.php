<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Article extends Model
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name', 'text'];
    protected $table = 'articles';
    protected $fillable = [
        'online',
    ];
}
