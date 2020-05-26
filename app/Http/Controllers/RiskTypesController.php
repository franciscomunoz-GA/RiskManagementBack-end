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
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'crear tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'    =>$nombre,
                                'IdUsuario' =>$idUsuario);
            $resultado = $riskTypesModel->InsertarRiskType($parametros);
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
        $id     = $request->input('Id');
        $nombre = trim(strtoupper($request->input('Nombre')));
        $idUsuario = $request->input('IdUsuario');
                
        $riskTypesModel = new RiskTypesModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'editar tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Nombre' =>$nombre);
            $resultado = $riskTypesModel->ModificarRiskType($parametros);
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
        $idUsuario = $request->input('IdUsuario');
        
        $riskTypesModel = new RiskTypesModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'eliminar tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Accion' =>$accion);
            $resultado = $riskTypesModel->ModificarEstatus($parametros);
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
        $idUsuario = $request->input('IdUsuario');
        $riskTypesModel = new RiskTypesModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'ver tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $riskTypesModel->SeleccionarRiskTypes();
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
        $idUsuario = $request->input('IdUsuario');
                
        $riskTypesModel = new RiskTypesModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'ver tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$id);
            $resultado = $riskTypesModel->SeleccionarDRiskTypes($parametros);
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
        $nombres   = json_decode($request->input('Nombres'));
        $idUsuario = $request->input('IdUsuario');
        $riskTypesModel = new RiskTypesModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'crear tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombres'   =>$nombres,
                                'IdUsuario' =>$idUsuario);
            $resultado = $riskTypesModel->ImportarRiskType($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado
                    ,'parametros' => $parametros);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        $idUsuario = $request->input('IdUsuario');
        
        $riskTypesModel = new RiskTypesModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'eliminar tipos riesgos');
        $permiso = $riskTypesModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id);
            $resultado = $riskTypesModel->Eliminar($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
