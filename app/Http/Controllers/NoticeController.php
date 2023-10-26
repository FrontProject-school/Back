<?php

namespace App\Http\Controllers;

use App\Http\Logics\ImageLogic;
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

        if ($request->hasFile('images')) {
            $imgList = $request->file('images');
            $imageClass = new ImageLogic;
            $imageClass->insertImgs($imgList, (String)$notice->id, 'notice');
        }

        return response()->json(
            [
                'status'  => true,
                'msg' => '공지사항 작성 완료'
            ],
            200
        );
    }

    public function show($id) {
        $notice = Notice::where('id', $id)->first();

        $imageClass = new ImageLogic;
        $images = $imageClass->showImgs($id, 'notice');

        if (!$notice) {
            return response()->json([
                'err'=>'현재 존재하지 않는 공지사항입니다.'
            ],
            404);
        }

        return response()->json([
            'notice'=>$notice,
            'images'=>$images
        ],
        200);
    }

    public function edit() {
        //
    }

    public function update(Request $request, $id) {
        $notice = Notice::where('id', $id)->first();

        if(!$notice) {
            return response()->json([
                'err' => '존재하지 않는 공지사항입니다.'
            ], 404);
        }

        // 요청에서 받은 데이터로 게시글 업데이트
        // $notice->adminNum = $request->adminNum;
        // $notice->title = $request->title;
        // $notice->content = $request->content;
        // $notice->confirm = $request->confirm;

        $imageClass = new ImageLogic;
        $imageClass->deleteImgs($id, 'notice');

        if ($request->hasFile('images')) {
            $imgList = $request->file('images');
            $imageClass->insertImgs($imgList, $notice->id, 'notice');
        }

        $notice->update($request->all());
        //$notice->save();

        return response()->json([
            'status' => true,
            'msg' => '공지사항이 수정되었습니다.'
        ], 200);
    }
    public function destroy($id) {
        $notice = Notice::where('id', $id)->first();

        $imageClass = new ImageLogic;
        $imageClass->deleteImgs($id, 'notice');

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
