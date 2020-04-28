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
        $parametros = array('Nombre'    =>$nombre,
                            'IdUsuario' =>$idUsuario);
        $resultado = $areasModel->InsertarAreas($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Modificar(Request $request){
        $id     = $request->input('Id');
        $nombre = trim(strtoupper($request->input('Nombre')));
                
        $areasModel = new AreasModel;
        $parametros = array('Id'     =>$id,
                            'Nombre' =>$nombre);
        $resultado = $areasModel->ModificarAreas($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarEstatus(Request $request){
        $id     = $request->input('Id');
        $accion = $request->input('Accion');
        
        $areasModel = new AreasModel;
        $parametros = array('Id'     =>$id,
                            'Accion' =>$accion);
        $resultado = $areasModel->ModificarEstatus($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Seleccionar(){
        $areasModel = new AreasModel;
        $resultado = $areasModel->SeleccionarAreas();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarDetalle(Request $request){
        $id = $request->input('Id');
                
        $areasModel = new AreasModel;
        $parametros = array('Id' =>$id);
        $resultado = $areasModel->SeleccionarDAreas($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Importador(Request $request){
        $nombres   = json_decode($request->input('Nombres'));
        $idUsuario = $request->input('IdUsuario');
        $areasModel = new AreasModel;
        $parametros = array('Nombres'   =>$nombres,
                            'IdUsuario' =>$idUsuario);
        $resultado = $areasModel->ImportarAreas($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        
        $areasModel = new AreasModel;
        $parametros = array('Id'     =>$id);
        $resultado = $areasModel->Eliminar($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
