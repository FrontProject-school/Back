<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ApplicantsController extends Controller
{
    // 전체 지원목록 확인
    public function index()
    {
        $applicants = Applicant::all();

        return response()->json(
            [
                'status'  => true,
                'applicants' => $applicants
            ],
            200
        );
    }

    // 지원 입력폼
    public function create()
    {
        //
    }

    // 지원 등록
    public function store(Request $req)
    {
        
        $applicant = new Applicant;

        $applicant->stuId = $req->stuId;
        $applicant->program = $req->program;
        $applicant->answer = $req->answer;

        $stuIdCheck = count(DB::table('users')->where('stuId', '=', $applicant->stuId)->get()) == 0 ? true : false;
        $programCheck = count(DB::table('programs')->where('num', '=', $applicant->program)->get()) == 0 ? true : false;
        if($stuIdCheck){
            return response()->json(
                [
                    'status'  => false,
                    'msg' => '해당 학생정보 없음'
                ],
                500
            );
        }

        if($programCheck){
            return response()->json(
                [
                    'status'  => false,
                    'msg' => '해당 프로그램 정보 없음'
                ],
                500
            );
        }


        $applicant->save();
        
        return response()->json(
            [
                'status'  => true,
                'msg' => '등록완료'
            ],
            200
        );
    }

    // 학생 지원목록 확인
    public function show(string $stuId)
    {
        $info = Applicant::where('stuId', '=', $stuId)->get(); // 없으면 빈 배열

        return response()->json(
            [
                'status'  => true,
                'info' => $info
            ],
            200
        );
    }

    // 학생 단일 프로그램 지원 정보 확인
    public function details(Request $req)
    {
        $result = Applicant::where('num', '=', $req->num)->get();

        return response()->json(
            [
                'status'  => true,
                'info' => $result
            ],
            200
        );
    }

    // 지원 수정 폼
    public function edit(Applicant $applicant)
    {
        //
    }

    // 지원 수정
    public function update(Request $req, string $num)
    {
        $result = Applicant::where('num', '=', $num)->first();

        if($result){
            $result->update($req->all());

            return response()->json(
                [
                    'msg' => '수정완료',
                ], 200
            );
            
        } else{
            return response()->json(
                [
                    'err' => '번호가 일치하지 않음',
                ], 500
            );
        }
    }

    // 지원 삭제
    public function destroy(string $num)
    {
        Applicant::where('num', '=', $num)->delete();
        return response()->json(
            [
                'status'  => true,
                'msg' => '정상 삭제 완료'
            ],
            200
        );
    }
}
