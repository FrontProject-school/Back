<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterUserRequest;

class RegisterController extends Controller
{
    public function regist(RegisterUserRequest $request) {

        $check_email = explode('@', $request['email'])[1];
        $is_not_g_suite_mail = strcmp($check_email, 'g.yju.ac.kr');

        // G-suite 계정이 아닌 경우
        if ($is_not_g_suite_mail) {
            return response()->json([
                'status' => false,
                'message' => 'G-suite 계정이 아닙니다.',
            ], 422);
        } 

        User::create($request->all());
        
        return response()->json([
            'status'  => true,
            'message' => "회원이 정상적으로 등록 되었습니다.",
        ],200);
    }
}
