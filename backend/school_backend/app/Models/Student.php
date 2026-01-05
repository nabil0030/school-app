<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

  protected $fillable = [
    'nom',
    'prenom',
    'email',
    'cin',
    'cin_image',
    'password',
    'bac_number',
    'bac_mention',
    'bac_year',
    'bac_image', 
       'role',
    'status',
    'admin_comment',
];


    protected $hidden = [
        'password',
    ];
}
