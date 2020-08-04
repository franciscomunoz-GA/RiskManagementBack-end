<?php

namespace App\Http\Controllers;
use App\EncuestaAplicacionModel;
use Illuminate\Http\Request;

class EncuestaAplicacionController extends Controller
{
    public function ServiciosXInspector(Request $request){
        $Datos = $request->json()->all();
        
        $EncuestaAplicacionModel = new EncuestaAplicacionModel;
        $parametros = array('Datos' =>$Datos);
        $resultado = $EncuestaAplicacionModel->ServiciosInspector($parametros);
       
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarEncuesta(Request $request){
        $Datos = $request->json()->all();
        
        $EncuestaAplicacionModel = new EncuestaAplicacionModel;
        $parametros = array('Datos' =>$Datos);
        $resultado = $EncuestaAplicacionModel->SeleccionarEncuesta($parametros);
       
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function ResponderEncuesta(Request $request){
        $Datos = $request->json()->all();
        
        $EncuestaAplicacionModel = new EncuestaAplicacionModel;
        $parametros = array('Datos' =>$Datos);
        $resultado = $EncuestaAplicacionModel->ResponderEncuesta($parametros);
       
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
