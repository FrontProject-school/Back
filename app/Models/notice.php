<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notice extends Model
{
    use HasFactory;

    protected $primaryKey = 'num';

    protected $fillable = [
        'num',
        'adminNum',
        'title',
        'content',
        'confirm'
    ];

    public function getRouteKeyName()
    {
        return 'num';
    }
}
