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