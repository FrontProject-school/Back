<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    // 유저 정보 리스트 불러오기 (관리자 미들웨어 등록)
    public function getUserList(){
        $user = User::all();

        if($user){
            return response()->json([
                'status' => true,
                'user_list' => $user,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'user_list' => null,
            ]);
        }
    }

    // 유저 정보 불러오기
    public function getInfo() {
        $user = Auth::user();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id) {
        
        // 유저 검색 & 데이터 업데이트 
        $model = User::find($id); 
        $model->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "회원정보가 정상적으로 수정되었습니다!"
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
