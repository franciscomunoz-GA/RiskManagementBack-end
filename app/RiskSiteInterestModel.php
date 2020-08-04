<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class RiskSiteInterestModel extends Model
{
    public function InsertarRiskSitesInterest($Parametros){
        $IdRiesgo       = $Parametros['IdRiesgo'];
        $IdSitioInteres = $Parametros['IdSitioInteres'];
        $IdUsuario      = $Parametros['IdUsuario'];
        
        $CondicionR = [
            ['sites_of_interest_id', '=', $IdSitioInteres],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                      'Condicion' => $CondicionR);
        $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
        $Duplicidad;
        if(!$DuplicidadR){
            $Duplicidad = 'Duplicidad';
        }else{
            try {
                $data = [];
                foreach ($IdRiesgo as $index => $row) {
                    $Fila =['risk_id'              => $row,
                            'sites_of_interest_id' => $IdSitioInteres,
                            'user_id'              => $IdUsuario
                            ];
                        array_push($data, $Fila);
                    }
                $Query = DB::table('sysdev.rm_risk_sites_interest')
                        ->insert($data);
                return $Query;
            } catch (\Throwable $th) {
                return 'Error al insertar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarRiskSitesInterest($Parametros){
        $Id             = $Parametros['Id'];
        $IdSitioInteres = $Parametros['IdSitioInteres'];

        $Uso = $this->from('sysdev.rm_ratings_calendar_sites_interest as URSI')
                    ->whereIn('risk_sites_interests_id',$Id)
                    ->count();
                        
        $CondicionR = [
            ['sites_of_interest_id', '=', $IdSitioInteres],
            ['status_delete',        '=', 1]
        ];
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                      'Condicion' => $CondicionR);
        $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
        $Duplicidad;
        if(!$DuplicidadR){
            $Duplicidad = 'Duplicidad';
        }elseif($Uso > 0){
                $Duplicidad = 'Ya se encuentra en uso';
        }else{
            try {
                $Query = DB::table('sysdev.rm_risk_sites_interest')
                                ->whereIn('id',$Id)
                                ->update([
                                        'sites_of_interest_id' => $IdSitioInteres
                                        ]);
                return $Query;
            } catch (\Throwable $th) {
                return 'Error al modificar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarEstatus($Parametros){
        $Id     = $Parametros['Id'];  //Id Sitio Interes
        $Accion = $Parametros['Accion'];
        try {
        $Query = DB::table('sysdev.rm_risk_sites_interest')
                   ->where('sites_of_interest_id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarRiskSitesInterest(){
        $SubQuery = DB::table('sysdev.rm_ratings_calendar_sites_interest as URSI')
                   ->select('URSI.risk_sites_interests_id as risk_sites_interes_id',
                            DB::raw('count(URSI.id) as usa'))
                   ->groupBy('URSI.risk_sites_interests_id');
        
        
        $Query = $this->from('sysdev.rm_risk_sites_interest as RRSI')
                      ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'RRSI.sites_of_interest_id')
                      ->join('sysdev.users as usuario', 'usuario.id', '=', 'RRSI.user_id')
                      
                      ->leftJoinSub($SubQuery, 'SubQuery', function ($join) {
                                $join->on('RRSI.id', '=', 'SubQuery.risk_sites_interes_id');})
                                                     
                        ->select('SI.id as Id', 
                               'SI.name as SitioInteres',
                               DB::raw('concat(cast(count(RRSI.risk_id) as UNSIGNED)," RIESGO(S)") as Riesgos'),
                               DB::raw('max(usuario.name) as Usuario'),
                               DB::raw('date_format(max(RRSI.created_at),"%d/%m/%Y %r") as Fecha'),
                               DB::raw('min(RRSI.status) as Estatus'),
                               DB::raw('count(SubQuery.usa) as usada')
                               )
                      ->where([['RRSI.status_delete', '=', 1]])
                      ->groupBy('RRSI.sites_of_interest_id')
                      ->orderBy('SitioInteres')
                      ->get();
        return $Query;
    }

    public function SeleccionarDRiskSitesInterest($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_risk_sites_interest AS RRSI')
                      ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'RRSI.sites_of_interest_id')
                      ->select('SI.id   AS IdSitesInterest', 
                               'SI.name AS SitesInterest'
                              )                               
                      ->where([['RRSI.sites_of_interest_id', '=', $Id]]) 
                      ->limit(1)
                      ->get();
        
        $data = [];
        foreach ($Query as $index => $row) {
        $Parametros = array('IdSitesInterest' => $row->IdSitesInterest);
        $Riesgos = $this->ObtenerRiesgosXRelacion($Parametros);
        $Fila = array('IdSitesInterest' => $row->IdSitesInterest,
                      'SitesInterest'   => $row->SitesInterest,
                      'Riesgos'          => $Riesgos);
        $data[$index]=$Fila;
        }

        return $data;
    }

    public function SeleccionarGRiskSitesInterest(){
        $Query = $this->from('sysdev.rm_risk_sites_interest as RSI')
                      ->join('sysdev.sites_of_interest as SI','SI.id','=','RSI.sites_of_interest_id')
                      ->select('RSI.sites_of_interest_id  as Id', 
                               'SI.name as SitioInteres'
                               )
                      ->where([['RSI.status_delete', '=', 1]]) 
                      ->groupBy('RSI.sites_of_interest_id')
                      ->orderBy('SitioInteres') 

                      ->get();
        return $Query;
    }

    public function ImportarRiskSitesInterest($Parametros){
        $Datos   = $Parametros['Datos'];
        $IdUsuario = $Parametros['IdUsuario'];
        $errores = [];
        $duplicados = [];
        $correctos = [];
        $data = [];
        try {
        foreach ($Datos as $index => $row) {
            $ParametrosR = array('Tabla' => 'sysdev.rm_risks', 
                                  'Texto' => $row->Riesgo);
            $IdR = $this->ObtenerId($ParametrosR);

            $ParametrosSI = array(  'Tabla' => 'sysdev.sites_of_interest', 
                                    'Texto' => [['name', '=', trim(strtoupper($row->SitioInteres))]]
                                    );
            $IdSI = $this->ObtenerIdCondicion($ParametrosSI);
            
            if (empty($IdR) || empty($IdSI) ){
                array_push($errores, $row);
            }
            else {
                $CondicionR = [
                            ['risk_id', '=', $IdR],
                            ['sites_of_interest_id', '=', $IdSI],
                            ['status_delete', '=', 1]
                            ];
                $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                               'Condicion' => $CondicionR);        
                $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
                if(!$DuplicidadR){
                    array_push($duplicados, $row);
                }else{  
                    array_push($correctos, $row);
                     
                        $Fila =['risk_id'              => $IdR,
                                'sites_of_interest_id' => $IdSI,
                                'user_id'              => $IdUsuario
                            ];
                        array_push($data, $Fila);
                }
            }
        }
        $Query = DB::table('sysdev.rm_risk_sites_interest')
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
        $Id = $Parametros['Id'];
        try {
        $Query = DB::table('sysdev.rm_risk_sites_interest')
                   ->where('sites_of_interest_id', $Id)
                   ->delete();
                   return $Query;
        } catch (\Throwable $th) {
            return 'Error al Eliminar';
        }
    }

    public function AgregarRiskSitesInterest($Parametros){
        $IdRiesgo       = $Parametros['IdRiesgo'];
        $IdSitioInteres = $Parametros['IdSitioInteres'];
        $IdUsuario      = $Parametros['IdUsuario'];
        
        try {
            $data = [];
            $Duplicidad  = 0;
            foreach ($IdRiesgo as $index => $row) {
                $CondicionR = [
                    ['sites_of_interest_id', '=', $IdSitioInteres],
                    ['risk_id', '=', $row],
                    ['status_delete', '=', 1]
                ];
                $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                              'Condicion' => $CondicionR);
                $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
                if(!$DuplicidadR){
                    $Duplicidad ++;
                }else{
                    $Fila =['risk_id'              => $row,
                            'sites_of_interest_id' => $IdSitioInteres,
                            'user_id'              => $IdUsuario
                            ];
                        array_push($data, $Fila);
                    }
            }
                $Query = DB::table('sysdev.rm_risk_sites_interest')
                        ->insert($data);
                return $Query;
            } catch (\Throwable $th) {
                return 'Error al agregar';
            }
        return $Query;
    }

    public function QuitarRiskSitesInterest($Parametros){
        $IdRelacion = $Parametros['IdRelacion'];
                
        try {
            $Query = DB::table('sysdev.rm_risk_sites_interest')
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

    private function ObtenerRiesgosXRelacion($parametros){
        $IdSitesInterest = $parametros['IdSitesInterest'];
        $SubQuery = DB::table('sysdev.rm_ratings_calendar_sites_interest as URSI')
                   ->select('URSI.risk_sites_interests_id as risk_sites_interes_id',
                            DB::raw('count(URSI.id) as usa'))
                   ->groupBy('URSI.risk_sites_interests_id');


        $Riesgos = $this->from('sysdev.rm_risk_sites_interest as RSI')
                        ->join('sysdev.rm_risks as Risk', 'Risk.id', '=', 'RSI.risk_id')

                        ->leftJoinSub($SubQuery, 'SubQuery', function ($join) {
                            $join->on('RSI.id', '=', 'SubQuery.risk_sites_interes_id');})
                            

                        ->select('RSI.id as Id',
                                 'Risk.id as IdRiesgo', 
                                 'Risk.name as Riesgo',
                                 DB::raw('ifnull(SubQuery.usa,0) as usada'))
                        ->where([['RSI.sites_of_interest_id', '=', $IdSitesInterest],
                                 ['RSI.status_delete', '=', 1]])
                        ->orderBy('Riesgo')                   
                        ->get();
        return $Riesgos;
    }
}
