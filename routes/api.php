<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// controller 
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\FreeboardController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\QuestionController;
use App\Models\Freeboard;
use App\Http\Controllers\SearchController;
use PHPUnit\Framework\TestStatus\Notice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// cors 방지 미들웨어
Route::middleware('cors')->group(function(){
    // 회원가입 / 로그인
Route::post('/regist', [RegisterController::class,'regist']);
Route::post('/login', [LoginController::class, 'login']);


// 토큰(로그인) 필요 미들웨어 (유저, 관리자)
// 넣어주세요...
Route::group(['middleware' => ['auth:sanctum']], function () {
    // 알림
    Route::apiResource('/notify', NotifyController::class)->except([
        'create', 'show', 'edit'
    ]);

    // 회원 정보 (받기 / 수정 / 삭제)
    Route::prefix('user')->group(function () {
        // 받기
        Route::get('/info', [UserController::class, 'getInfo']);
        // 수정
        Route::put('/update/{id}', [UserController::class, 'update']);
        // 삭제
        Route::delete('/delete/{id}', [UserController::class, 'delete']);
        // 리스트 받기
        Route::get('/list',[UserController::class, 'getUserList'])
        ->middleware('role:admin');
    });

    // 관리자, 유저 로그아웃 통합 
    Route::post('/logout', [LoginController::class, 'logout']);

    // 게시판
    Route::apiResource('freeboards', FreeboardController::class)
        ->except(['index','show']);


    // 관리자 권한 확인용 미들웨어
    Route::middleware(['role:admin'])->group(function() {
        // 여기에 관리자용 api 담아주시면 됩니다..
        
        // 관리자 (등록 / 삭제), 총관리자 변경
        Route::apiResource('/admin', AdminController::class)
            ->except(['create','edit']);

        // 프로그램 (토큰 o 작업)
        Route::apiResource('program', ProgramController::class)->except([
            'create', 'show', 'edit','index','programInfo'
        ]);
    });
});

// 프로그램 (토큰 x 작업)
Route::post('program/programInfo/{pId}', [ProgramController::class, 'programInfo']);
Route::apiResource('program', ProgramController::class)->only('index');

// 지원하기
Route::apiResource('applicant', ApplicantController::class)->except([
    'create', 'show', 'edit'
]);
Route::post('applicant/myApplicants/{stuId}', [ApplicantController::class, 'myApplicants']);
Route::post('applicant/details', [ApplicantController::class, 'details']);
Route::post('applicant/applicantSelection', [ApplicantController::class, 'applicantSelection']);

// 게시판
Route::apiResource('freeboards', FreeboardController::class)
    ->only(['index','show']);

// 공지사항
Route::apiResource('notices', NoticeController::class);

// 문의글
Route::apiResource('questions', QuestionController::class);

// 검색
Route::post('/search/{boardType}', [SearchController::class,'search']);

});
