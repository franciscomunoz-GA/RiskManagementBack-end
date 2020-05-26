<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class RiskSiteInterestModel extends Model
{
    public function InsertarRiskSitesInterest($Parametros){
        $Nombre         = $Parametros['Nombre'];
        $IdRiesgo       = $Parametros['IdRiesgo'];
        $IdSitioInteres = $Parametros['IdSitioInteres'];
        $IdUsuario      = $Parametros['IdUsuario'];
        
        $CondicionN = [
            ['name', '=', $Nombre],
            ['status_delete', '=', 1]
        ];
        $CondicionR = [
            ['risk_id', '=', $IdRiesgo],
            ['sites_of_interest_id', '=', $IdSitioInteres],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
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
                $Id = DB::table('sysdev.rm_risk_sites_interest')
                        ->insertGetId(['name'                 => $Nombre,
                                       'risk_id'              => $IdRiesgo,
                                       'sites_of_interest_id' => $IdSitioInteres,
                                       'user_id'              => $IdUsuario
                                       ]);
                
                return $Id;
            } catch (\Throwable $th) {
                return 'Error al insertar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarRiskSitesInterest($Parametros){
        $Id             = $Parametros['Id'];
        $Nombre         = $Parametros['Nombre'];
        $IdRiesgo       = $Parametros['IdRiesgo'];
        $IdSitioInteres = $Parametros['IdSitioInteres'];
                
        $CondicionN = [
            ['name',          '=', $Nombre],
            ['id',            '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $CondicionR = [
            ['risk_id',              '=', $IdRiesgo],
            ['sites_of_interest_id', '=', $IdSitioInteres],
            ['id',                   '!=', $Id],
            ['status_delete',        '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                      'Condicion' => $CondicionR);
        $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
        $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
        $Duplicidad;
        if(!$DuplicidadR && !$DuplicidadN){
            $Duplicidad = 'Duplicidad';
        }elseif(!$DuplicidadR){
            $Duplicidad = 'Duplicidad Id Riesgo';
        }elseif(!$DuplicidadN){
            $Duplicidad = 'Duplicidad Nombre';
        }else{
            try {
                $Query = DB::table('sysdev.rm_risk_sites_interest')
                                ->where('id', $Id)
                                ->update([
                                        'name'                 => $Nombre,
                                        'risk_id'              => $IdRiesgo,
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
        $Id     = $Parametros['Id'];
        $Accion = $Parametros['Accion'];
        try {
        $Query = DB::table('sysdev.rm_risk_sites_interest')
                   ->where('id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarRiskSitesInterest(){
        $Query = $this->from('sysdev.rm_risk_sites_interest as RSI')
                      ->join('sysdev.rm_risks as Risk', 'Risk.id', '=', 'RSI.risk_id')
                      ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'RSI.sites_of_interest_id')
                      ->join('sysdev.users as usuario', 'usuario.id', '=', 'RSI.user_id')
                      ->select('RSI.id as Id', 
                               'RSI.name as Nombre',
                               'Risk.id_risk as RiesgoId',
                               'Risk.name as RiesgoNombre',
                               'SI.name as SitioInteres',
                               'usuario.name as Usuario',
                               DB::raw('DATE_FORMAT(RSI.created_at, "%d/%m/%Y %r") as FechaCreacion'),
                               DB::raw('DATE_FORMAT(RSI.update_at, "%d/%m/%Y %r") as FechaModificacion'),
                               'RSI.status as Estatus'
                               )
                      ->where([['RSI.status_delete', '=', 1]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDRiskSitesInterest($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_risk_sites_interest AS RSI')
                      ->select('RSI.id        AS Id', 
                               'RSI.name   AS Nombre',
                               'RSI.risk_id      AS IdRisk',
                               'RSI.sites_of_interest_id  AS IdSitesInterest'
                               )                               
                               
                      ->where([['RSI.id', '=', $Id]]) 
                      ->get();
        return $Query;
    }

    public function SeleccionarGRiskSitesInterest(){
        $Query = $this->from('sysdev.rm_risk_sites_interest AS RSI')
                      ->select('RSI.id AS Id', 
                               'RSI.name AS Nombre'
                               )
                      ->where([['status', '=', 1]]) 
                      ->orderBy('Nombre') 
                      ->get();
        return $Query;
    }

    public function ImportarRiskSitesInterest($Parametros){
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
                $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                               'Condicion' => $CondicionN);
                $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_risk_sites_interest', 
                                               'Condicion' => $CondicionR);        
                $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
                $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
                if(!$DuplicidadR || !$DuplicidadN){
                    array_push($duplicados, $row);
                }else{    
                        $Fila =['name'                 => trim($row->Nombre),
                                'risk_id'              => $IdR,
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
        $Query = DB::table('sysdev.rm_risk_sites_interest')
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
