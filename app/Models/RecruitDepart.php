<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitDepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'pId',
        'depart'
    ];
}
