<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class RiskTypesModel extends Model
{
    public function InsertarRiskType($Parametros){
        $Nombre    = $Parametros['Nombre'];
        $IdUsuario = $Parametros['IdUsuario'];        
        
        $Condicion = [
            ['name', '=', $Nombre],
            ['status_delete',   '=', 1]
        ];
        $ParametrosDuplicidad = array('Tabla'     => 'sysdev.risk_types', 
                                      'Condicion' => $Condicion);
        $Duplicidad = $this->ValidarDuplicidad($ParametrosDuplicidad);

        switch($Duplicidad){
            case true:
                try {
                    $Id = DB::table('sysdev.risk_types')
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

    public function ModificarRiskType($Parametros){
        $Id     = $Parametros['Id'];
        $Nombre = $Parametros['Nombre'];
        
        $Condicion = [
            ['name', '=', $Nombre],
            ['id',   '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidad = array('Tabla'     => 'sysdev.risk_types', 
                                      'Condicion' => $Condicion);
        $Duplicidad = $this->ValidarDuplicidad($ParametrosDuplicidad);

        switch($Duplicidad){
            case true:
                try {
                    $Query = DB::table('sysdev.risk_types')
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
        $Query = DB::table('sysdev.risk_types')
                   ->where('id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarRiskTypes(){
        $Query = $this->from('sysdev.risk_types AS RT')
                      ->join('sysdev.users AS Usuario', 'Usuario.id', '=', 'RT.user_id')
                      ->select('RT.id        AS Id', 
                               'RT.name      AS Nombre',
                               'Usuario.name AS Usuario',
                               DB::raw('DATE_FORMAT(RT.created_at, "%d/%m/%Y %r") as FechaCreacion'),
                               DB::raw('DATE_FORMAT(RT.update_at, "%d/%m/%Y %r") as FechaModificacion'),
                               'RT.status    AS Estatus'
                               )
                      ->where([['RT.status_delete', '=', 1]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDRiskTypes($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.risk_types AS RT')
                      ->select('RT.id   AS Id', 
                               'RT.name AS Nombre'
                               )
                      ->where([['RT.id', '=', $Id]]) 
                      ->get();
        return $Query;
    }

    public function SeleccionarGRiskTypes(){
        $Query = $this->from('sysdev.risk_types AS RT')
                      ->select('RT.id   AS Id', 
                               'RT.name AS Nombre'
                               )
                      ->where([['RT.status', '=', 1]]) 
                      ->orderBy('Nombre') 
                      ->get();
        return $Query;
    }

    public function ImportarRiskType($Parametros){
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
            $ParametrosDuplicidad = array('Tabla'     => 'sysdev.risk_types', 
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
        $Query = DB::table('sysdev.risk_types')
                   ->insert($data);
        return $Query;
        } catch (\Throwable $th) {
            return 'Error al Importar';
        }
    }

    public function Eliminar($Parametros){
        $Id     = $Parametros['Id'];
        try {
        $Query = DB::table('sysdev.risk_types')
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
}
