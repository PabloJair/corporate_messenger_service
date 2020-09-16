<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:api')->get('/user', function (Request $request) {return $request->user();});

Route::post('/login', 'AuthController@login');
Route::post('/auth/signup', 'AuthController@signup');
Route::get('/auth/signup/activate/{token}/{codCompany}', 'AuthController@signupActivate');

//CRUD de AREA OK

Route::post('/area/add', 'AreaController@store');
Route::patch('/area/update/{id}', 'AreaController@update');
Route::delete('/area/delete/{id}', 'AreaController@destroy');
Route::get('/area/all', 'AreaController@all');

//CRUD de Compañia

Route::post('/company/add', 'CompanyController@store');
Route::patch('/company/update/{id}', 'CompanyController@update');
Route::delete('/company/delete/{id}', 'CompanyController@destroy');
Route::get('/company/{id}', 'CompanyController@show');
Route::get('/company/all', 'CompanyController@show');

//CRUD  MODULE
Route::post('/module/add', 'ModuleController@store');
Route::patch('/module/update/{id}', 'ModuleController@update');
Route::delete('/module/delete/{id}', 'ModuleController@destroy');
Route::get('/module/all', 'ModuleController@show');
// ROL
Route::post('/rol/add', 'RolController@store');
Route::patch('/rol/update/{id}', 'RolController@update');
Route::delete('/rol/delete/{id}', 'RolController@destroy');
Route::get('/rol/all', 'RolController@show');
// UserSTatus

Route::post('/userStatus/add', 'UserStatusController@store');
Route::patch('/userStatus/{id}', 'UserStatusController@update');
Route::delete('/serStatus/{id}', 'UserStatusController@destroy');
Route::get('/userStatus/all', 'UserStatusController@show');

//User

Route::get('/user/{idUser}/{idCompany}', 'UserController@UserByIdCompany');
Route::post('/user/updatePhoto/{idUser}', 'UserController@updatePhoto');
Route::patch('/user/updateProfile/{idUser}', 'UserController@updateProfile');
Route::post('/user/sendPushNotification/{idUser}', 'UserController@sendPushNotification');
Route::post('/user/recoveryPassword/', 'AuthController@recoveryPassword');

Route::get('/users/forCompany/{idCompany}', 'UserController@GetUsersByIdCompany');
Route::get('/user/all', 'UserController@getAllUser');

Route::post('/assigment/add', 'AssigmentOfActivityController@store');
Route::patch('/assigment/update/{id}', 'AssigmentOfActivityController@update');
Route::delete('/assigment/delete/{id}', 'AssigmentOfActivityController@destroy');
Route::get('/assigment/all', 'AssigmentOfActivityController@all');
Route::get('/assigment/userBetweenDate/{idUser}/{startDate}', 'AssigmentOfActivityController@userBetweenDate');
Route::get('/assigment/for/weekend/{idUser}', 'AssigmentOfActivityController@getCurrentWeekend');
Route::get('/assigment/update/{idAssigment}/{status}', 'AssigmentOfActivityController@changeStatusAssigment');


//MESSAGE
Route::get('/message/by/{idUser}', 'MessageController@getAllMessageByUser');
Route::post('/message/send', 'MessageController@sendMessage');
Route::get('/message/room/{idRoom}/{idUser}/{date}', 'MessageController@getMessageFromRoom');
Route::get('/message/fromLastMessage/{idRoom}/{idUser}/{date}/{lastMessage}', 'MessageController@getLastMessageFrom');


Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');

});
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('/logout', 'AuthController@logout');


});
