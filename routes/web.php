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
        //Route::get('/', 'ComercialController@index');

        //Route::resource('/comercial', 'ComercialController');
        Route::group(['as' => 'comercial'], function () {
            Route::get('/home', 'ComercialController@index');
            //Route::get('/comercial/asignarOfertaTecnico/{id}', 'ComercialController@asignarOfertaTecnico');
            Route::get('/comercial/asignarOfertaTecnico/{id}', ['as' => 'asignarOfertaTecnico', 'uses' => 'ComercialController@asignarOfertaTecnico']);
        });
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
