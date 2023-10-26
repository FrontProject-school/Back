<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Freeboard;
use App\Http\Logics\ImageLogic;
use App\Models\Comment;


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

        $freeboard->studId = $request->studId;
        $freeboard->title = $request->title;
        $freeboard->content = $request->content;
        
        $freeboard->save();

        if ($request->hasFile('images')) {
            $imgList = $request->file('images');
            $imageClass = new ImageLogic;
            $imageClass->insertImgs($imgList, (String)$freeboard->id, 'freeboard');
        }

        return response()->json(
            [
                'status'  => true,
                'msg' => '게시글 작성 완료'
            ],
            200
        );
    }

    public function show($id) {
        $freeboard = Freeboard::where('id', $id)->first();

        $imageClass = new ImageLogic;
        $images = $imageClass->showImgs($id, 'freeboard');
        $comment = Comment::where([['category','freeboard'],['uid',$id]])->get();

        if (!$freeboard) {
            return response()->json([
                'err'=>'현재 존재하지 않는 게시글 입니다.'
            ],
            404);
        }

        return response()->json([
            'freeboard'=>$freeboard,
            'images'=>$images
        ],
        200);
    }

    public function edit() {
        //
    }

    public function update(Request $request, $id) {
        $freeboard = Freeboard::where('id', $id)->first();

        if (!$freeboard) {
            return response()->json([
                'err' => '게시글이 존재하지 않습니다.'
            ], 404);
        }

        $imageClass = new ImageLogic;
        $imageClass->deleteImgs($id, 'freeboard');

        if ($request->hasFile('images')) {
            $imgList = $request->file('images');
            $imageClass->insertImgs($imgList, $freeboard->Id, 'freeboard');
        }

        $freeboard->update($request->all());

        return response()->json([
            'status' => true,
            'msg' => '게시글이 수정되었습니다.'
        ], 200);
    }

    public function destroy($id) {
        $freeboard = Freeboard::where('id', $id)->first();

        $imageClass = new ImageLogic;
        $imageClass->deleteImgs($id, 'freeboard');

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
