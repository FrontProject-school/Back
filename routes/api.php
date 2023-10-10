<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ProgramsController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
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
    });

    // 관리자 (등록 / 삭제), 총관리자 변경
    Route::prefix('admin')->group(function () {
        Route::apiResource('/panel', AdminController::class);
    });

    // 관리자 권한 확인용 미들웨어
    Route::middleware(['role:admin'])->group(function() {
        // 여기에 관리자용 api 담아주시면 됩니다..
    });
});

// 프로그램
Route::apiResource('programs', ProgramsController::class);

// 지원하기
Route::apiResource('applicants', ApplicantsController::class);
Route::post('applicants/details', [ApplicantsController::class, 'details']);

// 게시판
Route::get('/freeboards', [FreeboardController::class, 'index']);
Route::post('/freeboards', [FreeboardController::class, 'store']);
Route::get('/freeboards/{num}', [FreeboardController::class, 'show']);
Route::put('/freeboards/{num}', [FreeboardController::class, 'update']);
Route::delete('/freeboards/{num}', [FreeboardController::class, 'destroy']);

// 공지사항
Route::get('/notices', [NoticeController::class, 'index']);
Route::post('/notices', [NoticeController::class, 'store']);
Route::get('/notices/{num}', [NoticeController::class, 'show']);
Route::put('/notices/{num}', [NoticeController::class, 'update']);
Route::delete('/notices/{num}', [NoticeController::class, 'destroy']);
