<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;

    protected $primaryKey = 'num';

    protected $fillable = [
        'num',
        'stdId',
        'title',
        'content',
        'answer',
        'secret'
    ];
}
