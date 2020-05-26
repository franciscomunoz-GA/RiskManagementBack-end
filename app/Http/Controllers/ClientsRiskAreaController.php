<?php

namespace App\Http\Controllers;
use App\ClientsRiskAreaModel;
use App\RiskAreaModel;
use App\CatalogoModel;
use Illuminate\Http\Request;

class ClientsRiskAreaController extends Controller
{
    public function Insertar(Request $request){
        $Nombre    = trim(strtoupper($request->input('Nombre')));
        $IdRelRiskArea  = trim($request->input('IdRelRiskArea'));
        $IdCliente    = trim($request->input('IdCliente'));
        $IdUsuario = $request->input('IdUsuario');
        
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'        =>$Nombre,
                                'IdRelRiskArea' =>$IdRelRiskArea,
                                'IdCliente'     =>$IdCliente,
                                'IdUsuario'     =>$IdUsuario);
            
            $resultado = $ClientsRiskAreaModel->InsertarClientRiskArea($parametros);
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
        $Id            = $request->input('Id');
        $Nombre        = trim(strtoupper($request->input('Nombre')));
        $IdRelRiskArea = trim($request->input('IdRelRiskArea'));
        $IdCliente     = trim($request->input('IdCliente'));
        $IdUsuario     = $request->input('IdUsuario');

        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'editar relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'       =>$Id,
                                'Nombre'   =>$Nombre,
                                'IdRelRiskArea' =>$IdRelRiskArea,
                                'IdCliente'   =>$IdCliente);
            $resultado = $ClientsRiskAreaModel->ModificarClientRiskArea($parametros);
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
        
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$Id,
                                'Accion' =>$Accion);
            $resultado = $ClientsRiskAreaModel->ModificarEstatus($parametros);
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
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $ClientsRiskAreaModel->SeleccionarClientRiskArea();
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
                
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id);
            $resultado = $ClientsRiskAreaModel->SeleccionarDClientRiskArea($parametros);
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
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $ClientsRiskAreaModel->SeleccionarGClientRiskArea();
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
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Datos'     =>$Datos,
                                'IdUsuario' =>$IdUsuario);
            $resultado = $ClientsRiskAreaModel->ImportarClientRiskArea($parametros);
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
        
        $ClientsRiskAreaModel = new ClientsRiskAreaModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion cliente riesgo area');
        $permiso = $ClientsRiskAreaModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id);
            $resultado = $ClientsRiskAreaModel->Eliminar($parametros);
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
        $RiskAreaModel = new RiskAreaModel;
        $CatalogoModel = new CatalogoModel;
        $resultadoR = $RiskAreaModel->SeleccionarGRiskArea();
        $resultadoC = $CatalogoModel->SeleccionarClientG();
        $resultado = array('RiesgosAreas'       => $resultadoR,
                           'Clientes' => $resultadoC);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
