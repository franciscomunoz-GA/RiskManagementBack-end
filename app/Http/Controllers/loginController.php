<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\loginModel;

class loginController extends Controller
{

    public function validarUsuario(Request $request){
        $correo = $request->input('Correo');
        $loginModel = new loginModel;
        $parametros = array('Correo'=>$correo);
        $resultado = $loginModel->validarUsuario($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function sesionUsuario(Request $request){
        $correo   = $request->input('Correo');
        $password = $request->input('Password');
        $loginModel = new loginModel;
        $parametros = array('Correo'=>$correo,
                            'Password'=>$password);
        $resultado = $loginModel->sesionUsuario($parametros);
        
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
} 
