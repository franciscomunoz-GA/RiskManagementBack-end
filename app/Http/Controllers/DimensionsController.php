<?php

namespace App\Http\Controllers;
use App\DimensionsModel;
use Illuminate\Http\Request;

class DimensionsController extends Controller
{
    public function Insertar(Request $request){
        $nombre    = trim(strtoupper($request->input('Nombre')));
        $idUsuario = $request->input('IdUsuario');
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'crear dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'              =>$nombre,
                                'IdUsuario'           =>$idUsuario);
            $resultado = $dimensionsModel->InsertarDimension($parametros);
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
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'editar dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Nombre' =>$nombre);
            $resultado = $dimensionsModel->ModificarDimension($parametros);
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
        $id        = $request->input('Id');
        $accion    = $request->input('Accion');
        $idUsuario = $request->input('IdUsuario');
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'eliminar dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Accion' =>$accion);
            $resultado = $dimensionsModel->ModificarEstatus($parametros);
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
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $dimensionsModel->SeleccionarDimensions();
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
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'ver dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$id);
            $resultado = $dimensionsModel->SeleccionarDDimension($parametros);
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
        $nombres = json_decode($request->input('Nombres'));
        $idUsuario  = $request->input('IdUsuario');
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'crear dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombres'   =>$nombres,
                                'IdUsuario' =>$idUsuario);
            $resultado = $dimensionsModel->ImportarDimension($parametros);
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
        $dimensionsModel = new DimensionsModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                             'Permiso'   =>'eliminar dimensiones');
        $permiso = $dimensionsModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id);
            $resultado = $dimensionsModel->Eliminar($parametros);
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
