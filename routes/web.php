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


Route::group(['middleware' => 'guest'], function () {

//    Route::get('/', function () {
//        return "Test";
//    });

});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index');

//    Route::group(['middleware' => 'role:administrador'], function () {
//        Route::get('/', "AdministradorController@index");
//    });
//
    Route::group(['middleware' => 'role:cliente'], function () {
//        Route::get('/', "ClienteController@index");
    });
//
//    Route::group(['middleware' => 'role:tecnico'], function () {
//        Route::get('/', "TecnicoController@index");
//    });

    Route::resource('/proyectos', 'ProyectosController');
    Route::get('/proyectos/cambiarEstado/{id}', 'ProyectosController@cambiarEstado');

    Route::resource('/componentes', 'ComponentesController');

});

Auth::routes();
