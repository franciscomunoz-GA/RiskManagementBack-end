<?php

namespace App\Http\Controllers;
use App\RiskModel;
use App\CatalogoModel;
use App\RiskSiteInterestModel;
use Illuminate\Http\Request;

class RiskSiteInterestController extends Controller
{
    public function Insertar(Request $request){
        $IdSitioInteres = trim($request->input('IdSitioInteres'));
        $IdRiesgo       = json_decode($request->input('IdRiesgo'));
        $IdUsuario      = $request->input('IdUsuario');
        
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('IdRiesgo'       =>$IdRiesgo,
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
        $IdSitioInteres = trim($request->input('IdSitioInteres'));
        $Id             = json_decode($request->input('Id'));
        $IdUsuario      = $request->input('IdUsuario');

        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'editar relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('Id'             =>$Id,
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
        $Id          = $request->input('Id'); //Id Sitio interes
        $Accion      = $request->input('Accion');
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
        $Id        = $request->input('Id'); //Id Sitio interes
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
            $SitiosInteres = $RiskSiteInterestModel->SeleccionarGRiskSitesInterest();
            $resultado = array('SitiosInteres' => $SitiosInteres);
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
        $Id        = $request->input('Id'); //Id Sitio Interes
        $IdUsuario = $request->input('IdUsuario');
        
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

    public function Agregar(Request $request){
        $IdSitioInteres = trim($request->input('IdSitioInteres'));
        $IdRiesgo       = json_decode($request->input('IdRiesgo'));
        $IdUsuario      = $request->input('IdUsuario');
        
        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'crear relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('IdRiesgo'       =>$IdRiesgo,
                                'IdSitioInteres' =>$IdSitioInteres,
                                'IdUsuario'      =>$IdUsuario);
            
            $resultado = $RiskSiteInterestModel->AgregarRiskSitesInterest($parametros);
        }
        else {
            $resultado = $permiso;
        }
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function Quitar(Request $request){
        $IdRelacion  = json_decode($request->input('IdRelacion')); 
        $IdUsuario   = $request->input('IdUsuario');

        $RiskSiteInterestModel = new RiskSiteInterestModel;
        $parametrosP = array('IdUsuario' =>$IdUsuario,
                             'Permiso'   =>'eliminar relacion riesgo sitio interes');
        $permiso = $RiskSiteInterestModel->ValidarPermiso($parametrosP);
        if($permiso>0){
            $parametros = array('IdRelacion' =>$IdRelacion);
            $resultado = $RiskSiteInterestModel->QuitarRiskSitesInterest($parametros);
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
