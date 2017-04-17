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

/*Route::get('/', function () {

    return view('home');

});*/

Route::group(['middleware' => 'guest'], function () {

    //Route::get('/home', 'HomeController@index');

});

Route::group(['middleware' => 'auth'], function () {

    Route::resource('/', 'RedireccionController');




    Route::group(['middleware' => 'rol:administrador'], function () {

        Route::get('administrador/form_crear_usuario', 'AdministradorController@form_crear_usuario')->name('administrador.form_crear_usuario');
        Route::post('administrador/crear_usuario', 'AdministradorController@crear_usuario')->name('administrador.crear_usuario');
        Route::get('administrador/editar_usuario/{id}', 'AdministradorController@form_editar_usuario')->name('administrador.form_editar_usuario');
        Route::post('administrador/editar_usuario', 'AdministradorController@editar_usuario')->name('administrador.editar_usuario');
        Route::post('administrador/deshabilitar_usuario', 'AdministradorController@deshabilitar_usuario')->name('administrador.deshabilitar_usuario');
        Route::post('administrador/habilitar_usuario', 'AdministradorController@habilitar_usuario')->name('administrador.habilitar_usuario');

        Route::get('administrador/form_crear_producto', 'AdministradorController@form_crear_producto')->name('administrador.form_crear_producto');
        Route::post('administrador/crear_producto', 'AdministradorController@crear_producto')->name('administrador.crear_producto');
        Route::get('administrador/editar_producto/{id}', 'AdministradorController@form_editar_producto')->name('administrador.form_editar_producto');
        Route::post('administrador/editar_producto', 'AdministradorController@editar_producto')->name('administrador.editar_producto');
        Route::post('administrador/deshabilitar_producto', 'AdministradorController@deshabilitar_producto')->name('administrador.deshabilitar_producto');
        Route::post('administrador/habilitar_producto', 'AdministradorController@habilitar_producto')->name('administrador.habilitar_producto');

        Route::get('administrador/form_crear_plano', 'AdministradorController@form_crear_plano')->name('administrador.form_crear_plano');
        Route::post('administrador/crear_plano', 'AdministradorController@crear_plano')->name('administrador.crear_plano');
        Route::get('administrador/editar_plano/{id}', 'AdministradorController@form_editar_plano')->name('administrador.form_editar_plano');
        Route::post('administrador/editar_plano', 'AdministradorController@editar_plano')->name('administrador.editar_plano');
        Route::post('administrador/deshabilitar_plano', 'AdministradorController@deshabilitar_plano')->name('administrador.deshabilitar_plano');
        Route::post('administrador/habilitar_plano', 'AdministradorController@habilitar_plano')->name('administrador.habilitar_plano');

        Route::resource('administrador', 'AdministradorController');
    });


    Route::group(['middleware' => 'rol:director_comercial'], function () {

        Route::post('director_comercial/asignar_tecnico', 'DirectorComercialController@asignar_tecnico')->name('director_comercial.asignar_tecnico');
        Route::post('director_comercial/asignar_oferta', 'DirectorComercialController@asignar_oferta')->name('director_comercial.asignar_oferta');
        Route::post('director_comercial/añadir_cliente', 'DirectorComercialController@añadir_cliente')->name('director_comercial.añadir_cliente');

        Route::resource('director_comercial', 'DirectorComercialController');
    });


    Route::group(['middleware' => 'rol:cliente'], function () {
        Route::get('/cliente/cambiar_estado/{id}', 'ClienteController@cambiar_estado')->name('cliente.cambiar_estado');
        Route::get('/cliente/edit/{id}', 'ClienteController@edit')->name('cliente.edit');
        Route::post('/cliente/editar', 'ClienteController@editar')->name('cliente.editar');

        Route::resource('cliente', 'ClienteController');
    });

    Route::group(['middleware' => 'rol:comercial'], function () {

        Route::post('comercial/asignar_tecnico', 'ComercialController@asignar_tecnico')->name('comercial.asignar_tecnico');
        Route::post('comercial/asignar_oferta', 'ComercialController@asignar_oferta')->name('comercial.asignar_oferta');

        Route::resource('comercial', 'ComercialController');
    });


    Route::group(['middleware' => 'rol:tecnico'], function () {

        Route::get('tecnico/proyecto/{id}', 'TecnicoController@show')->name('tecnico.proyecto');
        Route::post('tecnico/cambiar_estado', 'TecnicoController@cambiar_estado')->name('tecnico.cambiar_estado');
        Route::post('tecnico/enviar_mensaje', 'TecnicoController@enviar_mensaje')->name('tecnico.enviar_mensaje');

        Route::resource('tecnico', 'TecnicoController');
    });


    //Route::resource('/proyectos', 'ProyectosController');
    //Route::get('/proyectos/cambiarEstado/{id}', 'ProyectosController@cambiarEstado');

    //Route::resource('/productos', 'ProductosController');

    //Route::resource('/user', 'UserController');

});

Auth::routes();
