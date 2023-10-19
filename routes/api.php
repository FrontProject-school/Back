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

// 회원가입 / 로그인
Route::post('/regist', [RegisterController::class,'regist']);
Route::post('/login', [LoginController::class, 'login']);

// 로그인 확인용 미들웨어 (유저, 관리자)
Route::group(['middleware' => ['auth:sanctum']], function () {

    // 알림
    Route::apiResource('/notify', NotifyController::class)->except([
        'create', 'show', 'edit'
    ]);

    // 관리자, 유저 로그아웃 통합 
    Route::post('/logout', [LoginController::class, 'logout']);

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

    // 관리자 (등록 / 삭제), 총관리자 변경
    
    Route::apiResource('/admin', AdminController::class)->except([
        'create','edit'
    ]);
    

    // 관리자 권한 확인용 미들웨어
    Route::middleware(['role:admin'])->group(function() {
        // 여기에 관리자용 api 담아주시면 됩니다..
    });
});

// 프로그램
Route::apiResource('program', ProgramController::class)->except([
    'create', 'show', 'edit'
]);
Route::post('program/programInfo/{pId}', [ProgramController::class, 'programInfo']);

// 지원하기
Route::apiResource('applicant', ApplicantController::class)->except([
    'create', 'show', 'edit'
]);
Route::post('applicant/myApplicants/{stuId}', [ApplicantController::class, 'myApplicants']);
Route::post('applicant/details', [ApplicantController::class, 'details']);
Route::post('applicant/applicantSelection', [ApplicantController::class, 'applicantSelection']);

// 게시판
// Route::get('/freeboards', [FreeboardController::class, 'index']);
// Route::post('/freeboards', [FreeboardController::class, 'store']);
// Route::get('/freeboards/{num}', [FreeboardController::class, 'show']);
// Route::put('/freeboards/{num}', [FreeboardController::class, 'update']);
// Route::delete('/freeboards/{num}', [FreeboardController::class, 'destroy']);
Route::apiResource('freeboards', FreeboardController::class);

// 공지사항
// Route::get('/notices', [NoticeController::class, 'index']);
// Route::post('/notices', [NoticeController::class, 'store']);
// Route::get('/notices/{num}', [NoticeController::class, 'show']);
// Route::put('/notices/{num}', [NoticeController::class, 'update']);
// Route::delete('/notices/{num}', [NoticeController::class, 'destroy']);
Route::apiResource('notices', NoticeController::class);

// 문의글
Route::apiResource('questions', QuestionController::class);

// 검색
Route::post('/search/{boardType}', [SearchController::class,'search']);
