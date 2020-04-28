<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Login
Route::post('/Validar/Usuario', 'loginController@validarUsuario');
Route::post('/Sesion/Usuario',  'loginController@sesionUsuario');
//Areas
Route::post('/Eliminar/Areas',         'AreasController@Eliminar');
Route::post('/Importar/Areas',         'AreasController@Importador');
Route::post('/Insertar/Areas',         'AreasController@Insertar');
Route::post('/Modificar/Areas',        'AreasController@Modificar');
Route::post('/Modificar/AreasEstatus', 'AreasController@ModificarEstatus');
Route::post('/Seleccionar/Areas',      'AreasController@Seleccionar');
Route::post('/Seleccionar/AreasD',     'AreasController@SeleccionarDetalle');
//Dimensions
Route::post('/Eliminar/Dimensiones',         'DimensionsController@Eliminar');
Route::post('/Importar/Dimensiones',         'DimensionsController@Importador');
Route::post('/Insertar/Dimensiones',         'DimensionsController@Insertar');
Route::post('/Modificar/Dimensiones',        'DimensionsController@Modificar');
Route::post('/Modificar/DimensionesEstatus', 'DimensionsController@ModificarEstatus');
Route::post('/Seleccionar/Dimensiones',      'DimensionsController@Seleccionar');
Route::post('/Seleccionar/DimensionesD',     'DimensionsController@SeleccionarDetalle');
//LegalStandards
Route::post('/Eliminar/LegalStandards',         'LegalStandardsController@Eliminar');
Route::post('/Importar/LegalStandards',         'LegalStandardsController@Importador');
Route::post('/Insertar/LegalStandards',         'LegalStandardsController@Insertar');
Route::post('/Modificar/LegalStandards',        'LegalStandardsController@Modificar');
Route::post('/Modificar/LegalStandardsEstatus', 'LegalStandardsController@ModificarEstatus');
Route::post('/Seleccionar/LegalStandards',      'LegalStandardsController@Seleccionar');
Route::post('/Seleccionar/LegalStandardsD',     'LegalStandardsController@SeleccionarDetalle');
//Risk
Route::post('/Eliminar/Risk',          'RiskController@Eliminar');
Route::post('/Importar/Risk',          'RiskController@Importador');
Route::post('/Insertar/Risk',          'RiskController@Insertar');
Route::post('/Modificar/Risk',         'RiskController@Modificar');
Route::post('/Modificar/RiskEstatus',  'RiskController@ModificarEstatus');
Route::post('/Seleccionar/CatalogosR', 'RiskController@Catalogos');
Route::post('/Seleccionar/Risk',       'RiskController@Seleccionar');
Route::post('/Seleccionar/RiskD',      'RiskController@SeleccionarDetalle');
Route::post('/Seleccionar/RiskG',      'RiskController@SeleccionarGeneral');
//RiskTypes
Route::post('/Eliminar/RiskTypes',         'RiskTypesController@Eliminar');
Route::post('/Importar/RiskTypes',         'RiskTypesController@Importador');
Route::post('/Insertar/RiskTypes',         'RiskTypesController@Insertar');
Route::post('/Modificar/RiskTypes',        'RiskTypesController@Modificar');
Route::post('/Modificar/RiskTypesEstatus', 'RiskTypesController@ModificarEstatus');
Route::post('/Seleccionar/RiskTypes',      'RiskTypesController@Seleccionar');
Route::post('/Seleccionar/RiskTypesD',     'RiskTypesController@SeleccionarDetalle');
