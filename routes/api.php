<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

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

// 로그 아웃 ( 미들웨어 : 로그인 유무 )
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [LoginController::class, 'logout']);
});

// 회원 정보 수정
Route::put('/user/update/{id}', [UserController::class, 'update']);


// 회원 정보 삭제
Route::delete('/user/delete/{id}', [UserController::class, 'delete']);




