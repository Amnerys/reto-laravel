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

//Usar middleware
use App\Http\Middleware\ApiAuthMiddleware;

//Rutas del controlador de usuarios
Route::post('/api/register', 'App\Http\Controllers\UserController@register');
Route::post('/api/login', 'App\Http\Controllers\UserController@login');
Route::put('/api/user/update', 'App\Http\Controllers\UserController@update')->middleware(ApiAuthMiddleware::class);
Route::post('/api/user/upload','App\Http\Controllers\UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}','App\Http\Controllers\UserController@getImage');
Route::get('/api/user/detail/{id}','App\Http\Controllers\UserController@detail');
Route::delete('/api/user/{id}','App\Http\Controllers\UserController@deleteUser');

//Rutas del controlador de categorías (GET, POST, PUT y DELETE)
Route::resource('api/category', 'App\Http\Controllers\CategoryController');

//Rutas del controlador de productos
Route::resource('api/product', 'App\Http\Controllers\ProductController');
Route::post('/api/product/upload','App\Http\Controllers\ProductController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/product/image/{filename}','App\Http\Controllers\ProductController@getImage');
Route::get('/api/product/category/{id}','App\Http\Controllers\ProductController@getProductsByCategory');
