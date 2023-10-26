<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uName',
        'category',
        'uId',
        'content'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
