<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class RiskAreaModel extends Model
{
    public function InsertarRiskArea($Parametros){
        $Nombre    = $Parametros['Nombre'];
        $IdRiesgo  = $Parametros['IdRiesgo'];
        $IdArea    = $Parametros['IdArea'];
        $IdUsuario = $Parametros['IdUsuario'];
        
        $CondicionN = [
            ['name', '=', $Nombre],
            ['status_delete', '=', 1]
        ];
        $CondicionR = [
            ['risk_id', '=', $IdRiesgo],
            ['area_id', '=', $IdArea],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risk_areas', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_areas', 
                                      'Condicion' => $CondicionR);
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
        $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
        $Duplicidad;
        if(!$DuplicidadR && !$DuplicidadN){
            $Duplicidad = 'Duplicidad';
        }elseif(!$DuplicidadR){
            $Duplicidad = 'Duplicidad Relacion';
        }elseif(!$DuplicidadN){
            $Duplicidad = 'Duplicidad Nombre';
        }else{
            try {
                $Id = DB::table('sysdev.rm_risk_areas')
                        ->insertGetId(['name'    => $Nombre,
                                       'risk_id' => $IdRiesgo,
                                       'area_id' => $IdArea,
                                       'user_id' => $IdUsuario
                                       ]);
                
                return $Id;
            } catch (\Throwable $th) {
                return 'Error al insertar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarRiskArea($Parametros){
        $Id       = $Parametros['Id'];
        $Nombre   = $Parametros['Nombre'];
        $IdRiesgo = $Parametros['IdRiesgo'];
        $IdArea   = $Parametros['IdArea'];
                
        $CondicionN = [
            ['name',          '=', $Nombre],
            ['id',            '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $CondicionR = [
            ['risk_id',      '=', $IdRiesgo],
            ['area_id',      '=', $IdArea],
            ['id',           '!=', $Id],
            ['status_delete','=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risk_areas', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_areas', 
                                      'Condicion' => $CondicionR);
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
        $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
        $Duplicidad;
        if(!$DuplicidadR && !$DuplicidadN){
            $Duplicidad = 'Duplicidad';
        }elseif(!$DuplicidadR){
            $Duplicidad = 'Duplicidad Relacion';
        }elseif(!$DuplicidadN){
            $Duplicidad = 'Duplicidad Nombre';
        }else{
            try {
                $Query = DB::table('sysdev.rm_risk_areas')
                                ->where('id', $Id)
                                ->update([
                                        'name'    => $Nombre,
                                        'risk_id' => $IdRiesgo,
                                        'area_id' => $IdArea
                                        ]);
                return $Query;
            } catch (\Throwable $th) {
                return 'Error al modificar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarEstatus($Parametros){
        $Id     = $Parametros['Id'];
        $Accion = $Parametros['Accion'];
        try {
        $Query = DB::table('sysdev.rm_risk_areas')
                   ->where('id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarRiskArea(){
        $Query = $this->from('sysdev.rm_risk_areas as RA')
                      ->join('sysdev.rm_risks as Risk', 'Risk.id', '=', 'RA.risk_id')
                      ->join('sysdev.rm_areas as Areas', 'Areas.id', '=', 'RA.area_id')
                      ->join('sysdev.users as usuario', 'usuario.id', '=', 'RA.user_id')
                      ->select('RA.id as Id', 
                               'RA.name as Nombre',
                               'Risk.id_risk as RiesgoId',
                               'Risk.name as RiesgoNombre',
                               'Areas.name as Area',
                               'usuario.name as Usuario',
                               DB::raw('DATE_FORMAT(RA.created_at, "%d/%m/%Y %r") as FechaCreacion'),
                               DB::raw('DATE_FORMAT(RA.update_at, "%d/%m/%Y %r") as FechaModificacion'),
                               'RA.status as Estatus'
                               )
                      ->where([['RA.status_delete', '=', 1]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDRiskArea($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_risk_areas AS RA')
                      ->select('RA.id       AS Id', 
                               'RA.name     AS Nombre',
                               'RA.risk_id  AS IdRisk',
                               'RA.area_id  AS IdArea'
                               )                               
                               
                      ->where([['RA.id', '=', $Id]]) 
                      ->get();
        return $Query;
    }

    public function SeleccionarGRiskArea(){
        $Query = $this->from('sysdev.rm_risk_areas AS RA')
                      ->select('RA.id AS Id', 
                               'RA.name AS Nombre'
                               )
                      ->where([['status', '=', 1]]) 
                      ->orderBy('Nombre') 
                      ->get();
        return $Query;
    }

    public function ImportarRiskArea($Parametros){
        $Datos     = $Parametros['Datos'];
        $IdUsuario = $Parametros['IdUsuario'];
        $errores = [];
        $duplicados = [];
        $data = [];
        try {
        foreach ($Datos as $index => $row) {
            $CondicionN = [
                        ['name', '=', trim(strtoupper($row->Nombre))],
                        ['status_delete', '=', 1]
                        ];
            
            $ParametrosR = array('Tabla' => 'sysdev.rm_risks', 
                                  'Texto' => $row->Riesgo);
            $IdR = $this->ObtenerId($ParametrosR);

            $ParametrosA = array(  'Tabla' => 'sysdev.rm_areas', 
                                    'Texto' => $row->Area);
            $IdA = $this->ObtenerId($ParametrosA);
            
            if (empty($IdR) || empty($IdA) ){
                array_push($errores, $row);
            }
            else {
                $CondicionR = [
                            ['risk_id', '=', $IdR],
                            ['area_id', '=', $IdA],
                            ['status_delete', '=', 1]
                            ];
                $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risk_areas', 
                                               'Condicion' => $CondicionN);
                $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_areas', 
                                               'Condicion' => $CondicionR);        
                $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
                $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
                if(!$DuplicidadR || !$DuplicidadN){
                    array_push($duplicados, $row);
                }else{    
                        $Fila =['name'    => trim($row->Nombre),
                                'risk_id' => $IdR,
                                'area_id' => $IdA,
                                'user_id' => $IdUsuario
                            ];
                        array_push($data, $Fila);
                }
            }
        }
        $Query = DB::table('sysdev.rm_risk_areas')
                   ->insert($data);
        $retorno = array(
                        'Dato'       => $Query,
                        'Correctos'  => $data,
                        'Duplicados' => $duplicados,
                        'Errores'    => $errores
                    );
        return $retorno;
        } catch (\Throwable $th) {
            return 'Error al Importar';
        }
    }

    public function Eliminar($Parametros){
        $Id     = $Parametros['Id'];
        try {
        $Query = DB::table('sysdev.rm_risk_areas')
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


    private function ObtenerId($Parametros){
        $Tabla = $Parametros['Tabla'];
        $Texto = $Parametros['Texto'];
       
        try {
            $Query = $this->from($Tabla)
                        ->select('id AS Id')
                        ->where([['name', '=', trim(strtoupper($Texto))],
                                 ['status_delete', '=', 1]]) 
                        ->limit(1)
                        ->get();
            if(empty($Query[0]['Id'])){
                return '';
            }
            else
            {
                return $Query[0]['Id'];
            }
        } catch (\Throwable $th) {
            return '';
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
