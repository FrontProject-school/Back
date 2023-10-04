<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freeboard extends Model
{
    use HasFactory;

    protected $primaryKey = 'num';

    protected $fillable = [
        'num',
        'imageNum',
        'stdId',
        'title',
        'content'
    ];

    public function getRouteKeyName()
    {
        return 'num';
    }
}
