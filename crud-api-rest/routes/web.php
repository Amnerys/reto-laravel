<?php

use App\Http\Controllers\PruebasController;
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

//Rutas prueba
/*
Route::get('/', function () {
    return '<h1>Hola Mundo!<h1>';
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/pruebas{nombre?}', function($nombre=null) {
    $texto = '<h2>Texto desde una ruta<h2>';
    $texto .= 'Nombre: '. $nombre;
    return view('pruebas', array(
        'texto' => $texto
    ));
});

Route::get('/animales', 'App\Http\Controllers\PruebasController@index');
Route::get('/test-orm', 'App\Http\Controllers\PruebasController@testOrm');
*/

//Rutas API (PUT actualizar recursos o datos, DELETE para eliminar datos o recursos)

//Rutas de prueba GET para conseguir datos
Route::get('/usuario/pruebas', 'App\Http\Controllers\UserController@pruebas');
Route::get('/categoria/pruebas', 'App\Http\Controllers\CategoryController@pruebas');
Route::get('/producto/pruebas', 'App\Http\Controllers\ProductController@pruebas');

//Rutas del controlador de usuarios POST para guardar datos o hacer lógica, recibir desde formulario
Route::post('/api/register', 'App\Http\Controllers\UserController@register');
Route::post('/api/login', 'App\Http\Controllers\UserController@login');
Route::put('/api/user/update', 'App\Http\Controllers\UserController@update');
Route::post('/api/user/upload', 'App\Http\Controllers\UserController@upload');
