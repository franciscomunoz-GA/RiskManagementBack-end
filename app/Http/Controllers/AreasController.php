<?php

namespace App\Http\Controllers;
use App\AreasModel;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function Insertar(Request $request){
        $nombre    = trim(strtoupper($request->input('Nombre')));
        $idUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'crear area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'    =>$nombre,
                                'IdUsuario' =>$idUsuario);
            $resultado = $areasModel->InsertarAreas($parametros);
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
        $IdUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'editar area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){        
            $parametros = array('Id'     =>$id,
                                'Nombre' =>$nombre);
            $resultado = $areasModel->ModificarAreas($parametros);
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
        $IdUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id,
                                'Accion' =>$accion);
            $resultado = $areasModel->ModificarEstatus($parametros);
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
        $IdUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                            'Permiso'   =>'ver area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $areasModel->SeleccionarAreas();
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
        $IdUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$id);
            $resultado = $areasModel->SeleccionarDAreas($parametros);
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
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$idUsuario,
                            'Permiso'   =>'crear area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombres'   =>$nombres,
                                'IdUsuario' =>$idUsuario);
            $resultado = $areasModel->ImportarAreas($parametros);
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
        $IdUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                            'Permiso'   =>'eliminar area');
        $permiso = $areasModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$id);
            $resultado = $areasModel->Eliminar($parametros);
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
