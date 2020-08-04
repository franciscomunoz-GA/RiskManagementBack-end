<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class ClientsRiskAreaModel extends Model
{
    public function InsertarClientRiskArea($Parametros){
        $Nombre        = $Parametros['Nombre'];
        $IdRelRiskArea = $Parametros['IdRelRiskArea'];
        $IdCliente     = $Parametros['IdCliente'];
        $IdUsuario     = $Parametros['IdUsuario'];
        
        $CondicionN = [
            ['name', '=', $Nombre],
            ['status_delete', '=', 1]
        ];
        $CondicionR = [
            ['risk_area_id', '=', $IdRelRiskArea],
            ['client_id', '=', $IdCliente],
            ['status_delete', '=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_clients_risks_areas', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_clients_risks_areas', 
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
                $Id = DB::table('sysdev.rm_clients_risks_areas')
                        ->insertGetId(['name'    => $Nombre,
                                       'risk_area_id' => $IdRelRiskArea,
                                       'client_id' => $IdCliente,
                                       'user_id' => $IdUsuario
                                       ]);
                
                return $Id;
            } catch (\Throwable $th) {
                return 'Error al insertar';
            }
        }
        return $Duplicidad;
    }

    public function ModificarClientRiskArea($Parametros){
        $Id            = $Parametros['Id'];
        $Nombre        = $Parametros['Nombre'];
        $IdRelRiskArea = $Parametros['IdRelRiskArea'];
        $IdCliente     = $Parametros['IdCliente'];
                
        $CondicionN = [
            ['name',          '=', $Nombre],
            ['id',            '!=', $Id],
            ['status_delete', '=', 1]
        ];
        $CondicionR = [
            ['risk_area_id', '=', $IdRelRiskArea],
            ['client_id',    '=', $IdCliente],
            ['id',           '!=', $Id],
            ['status_delete','=', 1]
        ];
        $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_clients_risks_areas', 
                                      'Condicion' => $CondicionN);
        $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_clients_risks_areas', 
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
                $Query = DB::table('sysdev.rm_clients_risks_areas')
                                ->where('id', $Id)
                                ->update([
                                        'name'         => $Nombre,
                                        'risk_area_id' => $IdRelRiskArea,
                                        'client_id'    => $IdCliente
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
        $Query = DB::table('sysdev.rm_clients_risks_areas')
                   ->where('id', $Id)
                   ->update([
                            'status' => $Accion
                           ]);
            return $Query;
        } catch (\Throwable $th) {
            return 'Error al Modificar';
        }
    }

    public function SeleccionarClientRiskArea(){
        $Query = $this->from('sysdev.rm_clients_risks_areas as RCRA')
                      ->join('sysdev.clients as Clients', 'Clients.id', '=', 'RCRA.client_id')
                      ->join('sysdev.rm_nombre_areas as NA', 'NA.id', '=', 'RCRA.risk_area_id')
                      ->join('sysdev.rm_areas as Areas', 'Areas.id', '=', 'NA.area_id')
                      ->join('sysdev.users as users', 'users.id', '=', 'RCRA.user_id')
                      ->select('RCRA.id as Id', 
                               'RCRA.name as Nombre',
                               'Clients.commercial_name as Cliente',
                               'NA.name as NombreRelacionAR',
                               'Areas.name as Area',
                               'users.name as Usuario',
                               DB::raw('DATE_FORMAT(RCRA.created_at, "%d/%m/%Y %r") as FechaCreacion'),
                               DB::raw('DATE_FORMAT(RCRA.update_at, "%d/%m/%Y %r") as FechaModificacion'),
                               'RCRA.status as Estatus'
                               )
                      ->where([['RCRA.status_delete', '=', 1]])
                      ->orderBy('Nombre')
                      ->get();
        return $Query;
    }

    public function SeleccionarDClientRiskArea($Parametros){
        $Id = $Parametros['Id'];
        $Query = $this->from('sysdev.rm_clients_risks_areas as RCRA')
                      ->select('RCRA.id       AS Id', 
                               'RCRA.name     AS Nombre',
                               'RCRA.risk_area_id  AS IdRelacionRiesgoArea',
                               'RCRA.client_id  AS IdCliente'
                               )                               
                               
                      ->where([['RCRA.id', '=', $Id]]) 
                      ->get();
        return $Query;
    }

    public function SeleccionarGClientRiskArea(){
        $Query = $this->from('sysdev.rm_clients_risks_areas as RCRA')
                      ->select('RCRA.id AS Id', 
                               'RCRA.name AS Nombre'
                               )
                      ->where([['RCRA.status', '=', 1]]) 
                      ->orderBy('Nombre') 
                      ->get();
        return $Query;
    }

    public function ImportarClientRiskArea($Parametros){
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
            
            $ParametrosR = array('Tabla' => 'sysdev.rm_nombre_areas', 
                                  'Texto' => $row->RiesgoArea);
            $IdR = $this->ObtenerId($ParametrosR);

            $ParametrosC = array(  'Tabla' => 'sysdev.clients', 
                                    'Texto' => [['commercial_name', '=', trim(strtoupper($row->NombreComercial))]]
                                     );
            $IdC = $this->ObtenerIdCondicion($ParametrosC);
            
            if (empty($IdR) || empty($IdC) ){
                array_push($errores, $row);
            }
            else {
                $CondicionR = [
                            ['risk_area_id', '=', $IdR],
                            ['client_id', '=', $IdC],
                            ['status_delete', '=', 1]
                            ];
                $ParametrosDuplicidadN = array('Tabla'     => 'sysdev.rm_clients_risks_areas', 
                                               'Condicion' => $CondicionN);
                $ParametrosDuplicidadR = array('Tabla'     => 'sysdev.rm_clients_risks_areas', 
                                               'Condicion' => $CondicionR);        
                $DuplicidadN = $this->ValidarDuplicidad($ParametrosDuplicidadN);
                $DuplicidadR = $this->ValidarDuplicidad($ParametrosDuplicidadR);
                if(!$DuplicidadR || !$DuplicidadN){
                    array_push($duplicados, $row);
                }else{    
                        $Fila =['name'    => trim($row->Nombre),
                                'risk_area_id' => $IdR,
                                'client_id' => $IdC,
                                'user_id' => $IdUsuario
                            ];
                        array_push($data, $Fila);
                }
            }
        }
        $Query = DB::table('sysdev.rm_clients_risks_areas')
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
        $Query = DB::table('sysdev.rm_clients_risks_areas')
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
