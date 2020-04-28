<?php

namespace App\Http\Controllers;
use App\RiskModel;
use App\RiskTypesModel;
use App\AreasModel;
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
        $idAreas     = $request->input('IdAreas');
        $idUsuario   = $request->input('IdUsuario');
        
        if ($idAreas == 0 || empty($idAreas)){
            $idAreas = null;
        }

        $riskModel = new RiskModel;
        $parametros = array('IdRiesgo'    =>$idRiesgo,
                            'Nombre'      =>$nombre,
                            'IdDimension' =>$idDimension,
                            'IdTRiesgo'   =>$idTRiesgo,
                            'IdCLegales'  =>$idCLegales,
                            'IdAreas'     =>$idAreas,
                            'IdUsuario'   =>$idUsuario);
        
        $resultado = $riskModel->InsertarRisk($parametros);
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
        $idAreas     = $request->input('IdAreas');
        
        if ($idAreas == 0 || empty($idAreas)){
            $idAreas = null;
        }       
        $riskModel = new RiskModel;
        $parametros = array('Id'          =>$id,
                            'IdRiesgo'    =>$idRiesgo,
                            'Nombre'      =>$nombre,
                            'IdDimension' =>$idDimension,
                            'IdTRiesgo'   =>$idTRiesgo,
                            'IdCLegales'  =>$idCLegales,
                            'IdAreas'     =>$idAreas);
        $resultado = $riskModel->ModificarRisk($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarEstatus(Request $request){
        $id     = $request->input('Id');
        $accion = $request->input('Accion');
        
        $riskModel = new RiskModel;
        $parametros = array('Id'     =>$id,
                            'Accion' =>$accion);
        $resultado = $riskModel->ModificarEstatus($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Seleccionar(){
        $riskModel = new RiskModel;
        $resultado = $riskModel->SeleccionarRisk();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarDetalle(Request $request){
        $id = $request->input('Id');
                
        $riskModel = new RiskModel;
        $parametros = array('Id' =>$id);
        $resultado = $riskModel->SeleccionarDRisk($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarGeneral(){
        $riskModel = new RiskModel;
        $resultado = $riskModel->SeleccionarGRisk();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Importador(Request $request){
        $datos   = json_decode($request->input('Datos'));
        $idUsuario = $request->input('IdUsuario');
        $riskModel = new RiskModel;
        $parametros = array('Datos'     =>$datos,
                            'IdUsuario' =>$idUsuario);
        $resultado = $riskModel->ImportarRisk($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        
        $riskModel = new RiskModel;
        $parametros = array('Id'     =>$id);
        $resultado = $riskModel->Eliminar($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Catalogos(){
        $riskTypesModel = new RiskTypesModel;
        $areasModel = new AreasModel;
        $dimensionsModel = new DimensionsModel;
        $legalStandardsModel = new LegalStandardsModel;
        $resultadoRT = $riskTypesModel->SeleccionarGRiskTypes();
        $resultadoA  = $areasModel->SeleccionarGAreas();
        $resultadoD  = $dimensionsModel->SeleccionarGDimensions();
        $resultadoLS = $legalStandardsModel->SeleccionarGLegalStandard();
        $resultado = array('TiposRiesgos'     => $resultadoRT,
                           'Areas'            => $resultadoA,
                           'Dimensiones'      => $resultadoD,
                           'CriteriosLegales' => $resultadoLS,);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    
}
