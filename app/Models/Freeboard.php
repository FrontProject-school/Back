<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freeboard extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'imageNum',
        'studId',
        'title',
        'content'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
