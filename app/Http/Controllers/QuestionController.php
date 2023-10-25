<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\question;
use Illuminate\Http\Request;

class QuestionController extends Controller{
    private $question;

    public function __construct(Question $question) {
        $this->question = $question;
    }

    public function index(){
        $questions = Question::all();

        return response()->json(
            [
                'status' => true,
                'questions' => $questions
            ],
            200
        );
    }
    public function create(){
        //
    }

    public function store(Request $request){
        $question = new Question;
        $question->id = count(Question::all()) + 1;
        $question->studId = $request->studId;
        $question->title = $request->title;
        $question->content = $request->content;
        $question->secret = $request->secret;
        $question->answer = $request->answer;

        $question->save();

        return response()->json(
            [
                'status'  => true,
                'msg' => '문의글 작성 완료'
            ],
            200
        );

    }
    // 상세 페이지
    public function show(Question $question){
        // show 에 경우는 해당 페이지의 모델 값이 파라미터로 넘어옴
        $question = Question::where('id', '=', $question->id)->first();

        if (!$question) {
            return response()->json([
                'err'=>'현재 존재하지 않는 문의글 입니다.'
            ],
            404);
        }

        return response()->json([
            'question'=> $question
        ],
        200);
    }

    public function edit(Question $question){
        //
    }

    public function update(Request $request, Question $question){
        $question = Question::where('id', '=', $question->id)->first();
        // return response()->json([
            // "ques" => $question->answer,
            // "req"=>$request->answer
        //     "sta"=>($question->answer != $request->answer)
        // ]);
        if ($question->answer != $request->answer) {

            // dd($question->answer);

            $question->update([$request->answer]);

            return response()->json([
                'status' => true,
                'msg' => '문의글에 답변을 완료했습니다.'
            ], 200);
        }

        if (!$question) {
            return response()->json([
                'err' => '문의글이 존재하지 않습니다.'
            ], 404);
        }

        $question->update($request->all());

        return response()->json([
            'status' => true,
            'msg' => '문의글이 수정되었습니다.'
        ], 200);

    }

    public function destroy(Question $question){
        $question = Question::where('id', '=', $question->id)->first();

        if (!$question) {
            return response()->json([
                'err' => '문의글이 존재하지 않습니다.'
            ], 404);
        }

        $question->delete();

        return response()->json([
            'status' => true,
            'msg' => '문의글이 삭제되었습니다.'
        ], 200);
    }
}
