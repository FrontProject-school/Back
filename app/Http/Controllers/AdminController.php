<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     *      현재 등록 된 관리자 정보 리스트 호출
     */
    public function index()
    {
        $admin = Admin::all();

        return response()->json([
            'status' => true,
            'admin' => $admin,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     *      관리자 등록
     */

     public function store(Request $request)
     {
        $check_data = User::where('email', $request->email)->first();
        
        if(!$check_data){
        return response()->json([
            'status' => false,
            'message' => '해당 계정이 존재하지 않습니다.',
         ],404);
        } else {
            $check_admin_data = Admin::where('email', $request->email)->first();
            if($check_admin_data) {
                return response()->json([
                    'status' => false,
                    'message' => '해당 계정은 이미 등록되어 있습니다.',
                 ],404);
            } else {
                // 관리자 정보등록에 필요한 컬럼만 추출
                $data = [
                    "name" => $check_data["name"],
                    "email" => $check_data["email"],
                    "phone" => $check_data["phone"],
                    "position" => "admin"
                ];
                
                // 관리자 계정 등록
                Admin::create($data);
        
                return response()->json([
                    'status' => true,
                    'message' => '관리자가 추가되었습니다',
                    ],200);
                    }
        
        }
     }
 
     /**
      *     특정 관리자 정보 호출
      */
     public function show($id)
     {
        $admin1 = Admin::where('id',$id)->first();
        // $admin = Auth::user();
        if($admin1){
            return response()->json([
                'status' => true,
                'admin' => $admin1,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'admin' => null,
            ]);
        }
     }
 
     /**
      * Show the form for editing the specified resource.
      */
     public function edit(Admin $admin)
     {
         //
     }
 
     /**
      *      관리자 변경 
      */
 
     public function update(Request $request, $id)
     {
        $auth = auth()->user();
        $check = $auth->position;
        // 권한이 없는 관리자가 변경하려 할 시
        if(!$check=="general_admin"){
            return response()->json([
                'message' => '총 관리자가 아닙니다.',
            ]);
        }
        // 유저 찾아 직급 변경
        $email = $request->input('email');
        Admin::where('email', $email)
        ->update(['position' => 'general_admin']);

        // // "position"을 "admin"으로 업데이트
        Admin::where('id', $id)->update(['position' => 'admin']);
        return response()->json([
            'status' => true,
            'message' => "관리자가 정상적으로 위임되었습니다!"
        ],200);
 
     }
 
     /**
      *      관리자 해임
      */
 
     public function destroy($id)
     {

        $auth = auth()->user();
        $check = $auth->position;
        // 권한이 없는 관리자가 변경하려 할 시
        if(!$check=="general_admin"){
            return response()->json([
                "status" => false,
                'message' => '총 관리자가 아닙니다.',
            ]);
        }
        Admin::where('id', $id)->delete();

         return response()->json([
             "status" => true,
             "message" => "관리자가 삭제 되었습니다!",
         ],200);
 
     }
}
