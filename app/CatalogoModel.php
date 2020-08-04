<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class CatalogoModel extends Model
{
    public function SeleccionarClientG(){
        $Query = $this->from('sysdev.clients AS Client')
                      ->select('Client.id                AS Id', 
                               'Client.commercial_name   AS Nombre'
                               )
                      ->where([['Client.deleted_at', NULL]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarSiteInterestG(){
        $Query = $this->from('sysdev.sites_of_interest AS SI')
                      ->select('SI.id   AS Id', 
                               'SI.name AS Nombre'
                               )
                      //->where([['Client.deleted_at', NULL]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarInspectores(){
        $Query = $this->from('sysdev.users as usuario')
                      ->join('sysdev.model_has_roles as UsuarioRol','UsuarioRol.model_id','=','usuario.id')
                      ->join('sysdev.roles as rol','rol.id','=','UsuarioRol.role_id')
                      ->select('usuario.id  as Id', 
                               'usuario.name as Nombre'
                               )
                      ->where([['rol.name', '=', 'operador'],
                                ['usuario.deleted_at','=', null]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarClientR(){
        $Query = $this->from('sysdev.rm_clients_risks_areas as CRA')
                      ->join('sysdev.clients as cliente','cliente.id','=','CRA.client_id')
                      ->select('CRA.client_id as Id', 
                               'cliente.commercial_name AS Nombre'
                               )
                      ->where([['CRA.status', '=', 1]])
                      ->distinct()
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarSiteInterestR(){
        $Query = $this->from('sysdev.rm_risk_sites_interest as RSI')
                      ->join('sysdev.sites_of_interest as SI','SI.id','=','RSI.sites_of_interest_id')
                      ->select('RSI.sites_of_interest_id as Id', 
                               'SI.name AS Nombre'
                               )
                      ->where([['RSI.status', '=', 1]])
                      ->distinct()
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }
}
