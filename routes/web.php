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
Route::get('/kontak','ControllerKontak@index');
Route::get('/bridging/{limit}/{id?}','ControllerKontak@show')->where('id', '(.*)');
Route::post('/bridging','ControllerKontak@store');
