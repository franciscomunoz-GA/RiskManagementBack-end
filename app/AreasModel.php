<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class AreasModel extends Model
{
    public function InsertarAreas($Parametros){
        $Nombre    = $Parametros['Nombre'];
        $IdUsuario = $Parametros['IdUsuario'];        
        
        $Condicion = [
            ['name', '=', $Nombre],
            ['status_delete',   '=', 1]
        ];
        $ParametrosDuplicidad = array('Tabla'     => 'sysdev.rm_areas', 
                                      'Condicion' => $Condicion);
        $Duplicidad = $this->ValidarDuplicidad($ParametrosDuplicidad);

        switch($Duplicidad){
            case true:
                try {
                    $Id = DB::table('sysdev.rm_areas')
                            ->insertGetId(['name'    => $Nombre,
                                           'user_id' => $IdUsuario
                                          ]);
                    
                    return $Id;
                } catch (\Throwable $th) {
                    return 'Error al insertar';
                }                
                break;
            case false:
                return "Duplicado";
                break;
            default:
                return $Duplicidad;
                break;            
        }
    }

    public function ModificarAreas($Parametros){
        $Id     = $Parametros['Id'];
        $Nombre = $Parametros['Nombre'];
        
        $Condicion = [
            ['name', '=', $Nombre],
            ['id',   '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidad = array('Tabla'     => 'sysdev.rm_areas', 
                                      'Condicion' => $Condicion);
        $Duplicidad = $this->ValidarDuplicidad($ParametrosDuplicidad);

        switch($Duplicidad){
            case true:
                try {
                    $Query = DB::table('sysdev.rm_areas')
                                ->where('id', $Id)
                                ->update([
                                        'name' => $Nombre
                                        ]);
                    return $Query;
                    
                } catch (\Throwable $th) {
                    return 'Error al modificar';
                }                
                break;
            case false:
                return "Duplicado";
                break;
            default:
                return $Duplicidad;
                break;            
        }
    }

    public function ModificarEstatus($Parametros){
        $Id     = $Parametros['Id'];
        $Accion = $Parametros['Accion'];
        try {
        $Query = DB::table('sysdev.rm_areas')
                   ->where('id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarAreas(){
        $Query = $this->from('sysdev.rm_areas AS areas')
                      ->join('sysdev.users AS Usuario', 'Usuario.id', '=', 'areas.user_id')
                      ->select('areas.id        AS Id', 
                               'areas.name      AS Nombre',
                               'Usuario.name AS Usuario',
                               DB::raw('DATE_FORMAT(areas.created_at, "%d/%m/%Y %r") as FechaCreacion'),
                               DB::raw('DATE_FORMAT(areas.update_at, "%d/%m/%Y %r") as FechaModificacion'),
                               'areas.status    AS Estatus'
                               )
                      ->where([['areas.status_delete', '=', 1]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDAreas($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_areas AS areas')
                      ->select('areas.id   AS Id', 
                               'areas.name AS Nombre'
                               )
                      ->where([['areas.id', '=', $Id]]) 
                      ->get();
        return $Query;
    }

    public function SeleccionarGAreas(){
        $Query = $this->from('sysdev.rm_areas AS areas')
                      ->select('areas.id   AS Id', 
                               'areas.name AS Nombre'
                               )
                      ->where([['areas.status', '=', 1]]) 
                      ->orderBy('Nombre') 
                      ->get();
        return $Query;
    }

    public function ImportarAreas($Parametros){
        $Nombres   = $Parametros['Nombres'];
        $IdUsuario = $Parametros['IdUsuario'];
        try {
        $duplicados = 0;
        $errores = 0;
        $data = [];
        foreach ($Nombres as $index => $row) {
            $Condicion = [
                        ['name', '=', trim(strtoupper($row))],
                        ['status_delete', '=', 1]
                        ];
            $ParametrosDuplicidad = array('Tabla'     => 'sysdev.rm_areas', 
                                          'Condicion' => $Condicion);
            $Duplicidad = $this->ValidarDuplicidad($ParametrosDuplicidad);
    
            switch($Duplicidad){
                case true:
                    $Fila =['name'    => trim(strtoupper($row)),
                            'user_id' => $IdUsuario
                           ];
                    array_push($data, $Fila);                
                    break;
                case false:
                    $duplicados ++;
                    break;
                default:
                    $errores = 0;
                    break;            
            }
        }
        $Query = DB::table('sysdev.rm_areas')
                   ->insert($data);
        return $Query;
        } catch (\Throwable $th) {
            return 'Error al Importar';
        }
    }

    public function Eliminar($Parametros){
        $Id     = $Parametros['Id'];
        try {
        $Query = DB::table('sysdev.rm_areas')
                   ->where('id', $Id)
                   ->update([
                            'status' => 0,
                            'status_delete'=> 0
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Eliminar';
        }
    }

    private function ValidarDuplicidad($Parametros){
        $Tabla     = $Parametros['Tabla'];
        $Condicion = $Parametros['Condicion'];        
        try {
            $Count = DB::table($Tabla)
                    ->where($Condicion)
                    ->count();
            if($Count > 0){
                $Resultado = false;
            }
            else{
                $Resultado = true;
            }
            return $Resultado;
        } catch (\Throwable $th) {
            return 'Error al validar duplicidad';
        }        
    }

    public function ValidarPermiso($Parametros){
        $IdUsuario = $Parametros['IdUsuario'];
        $Permiso   = $Parametros['Permiso'];        
        try {
            $Count = DB::table('sysdev.model_has_roles as model')
                       ->join('sysdev.roles as roles', 'model.role_id', '=', 'roles.id')
                       ->join('sysdev.role_has_permissions as relRolPermiso', 'relRolPermiso.role_id', '=', 'roles.id')
                       ->join('sysdev.permissions as permiso', 'permiso.id', '=', 'relRolPermiso.permission_id')
                    ->where([['model_id',     '=', $IdUsuario],
                             ['permiso.name', '=', $Permiso]])
                    ->count();
            if($Count > 0)
                return $Count;
            else    
                return 'No tiene permiso';
        } catch (\Throwable $th) {
            return 'Error al validar permiso';
        }        
    }
}
