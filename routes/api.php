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
Route::post('/Actualizar/Seccion', 'loginController@AcutalizarSesion');
Route::post('/Cerrar/Seccion',     'loginController@CerrarSesion');
Route::post('/Validar/Usuario',    'loginController@validarUsuario');
Route::post('/Sesion/Usuario',     'loginController@sesionUsuario');
//Login Aplicacion
Route::post('/Sesion/UsuarioAplicacion', 'LoginAplicacionController@SesionUsuario');
//Areas
Route::post('/Eliminar/Areas',         'AreasController@Eliminar');
Route::post('/Importar/Areas',         'AreasController@Importador');
Route::post('/Insertar/Areas',         'AreasController@Insertar');
Route::post('/Modificar/Areas',        'AreasController@Modificar');
Route::post('/Modificar/AreasEstatus', 'AreasController@ModificarEstatus');
Route::post('/Seleccionar/Areas',      'AreasController@Seleccionar');
Route::post('/Seleccionar/AreasD',     'AreasController@SeleccionarDetalle');
//Calendario
Route::post('/Insertar/Calendario',     'CalendarioController@Insertar');
Route::post('/Seleccionar/CatalogosC', 'CalendarioController@Catalogos');
Route::post('/Seleccionar/Calendario',      'CalendarioController@Seleccionar');
//Cartesiano
Route::post('/Modificar/Resultados',    'CartesianoController@ModificarRespuesta');
Route::post('/Seleccionar/CartesianoD', 'CartesianoController@SeleccionarDetalle');
//Catalogo
Route::post('/Seleccionar/ClientsG',      'CatalogosController@SeleccionarClientG');
Route::post('/Seleccionar/SiteInterestG', 'CatalogosController@SeleccionarSitesInterestG');
//ClientRiskAreas
Route::post('/Eliminar/RelacionCRA',         'ClientsRiskAreaController@Eliminar');
Route::post('/Importador/RelacionCRA',       'ClientsRiskAreaController@Importador');
Route::post('/Insertar/RelacionCRA',         'ClientsRiskAreaController@Insertar');
Route::post('/Modificar/RelacionCRA',        'ClientsRiskAreaController@Modificar');
Route::post('/Modificar/RelacionCRAEstatus', 'ClientsRiskAreaController@ModificarEstatus');
Route::post('/Seleccionar/CatalogosCRA',     'ClientsRiskAreaController@Catalogos');
Route::post('/Seleccionar/RelacionCRA',      'ClientsRiskAreaController@Seleccionar');
Route::post('/Seleccionar/RelacionCRAD',     'ClientsRiskAreaController@SeleccionarDetalle');
Route::post('/Seleccionar/RelacionCRAG',     'ClientsRiskAreaController@SeleccionarGeneral');
//Encuesta
Route::post('/Seleccionar/Encuestas', 'EncuestaAplicacionController@ServiciosXInspector');
Route::post('/Seleccionar/EncuestaD', 'EncuestaAplicacionController@SeleccionarEncuesta');
Route::post('/Responder/Encuesta',    'EncuestaAplicacionController@ResponderEncuesta');
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
//RiskAreas
Route::post('/Agregar/RelacionRA',          'RiskAreaController@Agregar');
Route::post('/Eliminar/RelacionRA',         'RiskAreaController@Eliminar');
Route::post('/Importador/RelacionRA',       'RiskAreaController@Importador');
Route::post('/Insertar/RelacionRA',         'RiskAreaController@Insertar');
Route::post('/Modificar/RelacionRA',        'RiskAreaController@Modificar');
Route::post('/Modificar/RelacionRAEstatus', 'RiskAreaController@ModificarEstatus');
Route::post('/Quitar/RelacionRA',           'RiskAreaController@Quitar');
Route::post('/Seleccionar/CatalogosRA',     'RiskAreaController@Catalogos');
Route::post('/Seleccionar/RelacionRA',      'RiskAreaController@Seleccionar');
Route::post('/Seleccionar/RelacionRAD',     'RiskAreaController@SeleccionarDetalle');
Route::post('/Seleccionar/RelacionRAG',     'RiskAreaController@SeleccionarGeneral');
//RiskSiteInterest 
Route::post('/Agregar/RelacionRSI',         'RiskSiteInterestController@Agregar');
Route::post('/Eliminar/RelacionRSI',         'RiskSiteInterestController@Eliminar');
Route::post('/Importador/RelacionRSI',       'RiskSiteInterestController@Importador');
Route::post('/Insertar/RelacionRSI',         'RiskSiteInterestController@Insertar');
Route::post('/Modificar/RelacionRSI',        'RiskSiteInterestController@Modificar');
Route::post('/Modificar/RelacionRSIEstatus', 'RiskSiteInterestController@ModificarEstatus');
Route::post('/Quitar/RelacionRSI',         'RiskSiteInterestController@Quitar');
Route::post('/Seleccionar/CatalogosRSI',     'RiskSiteInterestController@Catalogos');
Route::post('/Seleccionar/RelacionRSI',      'RiskSiteInterestController@Seleccionar');
Route::post('/Seleccionar/RelacionRSID',     'RiskSiteInterestController@SeleccionarDetalle');
Route::post('/Seleccionar/RelacionRSIG',     'RiskSiteInterestController@SeleccionarGeneral');
//RiskTypes
Route::post('/Eliminar/RiskTypes',         'RiskTypesController@Eliminar');
Route::post('/Importar/RiskTypes',         'RiskTypesController@Importador');
Route::post('/Insertar/RiskTypes',         'RiskTypesController@Insertar');
Route::post('/Modificar/RiskTypes',        'RiskTypesController@Modificar');
Route::post('/Modificar/RiskTypesEstatus', 'RiskTypesController@ModificarEstatus');
Route::post('/Seleccionar/RiskTypes',      'RiskTypesController@Seleccionar');
Route::post('/Seleccionar/RiskTypesD',     'RiskTypesController@SeleccionarDetalle');
