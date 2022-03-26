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

//Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

//Rutas API (PUT actualizar recursos o datos, DELETE para eliminar datos o recursos)

//Rutas de prueba GET para conseguir datos
Route::get('/usuario/pruebas', 'App\Http\Controllers\UserController@pruebas');
Route::get('/categoria/pruebas', 'App\Http\Controllers\CategoryController@pruebas');
Route::get('/producto/pruebas', 'App\Http\Controllers\ProductController@pruebas');

//Rutas del controlador de usuarios POST para guardar datos o hacer lógica, recibir desde formulario
Route::post('/api/register', 'App\Http\Controllers\UserController@register');
Route::post('/api/login', 'App\Http\Controllers\UserController@login');
Route::put('/api/user/update', 'App\Http\Controllers\UserController@update')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/user/upload','App\Http\Controllers\UserController@upload')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}','App\Http\Controllers\UserController@getImage');
Route::get('/api/user/detail/{id}','App\Http\Controllers\UserController@detail');
