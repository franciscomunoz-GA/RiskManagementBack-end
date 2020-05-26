<?php

namespace App\Http\Controllers;
use App\RiskModel;
use App\RiskTypesModel;
//use App\AreasModel;
use App\DimensionsModel;
use App\LegalStandardsModel;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function Insertar(Request $request){
        $idRiesgo    = trim($request->input('IdRiesgo'));
        $nombre      = trim(strtoupper($request->input('Nombre')));
        $idDimension = $request->input('IdDimension');
        $idTRiesgo   = $request->input('IdTRiesgo');
        $idCLegales  = $request->input('IdCLegales');
        //$idAreas     = $request->input('IdAreas');
        $idUsuario   = $request->input('IdUsuario');
        
        /*if ($idAreas == 0 || empty($idAreas)){
            $idAreas = null;
        }*/

        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'crear riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('IdRiesgo'    =>$idRiesgo,
                                'Nombre'      =>$nombre,
                                'IdDimension' =>$idDimension,
                                'IdTRiesgo'   =>$idTRiesgo,
                                'IdCLegales'  =>$idCLegales,
                                //'IdAreas'     =>$idAreas,
                                'IdUsuario'   =>$idUsuario);
            
            $resultado = $riskModel->InsertarRisk($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Modificar(Request $request){
        $id          = $request->input('Id');
        $idRiesgo    = trim($request->input('IdRiesgo'));
        $nombre      = trim(strtoupper($request->input('Nombre')));
        $idDimension = $request->input('IdDimension');
        $idTRiesgo   = $request->input('IdTRiesgo');
        $idCLegales  = $request->input('IdCLegales');
        $idUsuario   = $request->input('IdUsuario');
        //$idAreas     = $request->input('IdAreas');
        
        /*if ($idAreas == 0 || empty($idAreas)){
            $idAreas = null;
        }     */  
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'editar riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'          =>$id,
                                'IdRiesgo'    =>$idRiesgo,
                                'Nombre'      =>$nombre,
                                'IdDimension' =>$idDimension,
                                'IdTRiesgo'   =>$idTRiesgo,
                                'IdCLegales'  =>$idCLegales);
                                //'IdAreas'     =>$idAreas);
            $resultado = $riskModel->ModificarRisk($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarEstatus(Request $request){
        $id     = $request->input('Id');
        $accion = $request->input('Accion');
        $idUsuario   = $request->input('IdUsuario');
        
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'eliminar riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Accion' =>$accion);
            $resultado = $riskModel->ModificarEstatus($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Seleccionar(Request $request){
        $idUsuario   = $request->input('IdUsuario');
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $riskModel->SeleccionarRisk();
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarDetalle(Request $request){
        $id = $request->input('Id');
        $idUsuario   = $request->input('IdUsuario');
                
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$id);
            $resultado = $riskModel->SeleccionarDRisk($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarGeneral(Request $request){
        $idUsuario   = $request->input('IdUsuario');
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $riskModel->SeleccionarGRisk();
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Importador(Request $request){
        $datos   = json_decode($request->input('Datos'));
        $idUsuario = $request->input('IdUsuario');
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'crear riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Datos'     =>$datos,
                                'IdUsuario' =>$idUsuario);
            $resultado = $riskModel->ImportarRisk($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        $idUsuario   = $request->input('IdUsuario');
        
        $riskModel = new RiskModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'eliminar riesgos');
        $permiso = $riskModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id);
            $resultado = $riskModel->Eliminar($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Catalogos(){
        $riskTypesModel = new RiskTypesModel;
        //$areasModel = new AreasModel;
        $dimensionsModel = new DimensionsModel;
        $legalStandardsModel = new LegalStandardsModel;
        $resultadoRT = $riskTypesModel->SeleccionarGRiskTypes();
        //$resultadoA  = $areasModel->SeleccionarGAreas();
        $resultadoD  = $dimensionsModel->SeleccionarGDimensions();
        $resultadoLS = $legalStandardsModel->SeleccionarGLegalStandard();
        $resultado = array('TiposRiesgos'     => $resultadoRT,
                           //'Areas'            => $resultadoA,
                           'Dimensiones'      => $resultadoD,
                           'CriteriosLegales' => $resultadoLS);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    
}
