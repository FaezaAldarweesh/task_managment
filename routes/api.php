<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaskController;

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

    Route::apiResource('task',TaskController::class);
    Route::get('restore_task/{task_id}', [TaskController::class, 'restore']);
    Route::delete('forceDelete_task/{task_id}', [TaskController::class, 'forceDelete']);

 
});




