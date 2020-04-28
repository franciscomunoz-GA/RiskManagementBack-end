<?php

namespace App;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class loginModel extends Model
{
    public function validarUsuario($parametros){
        $correo = $parametros['Correo'];
        try {
            $Count = DB::table('sysdev.users')
                    ->where([['email',$correo],
                            ['deleted_at',  null]])
                    ->count();
            if($Count > 0){
                $Resultado = true;
            }
            else{
                $Resultado = false;
            }
            return $Resultado;
        } catch (\Throwable $th) {
            return 'Error al validar Usuario';
        }
    }

    public function sesionUsuario($parametros){
        $correo   = $parametros['Correo'];
        $password = $parametros['Password'];
        try {
            $passwordHash = $this->from('sysdev.users')
                            ->select('id       AS Id',
                                     'name     AS Nombre',
                                     'password AS Password')
                            ->where([['email', '=', $correo],
                                     ['deleted_at',  null]])
                            ->orderBy('created_at', 'desc')
                            ->get();
            $data=[];
            foreach ($passwordHash as $index => $row){
                if (Hash::check($password, $row['Password']))
                {
                    $Fila  = array('Id'     => $row->Id,
                                   'Nombre' => $row->Nombre);
                    array_push($data, $Fila);
                    return $data;
                    break;
                }
            }
            return false;
        } catch (\Throwable $th) {
        return 'Error al validar Usuario';
        }
    }
}
