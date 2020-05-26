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
        $parametrosS = array('Correo'=>$correo,
                            'Password'=>$password);
        $session = $loginModel->sesionUsuario($parametrosS);
        $parametrosP = array('IdUsuario'=>$session[0]['Id']);
        $permisos = $loginModel->ObtenerPermisos($parametrosP);
        $resultado = array('Sesion'    => $session,
                           'Permisos'  => $permisos);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function AcutalizarSesion(Request $request){
        $IdUsuario = $request->input('IdUsuario');
        $loginModel = new loginModel;
        $parametros = array('IdUsuario'=>$IdUsuario);
        $resultado = $loginModel->ActualizarSesion($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function CerrarSesion(Request $request){
        $IdUsuario = $request->input('IdUsuario');
        $loginModel = new loginModel;
        $parametros = array('IdUsuario'=>$IdUsuario);
        $resultado = $loginModel->CerrarSesion($parametros);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
} 
