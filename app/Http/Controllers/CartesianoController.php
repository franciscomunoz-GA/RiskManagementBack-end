<?php

namespace App\Http\Controllers;
use App\CartesianoModel;
use Illuminate\Http\Request;

class CartesianoController extends Controller
{
    public function SeleccionarDetalle(Request $request){
        $Id        = $request->input('Id');
        $Tipo      = $request->input('Tipo');
        $IdUsuario = $request->input('IdUsuario');
                
        $CartesianoModel = new CartesianoModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver encuestas');
        $permiso = $CartesianoModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id,
                                'Tipo' =>$Tipo);
            $resultado = $CartesianoModel->SeleccionarDCartesiano($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ModificarRespuesta(Request $request){
        $Datos        = json_decode($request->input('Datos'));
        $Tipo      = $request->input('Tipo');
        $IdUsuario = $request->input('IdUsuario');
                
        $CartesianoModel = new CartesianoModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'modificar encuestas');
        $permiso = $CartesianoModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Datos'     =>$Datos,
                                'Tipo'      =>$Tipo,
                                'IdUsuario' =>$IdUsuario);
            $resultado = $CartesianoModel->ModificarRespuestas($parametros);
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
