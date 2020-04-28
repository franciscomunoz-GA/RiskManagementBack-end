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
        $parametros = array('Nombre'              =>$nombre,
                            'IdUsuario'           =>$idUsuario);
        $resultado = $dimensionsModel->InsertarDimension($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Modificar(Request $request){
        $id     = $request->input('Id');
        $nombre = trim(strtoupper($request->input('Nombre')));
                
        $dimensionsModel = new DimensionsModel;
        $parametros = array('Id'     =>$id,
                            'Nombre' =>$nombre);
        $resultado = $dimensionsModel->ModificarDimension($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarEstatus(Request $request){
        $id        = $request->input('Id');
        $accion    = $request->input('Accion');
        
        $dimensionsModel = new DimensionsModel;
        $parametros = array('Id'     =>$id,
                            'Accion' =>$accion);
        $resultado = $dimensionsModel->ModificarEstatus($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Seleccionar(){
        $dimensionsModel = new DimensionsModel;
        $resultado = $dimensionsModel->SeleccionarDimensions();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarDetalle(Request $request){
        $id = $request->input('Id');
                
        $dimensionsModel = new DimensionsModel;
        $parametros = array('Id' =>$id);
        $resultado = $dimensionsModel->SeleccionarDDimension($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Importador(Request $request){
        $nombres = json_decode($request->input('Nombres'));
        $idUsuario  = $request->input('IdUsuario');
        $dimensionsModel = new DimensionsModel;
        $parametros = array('Nombres'   =>$nombres,
                            'IdUsuario' =>$idUsuario);
        $resultado = $dimensionsModel->ImportarDimension($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        
        $dimensionsModel = new DimensionsModel;
        $parametros = array('Id'     =>$id);
        $resultado = $dimensionsModel->Eliminar($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
