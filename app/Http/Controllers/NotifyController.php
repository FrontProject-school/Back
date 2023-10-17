<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 추가
use Illuminate\Support\Facades\Auth;
use App\Models\Notify;
use App\Models\Program;

class NotifyController extends Controller
{
    /**
     *  알림 리스트
     */
    public function index()
    {
        // 현재 로그인 사용자
        $user = Auth::user();
        // 학번 추출
        $data = $user->stuId;
        // 보유 알림 추출
        $list = Notify::where('stuId','=',$data)
                ->get('pId');
        
        // 짬처리용 그릇
        $result =[];
        
        // 알림 추출물
        foreach ($list as $item) {
            
            $title = Program::where('pId','=',$item['pId'])
                    ->get('title');
            $check = Notify::where('stuId',$data)->where('pId', $item['pId'])->first()->check;

            foreach($title as $value) {
                $result[] = [ 'title'=>$value->title, 'pId'=>$item['pId'], 'check'=> $check ];
            }
        }   

        return response()->json([
            'result' => $result,
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     *  알림 읽음 전환
     */
    public function update(Request $request, string $pId)
    {
        // user id 추출
        $user = Auth::user();
        $stuId = $user->stuId;

        
        Notify::where('stuId',$stuId)->where('pId', $pId)->update(['check' => 'Y']);


        return response()->json([
            "status" => true,
            "message" => "알림 읽음 처리 완료",
        ]);
    }

    /**
     *  알림 삭제
     */
    public function destroy(string $pId)
    {   
        // user id 추출
        $user = Auth::user();
        $stuId = $user->stuId;

        // 알림 레코드 삭제
        Notify::where('stuId',$stuId)->where('pId',$pId)->delete();

        return response()->json([
            "status" => true,
            "message" => '알림이 정상적으로 삭제 되었습니다.'
        ]);
    }
}
