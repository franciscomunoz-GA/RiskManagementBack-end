<?php

namespace App\Http\Controllers;
use App\RiskModel;
use App\RiskAreaModel;
use App\AreasModel;
use Illuminate\Http\Request;

class RiskAreaController extends Controller
{
    public function Insertar(Request $request){
        $Nombre    = trim(strtoupper($request->input('Nombre')));
        $IdRiesgo  = trim($request->input('IdRiesgo'));
        $IdArea    = trim($request->input('IdArea'));
        $IdUsuario = $request->input('IdUsuario');
        
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'    =>$Nombre,
                                'IdRiesgo'  =>$IdRiesgo,
                                'IdArea'    =>$IdArea,
                                'IdUsuario' =>$IdUsuario);
            
            $resultado = $RiskAreaModel->InsertarRiskArea($parametros);
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
        $Id        = $request->input('Id');
        $Nombre    = trim(strtoupper($request->input('Nombre')));
        $IdRiesgo  = trim($request->input('IdRiesgo'));
        $IdArea    = trim($request->input('IdArea'));
        $IdUsuario = $request->input('IdUsuario');

        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'editar relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'       =>$Id,
                                'Nombre'   =>$Nombre,
                                'IdRiesgo' =>$IdRiesgo,
                                'IdArea'   =>$IdArea);
            $resultado = $RiskAreaModel->ModificarRiskArea($parametros);
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
        $Id        = $request->input('Id');
        $Accion    = $request->input('Accion');
        $IdUsuario = $request->input('IdUsuario');
        
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$Id,
                                'Accion' =>$Accion);
            $resultado = $RiskAreaModel->ModificarEstatus($parametros);
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
        $IdUsuario   = $request->input('IdUsuario');
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $RiskAreaModel->SeleccionarRiskArea();
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
        $Id        = $request->input('Id');
        $IdUsuario = $request->input('IdUsuario');
                
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id);
            $resultado = $RiskAreaModel->SeleccionarDRiskArea($parametros);
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
        $IdUsuario   = $request->input('IdUsuario');
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $RiskAreaModel->SeleccionarGRiskArea();
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
        $Datos   = json_decode($request->input('Datos'));
        $IdUsuario = $request->input('IdUsuario');
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Datos'     =>$Datos,
                                'IdUsuario' =>$IdUsuario);
            $resultado = $RiskAreaModel->ImportarRiskArea($parametros);
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
        $Id        = $request->input('Id');
        $IdUsuario = $request->input('IdUsuario');
        
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion riesgo area');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id);
            $resultado = $RiskAreaModel->Eliminar($parametros);
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
        $RiskModel     = new RiskModel;
        $AreasModel = new AreasModel;
        $resultadoR = $RiskModel->SeleccionarGRisk();
        $resultadoA = $AreasModel->SeleccionarGAreas();
        $resultado = array('Riesgos'       => $resultadoR,
                           'Areas' => $resultadoA);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
