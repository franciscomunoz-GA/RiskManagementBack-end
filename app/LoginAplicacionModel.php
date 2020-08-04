<?php

namespace App;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class LoginAplicacionModel extends Model
{
    public function sesionUsuario($parametros){
        $Datos = $parametros['Datos'];
        try {
            $passwordHash = $this->from('sysdev.users as usuario')
                                ->join('sysdev.model_has_roles as relacion','relacion.model_id', '=', 'usuario.id')
                                ->join('sysdev.roles as rol','relacion.role_id', '=', 'rol.id')
                                ->select('usuario.id              AS Id',
                                         'usuario.name            AS Nombre',
                                         'usuario.password        AS Password',
                                         'usuario.client_id       AS IdCliente',
                                         'usuario.emergency_phone AS Telefono')
                                ->where([['usuario.email', '=', $Datos['Correo']],
                                        ['usuario.deleted_at',  null],
                                        ['rol.name',  'Inspector Risk Management']])
                                ->orderBy('usuario.created_at', 'desc')
                                ->limit(1)
                                ->get();
            $data=[];
            foreach ($passwordHash as $index => $row){
                if (Hash::check($Datos['Password'], $row->Password))
                {
                    $Imei = $this->from('sysdev.client_phones')
                                  ->select('id        AS Id',
                                           'imei      AS Imei')
                                  ->where([['client_id', '=', $row->IdCliente],
                                           ['phone_number','=', $row->Telefono]])
                                  ->limit(1)
                                  ->get();
                                       
                   if(!empty($Imei[0]->Id)) {
                       if($Imei[0]->Imei == null){
                            $Query = DB::table('sysdev.client_phones')
                                        ->where('id', $Imei[0]->Id)
                                        ->update(['imei' => $Datos['Imei']]);
                            
                            $Fila  = array('Id'       => $row->Id,
                                           'Nombre'   => $row->Nombre,
                                           'Telefono' => $row->Telefono);
                            array_push($data, $Fila);
                       }elseif($Imei[0]->Imei == $Datos['Imei']){
                            $Fila  = array('Id'       => $row->Id,
                                           'Nombre'   => $row->Nombre,
                                           'Telefono' => $row->Telefono);
                            array_push($data, $Fila);
                        }else{
                            array_push($data,  'IMEI incorrecto');
                        }
                   } 
                   else{
                       array_push($data, 'Sin permiso');
                   }                  
                }
                else{
                    array_push($data, 'Contraseña incorrecta');
                }
            }
            if(empty($data)){array_push($data, 'Usuario incorrecto');}
            return $data[0];
        } catch (\Throwable $th) {
        return 'Error al validar Usuario';
        }
    }
}
