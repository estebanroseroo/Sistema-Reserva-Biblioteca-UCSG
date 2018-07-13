<?php

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
    return view('auth/login');
});
Route::resource('mantenimiento/areas','AreaController');
Route::resource('mantenimiento/facultades','FacultadController');
Route::resource('mantenimiento/carreras','CarreraController');
Route::resource('mantenimiento/usuarios','UsuarioController');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout','Auth\LoginController@logout');
Route::get('/{slug?}', 'HomeController@index');
Route::get('mantenimiento/usuarios/create', 'UsuarioController@create');
Route::get('states/get/{id}', 'UsuarioController@getStates');