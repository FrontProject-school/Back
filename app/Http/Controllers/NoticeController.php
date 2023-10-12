<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\notice;

class NoticeController extends Controller
{
    private $notice;

    public function __construct(notice $notice) {
        $this->notice = $notice;
    }

    public function index() {
        $notices = Notice::all();

        return response()->json(
            [
                'status' => true,
                'notices' => $notices
            ],
            200
        );
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        $notice = new Notice;

        $notice->num = count(Notice::all()) + 1;
        $notice->adminNum = $request->adminNum;
        $notice->title = $request->title;
        $notice->content = $request->content;
        $notice->confirm = $request->confirm;

        $notice->save();

        return response()->json(
            [
                'status'  => true,
                'msg' => '공지사항 작성 완료'
            ],
            200
        );
    }

    public function show($num) {
        $content = Notice::where('num', '=', $num)->first();

        if (!$content) {
            return response()->json([
                'err'=>'현재 존재하지 않는 공지사항입니다.'
            ],
            404);
        }

        return response()->json([
            'content'=>$content
        ],
        200);
    }

    public function edit() {
        //
    }

    public function update(Request $request, $num) {
        $notice = Notice::where('num','=',$num)->first();

        if(!$notice) {
            return response()->json([
                'err' => '존재하지 않는 공지사항입니다.'
            ], 404);
        }

        // 요청에서 받은 데이터로 게시글 업데이트
        $notice->adminNum = $request->adminNum;
        $notice->title = $request->title;
        $notice->content = $request->content;
        $notice->confirm = $request->confirm;

        // $notice->update($request->all()); // 하단 코드 대체용
        $notice->save();

        return response()->json([
            'status' => true,
            'msg' => '공지사항이 수정되었습니다.'
        ], 200);
    }
    public function destroy($num) {
        $notice = Notice::where('num', '=', $num)->first();

        if (!$notice) {
            return response()->json([
                'err' => '존재하지 않는 공지사항입니다.'
            ], 404);
        }

        $notice->delete();

        return response()->json([
            'status' => true,
            'msg' => '공지사항이 삭제되었습니다.'
        ], 200);
    }
}
