<?php

namespace App\Http\Controllers;
use App\LegalStandardsModel;
use Illuminate\Http\Request;

class LegalStandardsController extends Controller
{
    public function Insertar(Request $request){
        $nombre    = trim(strtoupper($request->input('Nombre')));
        $idUsuario = $request->input('IdUsuario');
        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'crear estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'    =>$nombre,
                                'IdUsuario' =>$idUsuario);
            $resultado = $legalStandardsModel->InsertarLegalStandard($parametros);
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
                
        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'editar estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Nombre' =>$nombre);
            $resultado = $legalStandardsModel->ModificarLegalStandard($parametros);
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

        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'eliminar estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Accion' =>$accion);
            $resultado = $legalStandardsModel->ModificarEstatus($parametros);
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
        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $legalStandardsModel->SeleccionarLegalStandard();
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
                
        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$id);
            $resultado = $legalStandardsModel->SeleccionarDLegalStandard($parametros);
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
        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'crear estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombres'   =>$nombres,
                                'IdUsuario' =>$idUsuario);
            $resultado = $legalStandardsModel->ImportarLegalStandard($parametros);
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
        $idUsuario = $request->input('IdUsuario');
        
        $legalStandardsModel = new LegalStandardsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'eliminar estandares legales');
        $permiso = $legalStandardsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id);
            $resultado = $legalStandardsModel->Eliminar($parametros);
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
