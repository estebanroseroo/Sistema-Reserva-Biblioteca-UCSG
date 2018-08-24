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

Route::get('/', function () {return view('auth/login');});
Route::resource('mantenimiento/areas','AreaController');
Route::resource('mantenimiento/facultades','FacultadController');
Route::resource('mantenimiento/carreras','CarreraController');
Route::resource('mantenimiento/usuarios','UsuarioController');
Route::resource('mantenimiento/roles','RolController');
Route::resource('mantenimiento/horarios','HorarioController');
Route::resource('menu/perfiles','PerfilController');
Route::resource('menu/change','ContrasenaController');
Route::resource('operacion/adminreservas','AdminreservaController');
Route::resource('menu/reservas','UsureservaController');
Route::resource('operacion/consultas', 'QrLoginController');
Route::resource('operacion/reservasconfirmadas', 'AdminreservaconfirmadaController');
Route::resource('reporte/chart', 'ChartController');

Auth::routes();
Route::get('mantenimiento/usuarios/create', 'UsuarioController@create');
Route::get('get/{id}', 'UsuarioController@getStates');
Route::get('facu/get/{idtipousu}', 'UsuarioController@getFacu');
Route::get('carre/get/{idfacu}', 'ChartController@getCarre');
Route::get('states/get/{id}', 'Auth\RegisterController@getStates');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout','Auth\LoginController@logout');
Route::get('/{slug?}', 'Auth\LoginController@logout');
Route::get('/test/datepicker', function () {return view('operacion/adminreservas/create');});
Route::post('/test/save', ['as' => 'save-date',
                           'uses' => 'AdminreservaController@showDate', 
                            function () {
                                return '';
                            }]);

Route::post('qrLogin', ['uses' => 'QrLoginController@check']);

