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
        $IdArea    = trim($request->input('IdArea'));
        $IdRiesgo  = json_decode($request->input('IdRiesgo'));
        $IdUsuario = $request->input('IdUsuario');
        
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion area riesgo');
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
        $IdArea    = trim($request->input('IdArea'));
        $IdUsuario = $request->input('IdUsuario');

        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'editar relacion area riesgo');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'       =>$Id,
                                'Nombre'   =>$Nombre,
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
                             'Permiso'   =>'eliminar relacion area riesgo');
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
                             'Permiso'   =>'ver relacion area riesgo');
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
                             'Permiso'   =>'ver relacion area riesgo');
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
                             'Permiso'   =>'ver relacion area riesgo');
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
                             'Permiso'   =>'crear relacion area riesgo');
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
                             'Permiso'   =>'eliminar relacion area riesgo');
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

    public function Agregar(Request $request){
        $Id         = trim($request->input('Id'));
        $IdRiesgo   = json_decode($request->input('IdRiesgo'));
        $IdUsuario  = $request->input('IdUsuario');
        
        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion area riesgo');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('IdRiesgo'  =>$IdRiesgo,
                                'Id'        =>$Id,
                                'IdUsuario' =>$IdUsuario);
            
            $resultado = $RiskAreaModel->AgregarRiskAreas($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Quitar(Request $request){
        $IdRelacion  = json_decode($request->input('IdRelacion')); 
        $IdUsuario   = $request->input('IdUsuario');

        $RiskAreaModel = new RiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion area riesgo');
        $permiso = $RiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('IdRelacion' =>$IdRelacion);
            $resultado = $RiskAreaModel->QuitarRiskAreas($parametros);
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
        $resultado = array('Riesgos' => $resultadoR,
                           'Areas'   => $resultadoA);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
