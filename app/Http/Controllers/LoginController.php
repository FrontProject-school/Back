<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Contracts\Auth\Guard;

class LoginController extends Controller
{
    // 로그인 
    public function login(Request $request) 
    {
        $user = User::where('email', $request->email)->first();

        // 비밀번호 확인
        if(!Hash::check($request->password, $user->password)){
            return 'can not login';
        } else {
            // Admin 유저인지 확인 
            $admin = Admin::where('email', $request->email)->first();
            
            // Admin 일 경우 반환 값
            if($admin) {
                auth()->guard('admin')->login($admin);
                $token = $admin->createToken($admin->name);
              
                return response()->json([
                    'token' =>$token->plainTextToken,
                    'message' => '관리자 로그인 완료!', 
                ], 200);
            } 

            // 일반 유저일 경우 
            auth()->user();
            $token = $user->createToken($user->name);

            return response()->json([
                'token' => $token->plainTextToken, 
                'message' => '유저 로그인 완료!',
            ], 200);
        }
        
        
    }

    // 로그아웃
    public function logout(Request $request)
    {
        // 현재 로그인 사용자 정보 
        $auth_info = auth()->user();

        // 어드민 인지 판별
        $admin = Admin::where('email', $auth_info->email)->first();

        if ($admin) {
            $admin->tokens()->delete();

            return response()->json([
                'state' => true,
                'message' => '관리자 로그아웃 완료',
            ]);
        } else if ($auth_info) {
            // tokens() 메서드 빨간줄 무시 가능
            $auth_info->tokens()->delete();

            return response()->json([
                'state' => true,
                'message'=> '유저 로그아웃 완료',
            ]);
        } else {
            return response()->json([
                'state' => false,
                'message'=> '로그아웃 실패: 사용자가 로그인되어 있지 않습니다.',
            ], 401);
        }   
    }
}
