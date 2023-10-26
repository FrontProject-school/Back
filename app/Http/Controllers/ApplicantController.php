<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ApplicantAnswer;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicantController extends Controller {



    // // 관리자 학생 선발
    public function applicantSelection(Request $req)
    {
        $checkedStdList = $req->all();

        foreach ($checkedStdList as $item) {
            Applicant::where([
                ['studId', '=', $item->studId],
                ['pId', '=', $item->pId]
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

        $studIdCheck = count(User::where('studId', $req->studId)->get()) == 0 ? true : false;

        $programCheck = count(Program::where('pId', $req->pId)->get()) == 0 ? true : false;

        if ($studIdCheck) {
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

        
        $applicant->studId = $req->studId;
        $applicant->pId = $req->pId;
        $applicant->selected = 'F';
        
        // 답변 등록
        foreach ($req->answer as $index => $value) {
            $applicantAnswer = new ApplicantAnswer;
            $applicantAnswer->pId = $req->pId;
            $applicantAnswer->studId = $req->studId;
            $applicantAnswer->aNumberr = $index+1;
            $applicantAnswer->answer = $value;
            $applicantAnswer->save();
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
    public function myApplicants(string $studId)
    {
        $info = Applicant::where('studId', $studId)->get(); // 없으면 빈 배열

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

        $info = Applicant::where([
            ['studId', '=', $data->studId],
            ['pId', '=', $data->pId]
        ])->get();

        $answer = ApplicantAnswer::where([
            ['studId', '=', $data->studId],
            ['pId', '=', $data->pId]
        ])->get();

        $result = [
            'info' => $info,
            'answer' => $answer
        ];

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
        $result = Applicant::where($id)->first();


        if ($result) {
            $result->update($req->all());
            
            ApplicantAnswer::where([
                ['studId', '=', $req->studId],
                ['pId', '=', $req->pId]
            ])->delete();

            foreach ($req->answer as $index => $value) {
                $applicantAnswer = new ApplicantAnswer;
                $applicantAnswer->pId = $req->pId;
                $applicantAnswer->studId = $req->studId;
                $applicantAnswer->aNumberr = $index+1;
                $applicantAnswer->answer = $value;
                $applicantAnswer->save();
            }

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
        $applicant = Applicant::where($id)->first();

        ApplicantAnswer::where([
            ['studId', '=', $applicant->studId],
            ['pId', '=', $applicant->pId]
        ])->delete();

        Applicant::where($id)->delete();
        return response()->json(
            [
                'status'  => true,
                'msg' => '정상 삭제 완료'
            ],
            200
        );
    }
}
