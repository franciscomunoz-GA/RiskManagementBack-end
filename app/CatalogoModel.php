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
}
