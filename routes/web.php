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
    return redirect()->route('admin.home');
});
Route::get('/home', function () {
    return redirect()->route('admin.home');
});

//Auth
//login
Route::group([], function () {
    Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
    Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/admin/home', 'App\Http\Controllers\HomeController@index')->name('admin.home');
        Route::put('/admin/home', 'App\Http\Controllers\HomeController@update')->name('admin.home.update');
        Route::get('/admin/scores', 'App\Http\Controllers\HomeController@editScores')->name('admin.home.scores.edit')->middleware(['is_student']);
        Route::put('/admin/scores', 'App\Http\Controllers\HomeController@UpdateScore')->name('admin.home.scores.update')->middleware(['is_student']);
    });
});

/*-------------------------------------------  Login with Google ----------------------------------------------------------*/
Route::get('/login/redirect/{provider}', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('/{provider}/callback', 'App\Http\Controllers\Auth\LoginController@callback');


Route::group([], function () {
    Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@showRegisterForm');
    Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register');

    Route::match(['POST', 'GET'], '/logout', 'App\Http\Controllers\Auth\LogoutController@index')->name('logout');
});


//Faculty
//Faculty->create
//Route::post('admin/faculties/create','App\Http\Controllers\FacultyController2@create_one');
Route::group(['middleware' => ['auth', 'isadmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('faculties', \App\Http\Controllers\FacultyController::class);

    Route::resource('classes', \App\Http\Controllers\ClassController::class);

    Route::resource('subjects', \App\Http\Controllers\SubjectController::class);

    Route::resource('students', \App\Http\Controllers\StudentController::class);

    Route::get('permissions', '\App\Http\Controllers\PermissionController@indexPermissions')->name('permissions.index');
    Route::get('permissions/{permission}/edit', '\App\Http\Controllers\PermissionController@editPermissions')->name('permissions.edit');
    Route::get('roles', '\App\Http\Controllers\PermissionController@indexRoles')->name('roles.index');
    Route::get('roles/{role}/edit', '\App\Http\Controllers\PermissionController@editRoles')->name('roles.edit');

    Route::resource('mails', \App\Http\Controllers\MailController::class);
    Route::get('mails/send/expulsion', 'App\Http\Controllers\MailController@sendMailExpulsion')->name('sendMailsExpilsion');
});

Route::group(['middleware' => 'locale'], function () {
    Route::get('change-language/{language}', 'App\Http\Controllers\HomeController@changeLanguage')->name('user.change-language');
});

Route::get('checkpermission', 'App\Http\Controllers\PermissionController@checkRole');

//Route::get('admin/mails', function (){
//    return 'ok';
//});
