<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicantController extends Controller
{
    // // 관리자 학생 선발
    public function applicantSelection(Request $req)
    {
        $checkedStdList = $req->all();

        var_dump($checkedStdList);

        foreach ($checkedStdList as $item) {
            Applicant::where([
                ['stuId', $item->stuId],
                ['program', $item->program]
            ])->update(['selected' => 'T']);
        }

        $applicants = Applicant::all();

        return response()->json(
            [
                '$applicants' => $applicants
            ],
            200
        );
    }


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

    // 지원 등록
    public function store(Request $req)
    {

        $applicant = new Applicant;

        $applicant->stuId = $req->stuId;
        $applicant->program = $req->program;
        $applicant->answer = $req->answer;

        $stuIdCheck = count(DB::table('users')->where('stuId', '=', $applicant->stuId)->get()) == 0 ? true : false;

        $programCheck = count(DB::table('programs')->where('pId', '=', $applicant->program)->get()) == 0 ? true : false;
        if ($stuIdCheck) {
            return response()->json(
                [
                    'status'  => false,
                    'msg' => '해당 학생정보 없음'
                ],
                500
            );
        }

        if ($programCheck) {
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

    // 학생별 지원 목록
    public function myApplicants(string $stuId)
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

    // 학생별 단일 프로그램 지원 정보 확인
    public function details(Request $req)
    {
        $data = json_decode($req->getContent());

        $result = Applicant::where([
            ['stuId', '=', $data->stuId],
            ['program', '=', $data->program]
        ])->get();

        return response()->json(
            [
                'status'  => true,
                'info' => $result
            ],
            200
        );
    }

    // 지원 수정
    public function update(Request $req, string $id)
    {
        $result = Applicant::where('id', '=', $id)->first();

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

    // 지원 삭제
    public function destroy(string $id)
    {
        Applicant::where('id', '=', $id)->delete();
        return response()->json(
            [
                'status'  => true,
                'msg' => '정상 삭제 완료'
            ],
            200
        );
    }
}
