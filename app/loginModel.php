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
                    $sesion =  $this->from('sysdev.rm_users_sessions')
                                    ->where([['user_id', '=', $row->Id],
                                            ['status_session',  1]])
                                    ->count();
                    if($sesion > 0) {
                        $tiempo =  $this->from('sysdev.rm_users_sessions')
                                    ->select(DB::raw('timestampdiff(minute,update_at,current_timestamp()) as Diferencia'))
                                    ->where([['user_id', '=', $row->Id],
                                            ['status_session',  1]])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(1)
                                    ->get();
                        if($tiempo[0]['Diferencia']>5){
                            $Query = DB::table('sysdev.rm_users_sessions')
                                        ->where([['user_id', $row->Id],
                                                ['status_session', 1]])
                                        ->update(['status_session' => 0 ]);
                            $Fila  = array('Id'     => $row->Id,
                                           'Nombre' => $row->Nombre,
                                           'Sesion' => 'NUEVA');
                            array_push($data, $Fila);
                            $Id = DB::table('sysdev.rm_users_sessions')
                                    ->insertGetId(['user_id'    => $row->Id,
                                                'update_at' => date('Y-m-d H:i:s') 
                                                ]);
                        }
                        else{
                            $Fila  = array('Id'     => $row->Id,
                                           'Nombre' => $row->Nombre,
                                           'Sesion' => 'Sesión activa');
                            array_push($data, $Fila);
                        }
                            
                    }
                    else{
                        $Fila  = array('Id'     => $row->Id,
                                       'Nombre' => $row->Nombre,
                                       'Sesion' => 'NUEVA');
                                array_push($data, $Fila);
                        $Id = DB::table('sysdev.rm_users_sessions')
                                ->insertGetId(['user_id'    => $row->Id,
                                               'update_at' => date('Y-m-d H:i:s') 
                                               ]);

                    }
                    
                    return $data;
                    break;
                }
            }
            return false;
        } catch (\Throwable $th) {
        return 'Error al validar Usuario';
        }
    }

    public function ObtenerPermisos($parametros){
        $IdUsuario = $parametros['IdUsuario'];
        $Query = DB::table('sysdev.model_has_roles as modelRoles')
                      ->join('sysdev.roles as roles', 'roles.id', '=', 'modelRoles.role_id')
                      ->join('sysdev.role_has_permissions as rolPermission', 'rolPermission.role_id', '=', 'roles.id')
                      ->join('sysdev.permissions as permission', 'permission.id', '=', 'rolPermission.permission_id')
                      ->select('permission.name as permiso')
                      ->where([['modelRoles.model_id', '=', $IdUsuario]]) 
                      ->orderBy('permission.name') 
                      ->get();
        $data = [];
        foreach ($Query as $index => $row) {
            $data[$index] = $row->permiso;
        }
                      
        return $data;
    }

    public function ActualizarSesion($parametros){
        $IdUsuario = $parametros['IdUsuario'];
        try {
            $Query = DB::table('sysdev.rm_users_sessions')
            ->where([['user_id', $IdUsuario],
                    ['status_session', 1]])
            ->update([
                     'update_at' => date('Y-m-d H:i:s') 
                    ]);
     return $Query;
        } catch (\Throwable $th) {
            return 'Error al actualizar sesion' . $th;
        }
    }

    public function CerrarSesion($parametros){
        $IdUsuario = $parametros['IdUsuario'];
        try {
            $Query = DB::table('sysdev.rm_users_sessions')
            ->where([['user_id', $IdUsuario],
                    ['status_session', 1]])
            ->update(['update_at' => date('Y-m-d H:i:s'),
                      'status_session'=> 0]);
     return $Query;
        } catch (\Throwable $th) {
            return 'Error al cerrar la sesion' . $th;
        }
    }
}
