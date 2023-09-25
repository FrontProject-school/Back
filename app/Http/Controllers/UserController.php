<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function update(Request $request, $id) {
        
        // 유저 검색 & 데이터 업데이트 
        $model = User::find($id); 
        $model->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "회원정보가 정상적으로 수정되었습니다!",
            'user' => $model
        ],200);
    }

    public function delete(User $user) {
        $user->delete();

        return response()->json([
            "status" => true,
            "message" => "회원이 정상적으로 탈퇴 되었습니다!"
        ],200);
    }
}
