<?php

use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AdminRatingController;
use App\Http\Controllers\user\UserRatingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\user\UserBorrowRecordController;
use App\Http\Controllers\Admin\AdminBorrowRecordController;

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

//لدينا فقط راوت لتسجيل الدوخل لأن النظام هو مظام عمل موظفين , يقوم الأدمن بإنشاء حسابات الموظفين
Route::post('login', [AuthController::class , 'login']);  

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout',[AuthController::class ,'logout']); 

    //only for admin
    Route::apiResource('user',UserController::class); 
    Route::post('create_manager',[UserController::class ,'create_manager']); 
    Route::get('update_password/{user_id}',[UserController::class ,'update_password']); 
    Route::get('restore_user/{user_id}', [UserController::class, 'restore']);
    Route::delete('forceDelete_user/{user_id}', [UserController::class, 'forceDelete']);
 
});




