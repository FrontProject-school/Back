<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Http\Controllers\Controller;
use App\Http\Logics\ImageLogic;
use App\Models\RecruitDepart;
use App\Models\RecruitLang;
use App\Models\RecruitQeustion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    // request 배열로 반환
    public function getRequestData($req)
    {
        $data = [
            'category' => $req->category,
            'title' => $req->title,
            'grade' => $req->grade,
            'depart' => $req->depart,
            'lang' => $req->lang,
            'question' => $req->question,
            'selectNum'  => $req->selectNum,
            'rStart' => $req->rStart,
            'rEnd' => $req->rEnd,
            'actStart' => $req->actStart,
            'actEnd' => $req->actEnd
        ];

        return $data;
    }

    // 정보 처리
    public function infoProcess($data, $program)
    {
        $program->title = $data['title'];
        $program->selectNum = $data['selectNum'];
        $program->actStart = $data['actStart'];
        $program->actEnd = $data['actEnd'];

        

        // isset() : $data 안에 grade라는 key가 있는지 확인
        // is_array() : $data['grade']의 값이 배열인지 확인
        if (isset($data['grade']) && is_array($data['grade'])) {
            $program->grade = implode($data['grade']);
        } else {
            $program->grade = $data['grade'];
        }

        // Carbon : 날짜와 시간을 다루는 라이브러리
        $rStart = Carbon::createFromFormat('Y-m-d H:i', $data['rStart']);
        $rEnd = Carbon::createFromFormat('Y-m-d H:i', $data['rEnd']);
        $rStart->second(00);
        $rEnd->second(59);

        $program->rStart = $rStart;
        $program->rEnd = $rEnd;
        $flag = $program->save();

        if ($flag) {
            // 통일화
            foreach ($data['depart'] as $value) {
                $departs = new RecruitDepart;
                $departs->pId = $program->pId;
                $departs->depart = $value;
                $departs->save();
            }


            foreach ($data['lang'] as $value) {
                $langs = new RecruitLang;
                $langs->pId = $program->pId;
                $langs->lang = $value;
                $langs->save();
            }

            foreach ($data['question'] as $index => $value) {
                $recQues = new RecruitQeustion();
                $recQues->pId = $program->pId;
                $recQues->qNumber = $index+1;
                $recQues->question = $value;
                $recQues->save();
            }

        } else {
            return response()->json(
                [
                    'data' => $data,
                    'status'  => false,
                    'msg' => '학과,언어 등록실패'
                ],
                500
            );
        }
    }

    // 정보 확인 공통
    public function showInfo($item, $pId)
    {
        $departs = RecruitDepart::where('pId', $pId)->get();
        $langs = RecruitLang::where('pId', $pId)->get();
        $recQues = RecruitQeustion::where('pId', $pId)->get();

        $info = [
            'pId' => $item->pId,
            'category' => $item->category,
            'title' => $item->title,
            'grade' => $item->grade,
            'rStart' => $item->rStart,
            'rEnd' => $item->rEnd,
            'actStart' => $item->actStart,
            'actEnd' => $item->actEnd,
            'depart' => $departs,
            'lang' => $langs,
            'question' => $recQues
        ];

        return $info;
    }

    // 프로그램 목록
    public function index()
    {
        $programs = Program::all();

        $programList = [];

        $programs->each(function ($item) use (&$programList) {

            $info = $this->showInfo($item, $item->pId);

            $imageClass = new ImageLogic;
            $images = $imageClass->showImgs($item->pId, 'program');

            $program = [
                'info' => $info,
                'images' => $images
            ];

            array_push($programList, $program);
        });

        return response()->json(
            [
                'programList' => $programList, // 확인용임, 전달값이 있어야 할까?
                'status'  => true,
                'msg' => '불러오기'
            ],
            200
        );
    }

    // 프로그램 등록
    public function store(Request $req)
    {
        $program = new Program;

        $data = $this->getRequestData($req); // req를 배열로 반환

        $program->category = $data['category'];
        $type = $program->category == '한일교류' ? 'A' : 'B';

        $pIdList = Program::pluck('pId');
        $uList = [];
        $uuid = (string)Str::uuid();

        var_dump($pIdList);

        if(!($pIdList->isEmpty())){
            // $pIdList->each(function ($item) use (&$uList) {
            //     $result = explode('-', $item, 3);
            //     array_push($uList, $result[2]);
            // });

            foreach ($pIdList as $item) {
                $result = explode('-', $item, 3);
                array_push($uList, $result[2]);
            }
    
            // 중복 확인
            while (in_array($uuid, $uList)) {
                $uuid = (string)Str::uuid();
            }
        }

        $program->pId = date('y') . '-' . $type . '-' . $uuid;

        $this->infoProcess($data, $program);

        // 이미지 전달 확인
        if ($req->hasFile('images')) {
            echo "이미지 부분";
            $imgList = $req->file('images');
            $imageClass = new ImageLogic;
            $imageClass->insertImgs($imgList, $program->pId, 'program');
        }

        return response()->json(
            [
                'data' => $data, // 확인용임, 전달값이 있어야 할까?
                'status'  => true,
                'msg' => '등록완료'
            ],
            200
        );
    }

    // 프로그램 상세 정보
    public function programInfo(string $pId)
    {
        $program = Program::where('pId', $pId)->first(); // 없으면 null

        $info = $this->showInfo($program, $pId);

        $imageClass = new ImageLogic;
        $images = $imageClass->showImgs($pId, 'program');

        $programInfo = [
            'info' => $info,
            'images' => $images
        ];

        if (!$info) {
            return response()->json([
                'err' => '해당하는 번호 없음',
            ], 400);
        }

        return response()->json(
            [
                'programInfo' => $programInfo,
                'msg' => '불러오기 성공',
            ],
            200
        );
    }

    // 프로그램 정보 수정
    public function update(Request $req, string $pId)
    {
        $data = $this->getRequestData($req);

        $program = Program::find($pId);
        RecruitDepart::where('pId', $pId)->delete();
        RecruitLang::where('pId', $pId)->delete();
        RecruitQeustion::where('pId', $pId)->delete();

        $imageClass = new ImageLogic;
        $imageClass->deleteImgs($pId, 'program');

        if ($program) {
            // $program->update($req->all());

            $this->infoProcess($data, $program);

            if ($req->hasFile('images')) { // 이미지 전달 확인
                $imgList = $req->file('images');
                $imageClass->insertImgs($imgList, $program->pId, 'program');
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

    // 프로그램 삭제
    public function destroy(string $pId)
    {
        $confirm = Program::where('pId', $pId)->delete();

        $imageClass = new ImageLogic;
        $imageClass->deleteImgs($pId, 'program');

        $result = [
            'status'  => true,
            'msg' => '정상 삭제 완료'
        ];
        $status = 200;

        if ($confirm == 0) {
            $result = [
                'status'  => true,
                'msg' => '삭제 실패'
            ];
            $status = 500;
        }

        return response()->json(
            $result,
            $status
        );
    }
}
