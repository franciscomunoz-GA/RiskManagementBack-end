<?php

namespace App\Http\Controllers;
use App\RiskModel;
use App\CatalogoModel;
use App\RiskSiteInterestModel;
use Illuminate\Http\Request;

class RiskSiteInterestController extends Controller
{
    public function Insertar(Request $request){
        $Nombre         = trim(strtoupper($request->input('Nombre')));
        $IdRiesgo       = trim($request->input('IdRiesgo'));
        $IdSitioInteres = trim($request->input('IdSitioInteres'));
        $IdUsuario      = $request->input('IdUsuario');
        
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Nombre'         =>$Nombre,
                                'IdRiesgo'       =>$IdRiesgo,
                                'IdSitioInteres' =>$IdSitioInteres,
                                'IdUsuario'      =>$IdUsuario);
            
            $resultado = $RiskSiteInterestModel->InsertarRiskSitesInterest($parametros);
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
        $Id          = $request->input('Id');
        $Nombre         = trim(strtoupper($request->input('Nombre')));
        $IdRiesgo       = trim($request->input('IdRiesgo'));
        $IdSitioInteres = trim($request->input('IdSitioInteres'));
        $IdUsuario      = $request->input('IdUsuario');

        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'editar relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'             =>$Id,
                                'Nombre'         =>$Nombre,
                                'IdRiesgo'       =>$IdRiesgo,
                                'IdSitioInteres' =>$IdSitioInteres);
            $resultado = $RiskSiteInterestModel->ModificarRiskSitesInterest($parametros);
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
        $Id     = $request->input('Id');
        $Accion = $request->input('Accion');
        $IdUsuario   = $request->input('IdUsuario');
        
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'     =>$Id,
                                'Accion' =>$Accion);
            $resultado = $RiskSiteInterestModel->ModificarEstatus($parametros);
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
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $RiskSiteInterestModel->SeleccionarRiskSitesInterest();
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
        $Id        = $request->input('Id');
        $IdUsuario = $request->input('IdUsuario');
                
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id);
            $resultado = $RiskSiteInterestModel->SeleccionarDRiskSitesInterest($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarGeneral(Request $request){
        $IdUsuario   = $request->input('IdUsuario');
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'ver relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $resultado = $RiskSiteInterestModel->SeleccionarGRiskSitesInterest();
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
        $Datos   = json_decode($request->input('Datos'));
        $IdUsuario = $request->input('IdUsuario');
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Datos'     =>$Datos,
                                'IdUsuario' =>$IdUsuario);
            $resultado = $RiskSiteInterestModel->ImportarRiskSitesInterest($parametros);
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
        $Id = $request->input('Id');
        $IdUsuario   = $request->input('IdUsuario');
        
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id' =>$Id);
            $resultado = $RiskSiteInterestModel->Eliminar($parametros);
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
        $RiskModel     = new RiskModel;
        $CatalogoModel = new CatalogoModel;
        $resultadoR = $RiskModel->SeleccionarGRisk();
        $resultadoC = $CatalogoModel->SeleccionarSiteInterestG();
        $resultado = array('Riesgos'       => $resultadoR,
                           'SitiosInteres' => $resultadoC);
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
