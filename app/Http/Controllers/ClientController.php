<?php

namespace App\Http\Controllers;
use App\ClientModel;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function SeleccionarG(){
        $clientModel = new ClientModel;
        $resultado = $clientModel->SeleccionarClientG();
        $retono = array('Success' => 'true',
                        'Message' => 'Consulta Exitosa',
                        'Data'    => $resultado);
        return $retono;
    }
}
