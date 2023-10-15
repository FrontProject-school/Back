<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\RecruitDepart;
use App\Models\RecruitLang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{

    // 정보 처리
    public function infoProcess($data, $program){
        $program->title = $data['title'];
        $program->selectNum = $data['selectNum'];
        $program->actStart = $data['actStart'];
        $program->actEnd = $data['actEnd'];
        
        // isset() : $data 안에 grade라는 key가 있는지 확인
        // is_array() : $data['grade']의 값이 배열인지 확인
        if (isset($data['grade']) && is_array($data['grade'])) { 
            $program->grade = implode($data['grade']);
        } else{
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

        if($flag){
            foreach ($data['depart'] as $value) {
                $departs = new RecruitDepart;
                $departs->program = $program->pId;
                $departs->depart = $value;
                $departs->save();
            }
            
            foreach ($data['lang'] as $value) {
                $langs = new RecruitLang;
                $langs->program = $program->pId;
                $langs->lang = $value;
                $langs->save();
            }
        } else{
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
    public function showInfo($item, $pId){
        $departs = RecruitDepart::where('program', '=', $pId)->get();
        $langs = RecruitLang::where('program', '=', $pId)->get();

        $info = [
            'pId' => $item->pId,
            'category' => $item->category,
            'title' => $item->title,
            'grade' => $item->grade,
            'rStart' => $item->rStart,
            'rEnd' => $item-> rEnd,
            'actStart' => $item->actStart,
            'actEnd' => $item->actEnd,
            'depart' => $departs,
            'lang' => $langs
        ];

        return $info;
    }

    // 프로그램 목록
    public function index()
    {
        $programs = Program::all();

        $infoList = [];

        $programs->each(function ($item) use (&$infoList){

            $info = $this->showInfo($item, $item->pId);

            array_push($infoList, $info);
        });

        return response()->json(
            [
                'status'  => true,
                'infoList' => $infoList
            ],
            200
        );
    }

    // 프로그램 등록
    public function store(Request $req)
    {

        // $data = $req->all(); // 라라벨에서 자동으로 배열형태로 바꿔준다

        $data = json_decode($req->input('json'), true);

        $imgList = $req->file('images');

        $program = new Program;

        $program->category = $data['category'];
        $type = $program->category == '한일교류' ? 'A' : 'B';

        $pIdList = Program::pluck('pId');
        $uList = [];
        $uuid = (string)Str::uuid();
        $pIdList->each(function ($item) use (&$uList) {
            $result = explode('-', $item, 3);
            array_push($uList, $result[2]);
        });
        
        // 중복 확인
        while(in_array($uuid, $uList)){
            $uuid = (string)Str::uuid();
        }

        $program->pId = date('y') . '-' . $type . '-' . $uuid;

        $this->infoProcess($data, $program);

        if($req->hasFile('images')){ // 이미지 전달 확인
            $imageClass = new ImageController;
            $imageClass->insertImgs($imgList, $program->pId, 'program');
        }

        return response()->json(
            [
                'data' => $data,
                'status'  => true,
                'msg' => '등록완료'
            ],
            200
        );
    }

    // 프로그램 상세 정보
    public function programInfo(string $pId)
    {
        $program = Program::where('pId', '=', $pId)->first(); // 없으면 null
        
        $info = $this->showInfo($program, $pId);

        $imageClass = new ImageController;
        $images = $imageClass->showImgs($pId, 'program');

        if (!$info) {
            return response()->json([
                'info' => $info,
                'images' => $images,
                'err' => '해당하는 번호 없음',
            ], 400);
        }

        return response()->json(
            [
                'info' => $info,
                'images' => $images,
                'msg' => '불러오기 성공',
            ],
            200
        );
    }

    // 프로그램 정보 수정
    public function update(Request $req, string $pId)
    {

        // $data = $req->all();
        $data = json_decode($req->input('json'), true);

        $imgList = $req->file('images');

        $program = Program::find($pId);
        RecruitDepart::where('program', '=', $pId)->delete();
        RecruitLang::where('program', '=', $pId)->delete();

        $imageClass = new ImageController;
        $imageClass->deleteImgs($pId, 'program');

        if ($program) {
            // $program->update($req->all());

            $this->infoProcess($data, $program);

            if($req->hasFile('images')){ // 이미지 전달 확인
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
        $cofirm = Program::where('pId', '=', $pId)->delete();

        $imageClass = new ImageController;
        $imageClass->deleteImgs($pId, 'program');

        $result = [
            'status'  => true,
            'msg' => '정상 삭제 완료'
        ];
        $status = 200;

        if($cofirm == 0){
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

