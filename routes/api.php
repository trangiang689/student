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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Api
Route::group([ 'as'=> 'api.'], function () {
    Route::resource('permissions', \App\Http\Controllers\Api\Decentralization\PermissionController::class);
    Route::resource('roles', \App\Http\Controllers\APi\decentralization\RoleController::class);
    Route::resource('students',\App\Http\Controllers\Api\StudentsApiController::class);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
