<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notice extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'adminId',
        'title',
        'content',
        'confirm'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
