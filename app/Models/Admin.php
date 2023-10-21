<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User;


class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    // 사용자 권한 확인
    public function hasRole($role) {
        try {
            // 총관리자 시
            if($this->position === 'general_admin') {
                return response()->json([
                    'status' => true,
                    'role' => $this->position,
                ]);
            }
            // 관리자 시
            else if($this->position === 'admin')
            {
                return response()->json([
                    'status' => true,
                    'role' => $this->position,
                ]);
            }
            // 유저 시
            else 
            {
                response()->json([
                    'status' => false,
                    'role' => $this->position,
                ]);
            }
        } catch(\Exception $e){
            return false;
        }
        
    }
}
