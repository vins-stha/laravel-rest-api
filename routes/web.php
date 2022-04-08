<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/api/v1/equipments', [\App\Http\Controllers\EquipmentController::class, 'create']);

//Route::post('/register',  [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/login',  [\App\Http\Controllers\UserController::class, 'login']);

Route::get('/api/v1/equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'viewOrUpdate']);

//Route::get('/api/v1/equipments', [\App\Http\Controllers\EquipmentController::class, 'index']);

Route::post('/api/v1/equipments', [\App\Http\Controllers\EquipmentController::class, 'create']);

Route::put('/api/v1/equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'viewOrUpdate']);

Route::delete('/api/v1/equipments/{id}', [\App\Http\Controllers\EquipmentController::class, 'destroy']);
//Route::group(['middleware' => 'auth:sanctum'], function() {
//    Route::get('/api/v1/equipments',[[\App\Http\Controllers\EquipmentController::class, 'index']]);
//});
//Route::group(['middleware'=>'api'],function($router){
//    Route::post('/register', [\App\Http\Controllers\AuthController::class,'register']);
//});
