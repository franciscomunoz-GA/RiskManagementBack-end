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
Route::post('/validarUsuario','loginController@validarUsuario');
Route::post('/sesionUsuario','loginController@sesionUsuario');
Route::post('/obtenerMenus','loginController@obtenerMenus');
Route::post('/obtenerSubmenus','loginController@obtenerSubmenusXMenu');
Route::post('/obtenerTickets','ticketController@obtenerTickets');

