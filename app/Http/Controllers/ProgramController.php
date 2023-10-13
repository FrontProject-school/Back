<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    // 프로그램 목록
    public function index()
    {
        $programs = Program::all();

        return response()->json(
            [
                'status'  => true,
                'programs' => $programs
            ],
            200
        );
    }

    // 프로그램 등록 폼
    public function create()
    {
        //
    }

    // 프로그램 등록
    public function store(Request $req)
    {

        // json형식으로 받기로 수정 필요

        $program = new Program;

        $program->category = $req->category;
        $program->title = $req->title;
        $program->selectNum = $req->selectNum;
        $program->rStart = $req->rStart;
        $program->rEnd = $req->rEnd;
        $program->actStart = $req->actStart;
        $program->actEnd = $req->actEnd;

        $type = $program->category == '한일교류' ? 'A' : 'B';

        $program->pId = date('y') . '-' . $type . (count(Program::all()) + 1);

        $program->save();

        



        return response()->json(
            [
                'status'  => true,
                'msg' => '등록완료'
            ],
            200
        );
    }

    // 프로그램 상세 정보
    public function show(string $pId)
    {
        $info = Program::where('pId', '=', $pId)->first(); // 없으면 null

        if (!$info) {
            return response()->json([
                'info' => $info,
                'err' => '해당하는 번호 없음',
            ], 400);
        }

        return response()->json(
            [
                'info' => $info,
                'msg' => '불러오기 성공',
            ],
            200
        );
    }

    // 프로그램 정보수정 폼
    public function edit(Program $program)
    {
        //
    }

    // 프로그램 정보 수정
    public function update(Request $req, string $pId)
    {

        $result = Program::find($pId);

        if ($result) {
            $result->update($req->all());

            return response()->json(
                [
                    'msg' => '수정완료',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'err' => '번호가 일치하지 않음',
                ],
                500
            );
        }
    }

    // 프로그램 삭제
    public function destroy(Program $program)
    {
        $program->where('pId', '=', $program)->delete();
        return response()->json(
            [
                'status'  => true,
                'msg' => '정상 삭제 완료'
            ],
            200
        );
    }
}
