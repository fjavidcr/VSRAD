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
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('crearusuario', function () {
    return view('/admin/crearusuario');
});
Auth::routes();

Route::get('/home', 'HomeController@index');

/*Route::get('/proyectos/{id?}', function ($id = null) {
    if (isset($id)) {
        $proyecto = App\Proyecto::findOrFail($id);

        return $proyecto;
//        $proyectos = \Auth::getUser()->proyectos;
//
//        foreach ($proyectos as $proyecto)
//            echo $proyecto->isValidado();
    }
    else
        return App\Proyecto::all();
});*/


Route::get('/perfil', function () {
    $user = Auth::user();

    echo $user->name;

    foreach ($user->proyectos as $proyecto) {
        echo '<li>' . $proyecto->nombre . '</li>';
    }
});

Route::resource('/proyectos', 'ProyectosController');