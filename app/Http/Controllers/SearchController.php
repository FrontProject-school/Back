<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notice;
use App\Models\Freeboard;

class SearchController extends Controller
{
    // 공지사항 & 자유게시판 검색 기능
    public function search(Request $request,string $boardType){
        
        $term = $request->input('term');   // 검색 단어

        $type = '';
        $status = false;
        $result = null;

        if($boardType === 'notice') {
            $type = 'notice';
            $status = true;
            $result = Notice::where('title', 'like', '%' . $term . '%')->get();

        } else if($boardType === 'freeboard') {
            $type = 'freeboard';
            $status = true;
            $result = Freeboard::where('title', 'like', '%' . $term . '%')->get();
            
        } else {
            $type = null;
            $status = false;
            $result = null;
        }
        if(!$num = count($result)){
            $result = null;
        }

        return response()->json([
            'status' => $status,    // 정상 작동 유무
            'type' => $type,        // 게시판 종류
            'term' => $term,        // 검색한 키워드
            'result' => $result,    // 결과
            'num' => $num,           // 
        ]);
    }
}
