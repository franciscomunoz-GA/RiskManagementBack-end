<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class CalendarioModel extends Model
{
    public function InsertarCalendario($Parametros){
        $Inspector = $Parametros['Inspector'];
        $Folio     = $Parametros['Folio'];
        $Fecha     = $Parametros['Fecha'];
        $Tipo      = $Parametros['Tipo'];
        $Id        = $Parametros['Id'];
        $IdUsuario = $Parametros['IdUsuario'];
        
        try {
            if($Tipo == 1){
                $Id = DB::table('sysdev.rm_calendars_sites_interest')
                        ->insertGetId(['folio'    => $Folio,
                                       'inspector_id' => $Inspector,
                                       'date' => $Fecha,
                                       'sites_interest_id' => $Id,
                                       'user_id' => $IdUsuario]);
                
            } 
            elseif($Tipo == 2){
                $Id = DB::table('sysdev.rm_calendars_client')
                        ->insertGetId(['folio'    => $Folio,
                                       'inspector_id' => $Inspector,
                                       'date' => $Fecha,
                                       'client_id' => $Id,
                                       'user_id' => $IdUsuario]);
            }  
                return $Id;
        } catch (\Throwable $th) {
                return 'Error al insertar' . $th;
            }
    }

    public function SeleccionarCalendario(){
        $first = $this->from('sysdev.rm_calendars_client as CalCli')
                      ->join('sysdev.users as Inspector', 'Inspector.id', '=', 'CalCli.inspector_id')
                      ->join('sysdev.clients as cliente', 'cliente.id', '=', 'CalCli.client_id')
                      ->join('sysdev.rm_clients_risks_areas as CRA', 'CRA.client_id', '=', 'CalCli.client_id')
                      ->join('sysdev.rm_nombre_areas as NA', 'NA.id', '=', 'CRA.risk_area_id')
                      ->join('sysdev.rm_nombre_areas_risks as NAR', 'NAR.nombre_areas_id', '=', 'NA.id')
                      ->leftJoin('sysdev.rm_ratings_calendar_clients as RCC',  function ($join) {
                            $join->on('CalCli.id', '=', 'RCC.calendar_client_id')->on('NA.id', '=', 'RCC.nombre_area_id')->on('NAR.id', '=', 'RCC.nombre_area_risk_id');
                        })
                      ->select('CalCli.id as IdCalendario',
                               DB::raw('2 as Tipo'),
                               'CalCli.folio as Folio',
                               'Inspector.name as Inspector',
                               DB::raw('date_format(CalCli.date,"%Y-%m-%dT%H:%i:%s") as Fecha'),
                               'cliente.commercial_name as Descripcion',
                               DB::raw('if(count(RCC.id)>0,1,0) as Respondido')
                               )
                      ->where([['CRA.status', '=', 1],
                                ['NA.status', '=', 1],
                                ['NAR.status', '=', 1]])
                      ->whereBetween(DB::raw('date(CalCli.created_at)'), [DB::raw('date_sub(curdate(),interval 1 year)'), DB::raw('curdate()')])
                      ->groupBy('CalCli.id');

        $Query = $this->from('sysdev.rm_calendars_sites_interest as CSI')
                      ->join('sysdev.users as Inspector', 'Inspector.id', '=', 'CSI.inspector_id')
                      ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'CSI.sites_interest_id')
                      ->join('sysdev.rm_risk_sites_interest as RSI', 'RSI.sites_of_interest_id', '=', 'CSI.sites_interest_id')
                      ->leftJoin('sysdev.rm_ratings_calendar_sites_interest as RCSI',  function ($join) {
                            $join->on('RCSI.calendar_site_interest', '=', 'CSI.id')->on('RCSI.risk_sites_interests_id', '=', 'RSI.id');
                        })
                      ->select('CSI.id as IdCalendario',
                               DB::raw('1 as Tipo'),
                               'CSI.folio as Folio',
                               'Inspector.name as Inspector',
                               DB::raw('date_format(CSI.date,"%Y-%m-%dT%H:%i:%s") as Fecha'),
                               'SI.name as Descripcion',
                               DB::raw('if(count(RCSI.id)>0,1,0) as respondido')
                               )
                      ->where([['RSI.status', '=', 1]])
                      ->whereBetween(DB::raw('date(CSI.created_at)'), [DB::raw('date_sub(curdate(),interval 1 year)'), DB::raw('curdate()')])
                      ->groupBy('CSI.id')
                      ->union($first)
                      ->orderBy('Folio')
                      ->get();
                      
        
        return $Query;
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
