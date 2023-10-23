<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Freeboard;
use App\Http\Logics\ImageLogic;


class FreeboardController extends Controller
{
    private $freeboard;

    public function __construct(Freeboard $freeboard) {
        $this->freeboard = $freeboard;
    }

    public function index() {
        $freeboards = Freeboard::all();

        return response()->json(
            [
                'status' => true,
                'freeboards' => $freeboards
            ],
            200
        );
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        $freeboard = new Freeboard;

        $freeboard->num = count(Freeboard::all()) + 1;
        $freeboard->stdId = $request->stdId;
        $freeboard->imageNum = $request->imageNum;
        $freeboard->title = $request->title;
        $freeboard->content = $request->content;

        if ($request->hasFile('images')) {
            $imgList = $request->file('images');
            $imageClass = new ImageLogic;
            $imageClass->insertImgs($imgList, $freeboard->Id, 'freeboard');
        }

        $freeboard->save();

        return response()->json(
            [
                'status'  => true,
                'msg' => '게시글 작성 완료'
            ],
            200
        );
    }

    public function show($num) {
        $content = Freeboard::where('num', '=', $num)->first();

        if (!$content) {
            return response()->json([
                'err'=>'현재 존재하지 않는 게시글 입니다.'
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
        $freeboard = Freeboard::where('num', '=', $num)->first();

        if (!$freeboard) {
            return response()->json([
                'err' => '게시글이 존재하지 않습니다.'
            ], 404);
        }

        // 요청에서 받은 데이터로 게시글 업데이트
        $freeboard->stdId = $request->stdId;
        $freeboard->title = $request->title;
        $freeboard->content = $request->content;

        $freeboard->save();

        return response()->json([
            'status' => true,
            'msg' => '게시글이 수정되었습니다.'
        ], 200);
    }
    public function destroy($num) {
        $freeboard = Freeboard::where('num', '=', $num)->first();

        if (!$freeboard) {
            return response()->json([
                'err' => '게시글이 존재하지 않습니다.'
            ], 404);
        }

        $freeboard->delete();

        return response()->json([
            'status' => true,
            'msg' => '게시글이 삭제되었습니다.'
        ], 200);
    }
}
