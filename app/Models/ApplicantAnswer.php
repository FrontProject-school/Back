<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'pId',
        'studId',
        'aNumber',
        'answer'
    ];
}
