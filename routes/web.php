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
Route::resource('mantenimiento/roles','RolController');
Auth::routes();
Route::get('mantenimiento/usuarios/create', 'UsuarioController@create');
<<<<<<< HEAD
Route::get('get/{id}', 'UsuarioController@getStates');
Route::get('states/get/{id}', 'Auth\RegisterController@getStates');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout','Auth\LoginController@logout');
Route::get('/{slug?}', 'Auth\LoginController@logout');
=======
<<<<<<< HEAD
Route::get('get/{id}', 'UsuarioController@getStates');
Route::get('states/get/{id}', 'Auth\RegisterController@getStates');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout','Auth\LoginController@logout');
Route::get('/{slug?}', 'Auth\LoginController@logout');
=======
Route::get('states/get/{id}', 'UsuarioController@getStates');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout','Auth\LoginController@logout');
Route::get('/{slug?}', 'HomeController@index');
>>>>>>> 6f1b6b1aac6c00ef1c47b5b3af997e166c257e80
>>>>>>> 54433a6b974e1b2ca95c2daa453f89fd9e663aab

