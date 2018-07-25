<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('hello',function(){
	return 'hello';
});
Route::controller('passport','PassportController');
Route::controller('index','IndexController');
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['admin.check']], function()
{
    Route::controller('index','IndexController');
});