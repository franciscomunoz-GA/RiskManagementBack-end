<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class RiskModel extends Model
{
    public function InsertarRisk($Parametros){
        $IdRiesgo    = $Parametros['IdRiesgo'];
        $Nombre      = $Parametros['Nombre'];
        $IdDimension = $Parametros['IdDimension'];
        $IdTRiesgo   = $Parametros['IdTRiesgo'];
        $IdCLegales  = $Parametros['IdCLegales'];
        $IdUsuario   = $Parametros['IdUsuario'];       
        
        $CondicionN = [
            ['name', '=', $Nombre],
            ['status_delete', '=', 1]
        ];
        $CondicionI = [
            ['id_risk', '=', $IdRiesgo],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risks', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadI = array('Tabla'     => 'sysdev.rm_risks', 
                                      'Condicion' => $CondicionI);
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
        $DuplicidadI = $this->ValidarDuplicidad($ParametrosDuplicidadI);
        $Duplicidad;
        if(!$DuplicidadI && !$DuplicidadN){
            $Duplicidad = 'Duplicidad';
        }elseif(!$DuplicidadI){
            $Duplicidad = 'Duplicidad Id Riesgo';
        }elseif(!$DuplicidadN){
            $Duplicidad = 'Duplicidad Nombre';
        }else{
            try {
                $Id = DB::table('sysdev.rm_risks')
                        ->insertGetId(['id_risk'           => $IdRiesgo,
                                       'name'              => $Nombre,
                                       'dimension_id'      => $IdDimension,
                                       'risk_type_id'      => $IdTRiesgo,
                                       'legal_standard_id' => $IdCLegales,
                                       'user_id'           => $IdUsuario
                                      ]);
                
                return $Id;
            } catch (\Throwable $th) {
                return 'Error al insertar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarRisk($Parametros){
        $Id          = $Parametros['Id'];
        $IdRiesgo    = $Parametros['IdRiesgo'];
        $Nombre      = $Parametros['Nombre'];
        $IdDimension = $Parametros['IdDimension'];
        $IdTRiesgo   = $Parametros['IdTRiesgo'];
        $IdCLegales  = $Parametros['IdCLegales'];
        
        $CondicionN = [
            ['name',          '=', $Nombre],
            ['id',            '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $CondicionI = [
            ['id_risk',       '=', $IdRiesgo],
            ['id',            '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risks', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadI = array('Tabla'     => 'sysdev.rm_risks', 
                                      'Condicion' => $CondicionI);
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
        $DuplicidadI = $this->ValidarDuplicidad($ParametrosDuplicidadI);
        $Duplicidad;
        if(!$DuplicidadI && !$DuplicidadN){
            $Duplicidad = 'Duplicidad';
        }elseif(!$DuplicidadI){
            $Duplicidad = 'Duplicidad Id Riesgo';
        }elseif(!$DuplicidadN){
            $Duplicidad = 'Duplicidad Nombre';
        }else{
            try {
                $Query = DB::table('sysdev.rm_risks')
                                ->where('id', $Id)
                                ->update([
                                        'id_risk'           => $IdRiesgo,
                                        'name'              => $Nombre,
                                        'dimension_id'      => $IdDimension,
                                        'risk_type_id'      => $IdTRiesgo,
                                        'legal_standard_id' => $IdCLegales
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
        $Query = DB::table('sysdev.rm_risks')
                   ->where('id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarRisk(){
        $Query = $this->from('sysdev.rm_risks AS risk')
                      ->join('sysdev.users AS Usuario', 'Usuario.id', '=', 'risk.user_id')
                      ->join('sysdev.rm_dimensions AS dimension', 'dimension.id', '=', 'risk.dimension_id')
                      ->join('sysdev.rm_legal_standards AS LS', 'LS.id', '=', 'risk.legal_standard_id')
                      ->join('sysdev.rm_risk_types AS RT', 'RT.id', '=', 'risk.risk_type_id')
                      ->select('risk.id        AS Id', 
                               'risk.id_risk   AS IdRiesgo',
                               'risk.name      AS Nombre',
                               'dimension.name      AS Dimension',
                               'LS.name      AS CriteriosLegales',
                               'RT.name      AS TiposRiesgos',
                               'Usuario.name AS Usuario',
                               DB::raw('DATE_FORMAT(risk.created_at, "%d/%m/%Y %r") as FechaCreacion'),
                               DB::raw('DATE_FORMAT(risk.update_at, "%d/%m/%Y %r") as FechaModificacion'),
                               'risk.status    AS Estatus'
                               )
                      ->where([['risk.status_delete', '=', 1]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDRisk($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_risks AS risk')
                      ->select('risk.id        AS Id', 
                               'risk.id_risk   AS IdRiesgo',
                               'risk.name      AS Nombre',
                               'risk.dimension_id  AS IdDimension',
                               'risk.risk_type_id      AS IdTiposRiesgos',
                               'risk.legal_standard_id      AS IdCriteriosLegales')
                               
                      ->where([['risk.id', '=', $Id]]) 
                      ->get();
        return $Query;
    }

    public function SeleccionarGRisk(){
        $Query = $this->from('sysdev.rm_risks')
                      ->select('id AS Id', 
                                'id_risk AS Identificador',
                                'name AS Nombre'
                               )
                      ->where([['status', '=', 1]]) 
                      ->orderBy('Identificador') 
                      ->get();
        return $Query;
    }

    public function ImportarRisk($Parametros){
        $Datos   = $Parametros['Datos'];
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
            $CondicionI = [
                        ['id_risk', '=', trim($row->IdRiesgo)],
                        ['status_delete', '=', 1]
                        ];
            $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risks', 
                                          'Condicion' => $CondicionN);
            $ParametrosDuplicidadI = array('Tabla'     => 'sysdev.rm_risks', 
                                          'Condicion' => $CondicionI);        
            $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
            $DuplicidadI = $this->ValidarDuplicidad($ParametrosDuplicidadI);
            if(!$DuplicidadI || !$DuplicidadN){
                array_push($duplicados, $row);
            }else{    
                    
                    $ParametrosLS = array('Tabla' => 'sysdev.rm_legal_standards', 
                                          'Texto' => $row->CriterioLegal);
                    $IdLS = $this->ObtenerId($ParametrosLS);

                    $ParametrosRT = array('Tabla' => 'sysdev.rm_risk_types', 
                                          'Texto' => $row->TipoRiesgo);
                    $IdRT = $this->ObtenerId($ParametrosRT);

                    $ParametrosDimesion = array('Tabla' => 'sysdev.rm_dimensions', 
                                                'Texto' => $row->Dimension);
                    $IdDimension = $this->ObtenerId($ParametrosDimesion);

                    
                    if (empty($IdLS) || empty($IdRT) || empty($IdDimension)){
                         array_push($errores, $row);
                    }
                    else {
                        $Fila =['id_risk'           => trim($row->IdRiesgo),
                                'name'              => trim(strtoupper($row->Nombre)),
                                'dimension_id'      => $IdDimension,
                                'risk_type_id'      => $IdRT,
                                'legal_standard_id' => $IdLS,
                                'user_id'           => $IdUsuario
                           ];
                    array_push($data, $Fila);
                    }
            }
        }
        $Query = DB::table('sysdev.rm_risks')
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
        $Query = DB::table('sysdev.rm_risks')
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
