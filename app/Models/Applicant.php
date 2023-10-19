<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    // 기본키 지정
    // protected $primaryKey = 'num';

    protected $fillable = [
        'id',
        'stuId',
        'program',
        'answer',
        'selected'
    ];
}
