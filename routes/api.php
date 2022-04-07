<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>'api'],function($router){
    Route::post('/register', [\App\Http\Controllers\AuthController::class,'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class,'login']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class,'logout']);

    Route::get('/equipments', [\App\Http\Controllers\EquipmentController::class, 'index']);
    Route::post('/equipments', [\App\Http\Controllers\EquipmentController::class, 'create']);
    Route::get('equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'viewOrUpdate']);
    Route::put('equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'viewOrUpdate']);
    Route::delete('equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'destroy']);

    Route::get('/find-equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'guestView']);
    Route::get('/find-equipments/', [\App\Http\Controllers\EquipmentController::class, 'guestView']);

});
