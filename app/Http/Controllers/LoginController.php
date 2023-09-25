<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LoginController extends Controller
{
    public function login(Request $request) 
    {
        $user = User::where('email', $request->email)->first();
        if(!Hash::check($request->password, $user->password)){
            return 'can not login';
        }
        
        $token = $user->createToken($user->name);

        return response()->json([
            'token' =>$token->plainTextToken, 
            'user'=>$user
        ],200);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            // tokens() 메서드 빨간줄 무시 가능
            $user->tokens()->delete();

            return response()->json([
                'state' => true,
                'message'=> '로그아웃 완료',
            ]);
        } else {
            return response()->json([
                'state' => false,
                'message'=> '로그아웃 실패: 사용자가 로그인되어 있지 않습니다.',
            ], 401);
        }   
    }

}
