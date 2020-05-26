<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ClientModel extends Model
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
}
