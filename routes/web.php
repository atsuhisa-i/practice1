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

// Route::get('/', function () {
//     return view('/auth/login');
// });

Auth::routes();

Route::get('/', 'App\Http\Controllers\Auth\LoginController@index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/messages/index', 'App\Http\Controllers\MessagesController@index');
Route::get('/messages/create', 'App\Http\Controllers\MessagesController@create')->name('create');
Route::post('/messages/store', 'App\Http\Controllers\MessagesController@store');
Route::get('/messages/show/{id}', 'App\Http\Controllers\MessagesController@show');
Route::get('/messages/edit/{id}', 'App\Http\Controllers\MessagesController@edit');
Route::patch('messages/update/{id}', 'App\Http\Controllers\MessagesController@update');
Route::delete('/messages/delete/{id}', 'App\Http\Controllers\MessagesController@destroy');
Route::post('/comment/store/{id}', 'App\Http\Controllers\CommentsController@store');
Route::get('/user/show/{id}', 'App\Http\Controllers\UserController@show');
Route::get('/user/edit/{id}', 'App\Http\Controllers\UserController@edit');
Route::patch('/user/update/{id}', 'App\Http\Controllers\UserController@update');
Route::delete('/user/delete/{id}', 'App\Http\Controllers\UserController@destroy');
Route::get('/group/index', 'App\Http\Controllers\GroupController@index');
Route::get('/group/create', 'App\Http\Controllers\GroupController@create');
Route::post('/group/store', 'App\Http\Controllers\GroupController@store');
Route::get('/messages/download', 'App\Http\Controllers\MessagesController@download');
Route::post('/messages/import', 'App\Http\Controllers\MessagesController@import');
Route::get('/messages/json', 'App\Http\Controllers\MessagesController@json');
Route::post('/messages/json_import', 'App\Http\Controllers\MessagesController@json_import');
Route::get('/user/download', 'App\Http\Controllers\UserController@download');
Route::post('/user/import', 'App\Http\controllers\UserController@import');