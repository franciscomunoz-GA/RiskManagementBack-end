<?php

namespace App\Http\Controllers;

use App\LoginAplicacionModel;
use Illuminate\Http\Request;

class LoginAplicacionController extends Controller
{
    public function SesionUsuario(Request $request){
        $Datos = $request->json()->all();
        
        $LoginAplicacionModel = new LoginAplicacionModel;
        $parametros = array('Datos' =>$Datos);
        $resultado = $LoginAplicacionModel->sesionUsuario($parametros);
       
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
