<?php

namespace App\Http\Controllers;
use App\CatalogoModel;
use Illuminate\Http\Request;

class CatalogosController extends Controller
{
    public function SeleccionarClientG(){
        $catalogoModel = new CatalogoModel;
        $resultado = $catalogoModel->SeleccionarClientG();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

    public function SeleccionarSitesInterestG(){
        $catalogoModel = new CatalogoModel;
        $resultado = $catalogoModel->SeleccionarSiteInterestG();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }

}
