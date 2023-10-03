<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ProgramsController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


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
});

// 프로그램
Route::apiResource('programs', ProgramsController::class);

// 지원하기
Route::apiResource('applicants', ApplicantsController::class);
Route::post('applicants/details', [ApplicantsController::class, 'details']);