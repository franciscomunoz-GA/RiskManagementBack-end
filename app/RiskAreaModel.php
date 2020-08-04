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
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_nombre_areas', 
                                      'Condicion' => $CondicionN);
        
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
        $Duplicidad;
        if(!$DuplicidadN){
            $Duplicidad = 'Duplicidad Nombre';
        }else{
            try {
                $Id = DB::table('sysdev.rm_nombre_areas')
                        ->insertGetId(['name'    => $Nombre,
                                       'area_id' => $IdArea,
                                       'user_id' => $IdUsuario
                                       ]);
                $ParametrosAgregar = array('Id'        => $Id,
                                           'IdRiesgo'  => $IdRiesgo,
                                           'IdUsuario' => $IdUsuario);
                $Query = $this->AgregarRiesgos($ParametrosAgregar);
                
                return $Id;
            } catch (\Throwable $th) {
                DB::table('sysdev.rm_nombre_areas')
                    ->where('id', '=', $Id)
                    ->delete();
                return 'Error al insertar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarRiskArea($Parametros){
        $Id       = $Parametros['Id'];
        $Nombre   = $Parametros['Nombre'];
        $IdArea   = $Parametros['IdArea'];

        $Uso = DB::table('sysdev.rm_ratings_calendar_clients as URSI')
                   ->where('nombre_area_id',$Id)
                   ->count();
                
        $CondicionN = [
            ['name',          '=', $Nombre],
            ['id',            '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_nombre_areas', 
                                      'Condicion' => $CondicionN);
        
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
       
        $Duplicidad;
        if(!$DuplicidadN){
            $Duplicidad = 'Duplicidad';
        }elseif($Uso > 0){
            $Duplicidad = 'Ya se encuentra en uso';
        }else{
            try {
                $Query = DB::table('sysdev.rm_nombre_areas')
                                ->where('id', $Id)
                                ->update([
                                        'name'    => $Nombre,
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
        $Query = DB::table('sysdev.rm_nombre_areas')
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
        $SubQuery = DB::table('sysdev.rm_ratings_calendar_clients as URSI')
                   ->select('URSI.nombre_area_id as nombre_area_id',
                            DB::raw('count(URSI.id) as usa'))
                   ->groupBy('URSI.nombre_area_id');
        
        
        $Query = $this->from('sysdev.rm_nombre_areas as NA')
                      ->join('sysdev.rm_areas as Areas', 'Areas.id', '=', 'NA.area_id')
                      ->join('sysdev.users as Usuario', 'Usuario.id', '=', 'NA.user_id')
                      ->leftjoin('sysdev.rm_nombre_areas_risks as NAR', 'NAR.nombre_areas_id', '=', 'NA.id')
                      
                      ->leftJoinSub($SubQuery, 'SubQuery', function ($join) {
                                $join->on('NA.id', '=', 'SubQuery.nombre_area_id');})
                                                     
                        ->select('NA.id as Id', 
                               'NA.name as Nombre',
                               'Areas.id as IdArea',
                               'Areas.name as NombreArea',
                               DB::raw('concat(cast(count(NAR.id) as UNSIGNED)," RIESGO(S)") as Riesgos'),
                               DB::raw('max(Usuario.name) as Usuario'),
                               DB::raw('date_format(max(NA.created_at),"%d/%m/%Y %r") as Fecha'),
                               DB::raw('min(NA.status) as Estatus'),
                               DB::raw('count(SubQuery.usa) as usada')
                               )
                      ->where([['NA.status_delete', '=', 1],
                                ['NAR.status_delete', '=', 1]])
                      ->groupBy('NA.id')
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDRiskArea($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_nombre_areas as NA')
                      ->select('NA.id   AS Id', 
                               'NA.name AS Nombre',
                               'NA.area_id as IdArea'
                              )                               
                      ->where([['NA.id', '=', $Id]]) 
                      ->limit(1)
                      ->get();
        
        $data = [];
        foreach ($Query as $index => $row) {
        $Parametros = array('Id' => $row->Id);
        $Riesgos = $this->ObtenerRiesgosXRelacion($Parametros);
        $Fila = array('Id'      => $row->Id,
                      'Nombre'  => $row->Nombre,
                      'IdArea'  => $row->IdArea,
                      'Riesgos' => $Riesgos);
        $data[$index]=$Fila;
        }

        return $data;
    }

    public function SeleccionarGRiskArea(){
        $Query = $this->from('sysdev.rm_nombre_areas AS NA')
                      ->select('NA.id AS Id', 
                               'NA.name AS Nombre'
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
        $correctos = [];
        $data = [];
        try {
        foreach ($Datos as $index => $row) {
            $ParametrosA = array('Tabla' => 'sysdev.rm_areas', 
                                  'Texto' => $row->Area);
            $IdA = $this->ObtenerId($ParametrosA);
            
            $ParametrosR = array('Tabla' => 'sysdev.rm_risks', 
                                  'Texto' => $row->Riesgo);
            $IdR = $this->ObtenerId($ParametrosR);

            if (empty($IdR) || empty($IdA) ){
                array_push($errores, $row);
            }
            else {
            
            $ParametrosNA = array(  'Tabla' => 'sysdev.rm_nombre_areas', 
                                    'Texto' => [['name', '=', trim(strtoupper($row->Nombre))],
                                                ['area_id', '=', $IdA],
                                                ['status_delete', '=', 1]]
                                    );
            $IdNA = $this->ObtenerIdCondicion($ParametrosNA);

            if (empty($IdNA)){
                $ParametrosN = array(  'Tabla' => 'sysdev.rm_nombre_areas', 
                                        'Texto' => [['name', '=', trim(strtoupper($row->Nombre))],
                                                    ['status_delete', '=', 1]]
                                    );
                $IdN = $this->ObtenerIdCondicion($ParametrosN);

                if(empty($IdN)){
                    $IdNA = DB::table('sysdev.rm_nombre_areas')
                                ->insertGetId(['name'    => $row->Nombre,
                                            'area_id' => $IdA,
                                            'user_id' => $IdUsuario
                                        ]);
                    array_push($correctos, $row);
                    $Fila =['nombre_areas_id' => $IdNA,
                                'risk_id'     => $IdR,
                                'user_id'     => $IdUsuario
                            ];
                    array_push($data, $Fila);
                }
                else {
                    array_push($duplicados, $row);
                }
            }
            else {
                $CondicionNAR = [
                            ['nombre_areas_id', '=', $IdNA],
                            ['risk_id',         '=', $IdR],
                            ['status_delete',   '=', 1]
                            ];
                $ParametrosDuplicidadNAR = array('Tabla'     => 'sysdev.rm_nombre_areas_risks', 
                                               'Condicion' => $CondicionNAR);        
                $DuplicidadNAR = $this->ValidarDuplicidad($ParametrosDuplicidadNAR);
                if(!$DuplicidadNAR){
                    array_push($duplicados, $row);
                }else{  
                    array_push($correctos, $row);
                     
                        $Fila =['nombre_areas_id' => $IdNA,
                                'risk_id'         => $IdR,
                                'user_id'         => $IdUsuario
                            ];
                        array_push($data, $Fila);
                    }
                }
            }
        }
        $Query = DB::table('sysdev.rm_nombre_areas_risks')
                   ->insert($data);
        $retorno = array(
                        'Dato'       => $Query,
                        'Correctos'  => $correctos,
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
        $Query = DB::table('sysdev.rm_nombre_areas')
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

    private function ObtenerIdCondicion($Parametros){
        $Tabla = $Parametros['Tabla'];
        $Condicion = $Parametros['Texto'];
       
        try {
            $Query = $this->from($Tabla)
                        ->select('id AS Id')
                        ->where($Condicion) 
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

    private function AgregarRiesgos($Parametros){
        $Id        = $Parametros['Id'];
        $IdRiesgo  = $Parametros['IdRiesgo'];
        $IdUsuario = $Parametros['IdUsuario'];
        $data = [];
       foreach ($IdRiesgo as $index => $row) {
                $Fila =['nombre_areas_id' => $Id,
                        'risk_id'         => $row,
                        'user_id'         => $IdUsuario
                        ];
                    array_push($data, $Fila);
                }
        $Query = DB::table('sysdev.rm_nombre_areas_risks')
                   ->insert($data);
        return $Query;
    }

    private function ObtenerRiesgosXRelacion($parametros){
        $Id = $parametros['Id'];
        $SubQuery = DB::table('sysdev.rm_ratings_calendar_clients as URSI')
                   ->select('URSI.nombre_area_risk_id as nombre_area_riesgo_id',
                            DB::raw('count(URSI.id) as usa'))
                   ->groupBy('URSI.nombre_area_risk_id');


        $Riesgos = $this->from('sysdev.rm_nombre_areas_risks as NAR')
                        ->join('sysdev.rm_risks as Riesgos', 'Riesgos.id', '=', 'NAR.risk_id')

                        ->leftJoinSub($SubQuery, 'SubQuery', function ($join) {
                            $join->on('NAR.id', '=', 'SubQuery.nombre_area_riesgo_id');})
                            

                        ->select('NAR.id as idRelacion',
                                 'Riesgos.id as IdRiesgo', 
                                 'Riesgos.name as Riesgo',
                                 DB::raw('ifnull(SubQuery.usa,0) as usada'))
                        ->where([['NAR.nombre_areas_id', '=', $Id],
                                 ['NAR.status_delete', '=', 1]])
                        ->orderBy('Riesgo')                   
                        ->get();
        return $Riesgos;
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

    public function AgregarRiskAreas($Parametros){
        $IdRiesgo  = $Parametros['IdRiesgo'];
        $Id        = $Parametros['Id'];
        $IdUsuario = $Parametros['IdUsuario'];
        
        try {
            $data = [];
            $Duplicidad  = 0;
            foreach ($IdRiesgo as $index => $row) {
                $CondicionR = [
                    ['nombre_areas_id', '=', $Id],
                    ['risk_id',       '=', $row],
                    ['status_delete', '=', 1]
                ];
                $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_nombre_areas_risks', 
                                              'Condicion' => $CondicionR);
                $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
                if(!$DuplicidadR){
                    $Duplicidad ++;
                }else{
                    $Fila =['risk_id'         => $row,
                            'nombre_areas_id' => $Id,
                            'user_id'         => $IdUsuario
                            ];
                        array_push($data, $Fila);
                    }
            }
                $Query = DB::table('sysdev.rm_nombre_areas_risks')
                        ->insert($data);
                return $Query;
            } catch (\Throwable $th) {
                return 'Error al agregar';
            }
        return $Query;
    }

    public function QuitarRiskAreas($Parametros){
        $IdRelacion = $Parametros['IdRelacion'];
                
        try {
            $Query = DB::table('sysdev.rm_nombre_areas_risks')
                        ->whereIn('id',$IdRelacion)
                        ->update([
                            'status_delete' => 0,
                            'status' =>0
                            ]);
                return $Query;
            } catch (\Throwable $th) {
                return 'Error al quitar';
            }
        
        return $Duplicidad;
    }
}
