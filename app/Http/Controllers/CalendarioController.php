<?php

namespace App\Http\Controllers;
use App\CalendarioModel;
use App\CatalogoModel;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function Insertar(Request $request){
        $Inspector = trim($request->input('IdInspector'));
        $Fecha     = trim($request->input('Fecha'));
        $Tipo      = trim($request->input('Tipo'));
        $Id        = trim($request->input('Id'));
        $IdUsuario = $request->input('IdUsuario');

        $hoy = getdate();
        $Folio = $hoy['year'].str_pad($hoy['mon'], 2, "0", STR_PAD_LEFT).str_pad($hoy['mday'], 2, "0", STR_PAD_LEFT).str_pad($hoy['hours'], 2, "0", STR_PAD_LEFT).str_pad($hoy['minutes'], 2, "0", STR_PAD_LEFT).str_pad($hoy['seconds'], 2, "0", STR_PAD_LEFT);
        
        
        $CalendarioModel = new CalendarioModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear calendario');
        $permiso = $CalendarioModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Inspector' =>$Inspector,
                                'Folio'     =>$Folio,
                                'Fecha'     =>$Fecha,
                                'Tipo'      =>$Tipo,
                                'Id'        =>$Id,
                                'IdUsuario' =>$IdUsuario);
            
            $resultado = $CalendarioModel->InsertarCalendario($parametros);
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
        $IdUsuario   = $request->input('IdUsuario');
        $CalendarioModel = new CalendarioModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver calendario');
        $permiso = $CalendarioModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $CalendarioModel->SeleccionarCalendario();
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Catalogos(){
        $CatalogoModel         = new CatalogoModel;
        $resultadoCRA = $CatalogoModel->SeleccionarClientR();
        $resultadoRSI = $CatalogoModel->SeleccionarSiteInterestR();
        $resultadoI   = $CatalogoModel->SeleccionarInspectores();
        $resultado = array('ClientesRA'  => $resultadoCRA,
                           'SInteresR'   => $resultadoRSI,
                           'Inspectores' =>  $resultadoI );
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
