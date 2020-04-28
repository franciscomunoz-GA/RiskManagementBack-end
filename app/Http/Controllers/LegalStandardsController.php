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
        $parametros = array('Nombre'    =>$nombre,
                            'IdUsuario' =>$idUsuario);
        $resultado = $legalStandardsModel->InsertarLegalStandard($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Modificar(Request $request){
        $id     = $request->input('Id');
        $nombre = trim(strtoupper($request->input('Nombre')));
                
        $legalStandardsModel = new LegalStandardsModel;
        $parametros = array('Id'     =>$id,
                            'Nombre' =>$nombre);
        $resultado = $legalStandardsModel->ModificarLegalStandard($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarEstatus(Request $request){
        $id     = $request->input('Id');
        $accion = $request->input('Accion');
        
        $legalStandardsModel = new LegalStandardsModel;
        $parametros = array('Id'     =>$id,
                            'Accion' =>$accion);
        $resultado = $legalStandardsModel->ModificarEstatus($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Seleccionar(){
        $legalStandardsModel = new LegalStandardsModel;
        $resultado = $legalStandardsModel->SeleccionarLegalStandard();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarDetalle(Request $request){
        $id = $request->input('Id');
                
        $legalStandardsModel = new LegalStandardsModel;
        $parametros = array('Id' =>$id);
        $resultado = $legalStandardsModel->SeleccionarDLegalStandard($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Importador(Request $request){
        $nombres   = json_decode($request->input('Nombres'));
        $idUsuario = $request->input('IdUsuario');
        $legalStandardsModel = new LegalStandardsModel;
        $parametros = array('Nombres'   =>$nombres,
                            'IdUsuario' =>$idUsuario);
        $resultado = $legalStandardsModel->ImportarLegalStandard($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Eliminar(Request $request){
        $id = $request->input('Id');
        
        $legalStandardsModel = new LegalStandardsModel;
        $parametros = array('Id'     =>$id);
        $resultado = $legalStandardsModel->Eliminar($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
