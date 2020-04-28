<?php

namespace App\Http\Controllers;
use App\RiskTypesModel;
use Illuminate\Http\Request;

class RiskTypesController extends Controller
{
    public function Insertar(Request $request){
        $nombre    = trim(strtoupper($request->input('Nombre')));
        $idUsuario = $request->input('IdUsuario');
        $riskTypesModel = new RiskTypesModel;
        $parametros = array('Nombre'    =>$nombre,
                            'IdUsuario' =>$idUsuario);
        $resultado = $riskTypesModel->InsertarRiskType($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Modificar(Request $request){
        $id     = $request->input('Id');
        $nombre = trim(strtoupper($request->input('Nombre')));
                
        $riskTypesModel = new RiskTypesModel;
        $parametros = array('Id'     =>$id,
                            'Nombre' =>$nombre);
        $resultado = $riskTypesModel->ModificarRiskType($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarEstatus(Request $request){
        $id     = $request->input('Id');
        $accion = $request->input('Accion');
        
        $riskTypesModel = new RiskTypesModel;
        $parametros = array('Id'     =>$id,
                            'Accion' =>$accion);
        $resultado = $riskTypesModel->ModificarEstatus($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Seleccionar(){
        $riskTypesModel = new RiskTypesModel;
        $resultado = $riskTypesModel->SeleccionarRiskTypes();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarDetalle(Request $request){
        $id = $request->input('Id');
                
        $riskTypesModel = new RiskTypesModel;
        $parametros = array('Id' =>$id);
        $resultado = $riskTypesModel->SeleccionarDRiskTypes($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Importador(Request $request){
        $nombres   = json_decode($request->input('Nombres'));
        $idUsuario = $request->input('IdUsuario');
        $riskTypesModel = new RiskTypesModel;
        $parametros = array('Nombres'   =>$nombres,
                            'IdUsuario' =>$idUsuario);
        $resultado = $riskTypesModel->ImportarRiskType($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado
                    ,'parametros' => $parametros);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        
        $riskTypesModel = new RiskTypesModel;
        $parametros = array('Id'     =>$id);
        $resultado = $riskTypesModel->Eliminar($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
