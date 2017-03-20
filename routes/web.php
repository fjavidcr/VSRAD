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

    return view('home');

});

Route::group(['middleware' => 'guest'], function () {

    //Route::get('/home', 'HomeController@index');

});

Route::group(['middleware' => 'auth'], function () {

    /*
    Route::group(['middleware' => 'rol:administrador'], function () {
        Route::get('/home', 'AdministradorController@index');
    });
    */

    Route::group(['middleware' => 'rol:cliente'], function () {
        //Route::get('/', 'ClienteController@index');
        Route::get('/home', 'ClienteController@index');
    });

    Route::group(['middleware' => 'rol:comercial'], function () {
        Route::get('/', 'ComercialController@redireccion');

        Route::post('comercial/asignar_tecnico', 'ComercialController@asignar_tecnico')->name('comercial.asignar_tecnico');
        Route::post('comercial/asignar_oferta', 'ComercialController@asignar_oferta')->name('comercial.asignar_oferta');

        Route::resource('comercial', 'ComercialController');
    });

    /*
    Route::group(['middleware' => 'rol:tecnico'], function () {
        Route::get('/home', 'TecnicoController@index');
    });
    */

    Route::resource('/proyectos', 'ProyectosController');
    Route::get('/proyectos/cambiarEstado/{id}', 'ProyectosController@cambiarEstado');

    Route::resource('/productos', 'ProductosController');

    Route::resource('/user', 'UserController');

});

Auth::routes();
