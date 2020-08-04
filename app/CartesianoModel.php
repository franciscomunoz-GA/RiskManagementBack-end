<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class CartesianoModel extends Model
{
    public function SeleccionarDCartesiano($Parametros){
        $Id = $Parametros['Id'];
        $Tipo = $Parametros['Tipo'];

        if($Tipo == 2){
            $Query = $this->from('sysdev.rm_calendars_client as CalCli')
                          ->join('sysdev.users as Inspector', 'Inspector.id', '=', 'CalCli.inspector_id')
                          ->join('sysdev.clients as cliente', 'cliente.id', '=', 'CalCli.client_id')
                          ->select( 'CalCli.id as Id', 
                                    'CalCli.folio as Folio',
                                    'Inspector.name as Inspector',
                                    DB::raw('date_format(CalCli.date,"%d-%m-%Y %H:%i:%s") as Fecha'),
                                    'cliente.id as IdCliente',
                                    'cliente.commercial_name as Cliente')
                          ->where ([['CalCli.id', '=', $Id]]) 
                          ->get();
            $data = [];
            foreach ($Query as $index => $row) {
                    $Parametros = array('IdCalendario' => $row->Id);
                    $Areas = $this->ObtenerAreas($Parametros);
                    $Fila = array('Id'        => $row->Id,
                                  'Folio'     => $row->Folio,
                                  'Inspector' => $row->Inspector,
                                  'Fecha'     => $row->Fecha,
                                  'Cliente'   => $row->Cliente,
                                  'Areas'     => $Areas);
                    $data[$index]=$Fila;
            }
        }

        elseif($Tipo == 1){
            $Query = $this->from('sysdev.rm_calendars_sites_interest as CSI')
                          ->join('sysdev.users as Inspector', 'Inspector.id', '=', 'CSI.inspector_id')
                          ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'CSI.sites_interest_id')
                          ->select( 'CSI.id as Id', 
                                    'CSI.folio as Folio',
                                    'Inspector.name as Inspector',
                                    DB::raw('date_format(CSI.date,"%d-%m-%Y %H:%i:%s") as Fecha'),
                                    'SI.id as IdSitioInteres',
                                    'SI.name as SitioInteres')
                          ->where ([['CSI.id', '=', $Id]]) 
                          ->get();
            $data = [];
            foreach ($Query as $index => $row) {
                    $Parametros = array('IdCalendario' => $row->Id);
                    $Riesgos = $this->ObtenerRiesgosSI($Parametros);
                    $Fila = array('Id'           => $row->Id,
                                  'Folio'        => $row->Folio,
                                  'Inspector'    => $row->Inspector,
                                  'Fecha'        => $row->Fecha,
                                  'SitioInteres' => $row->SitioInteres,
                                  'Riesgos'      => $Riesgos);
                    $data[$index]=$Fila;
            }
        }

        return $data;
    }

    public function ModificarRespuestas($Parametros){
        $Datos     = $Parametros['Datos'];
        $Tipo      = $Parametros['Tipo'];
        $IdUsuario = $Parametros['IdUsuario'];

        if($Tipo == 2){
            foreach ($Datos as $index => $row) {
                $Query = DB::table('sysdev.rm_ratings_calendar_clients')
                              ->where('id', $row->IdRespuestas)
                              ->update(['probability'    => $row->Probabilidad,
                                        'impact'         => $row->Impacto,
                                        'user_id_update' => $IdUsuario]);
                }
        }
        elseif($Tipo == 1){
            foreach ($Datos as $index => $row) {
                $Query = DB::table('sysdev.rm_ratings_calendar_sites_interest')
                            ->where('id', $row->IdRespuestas)
                            ->update(['probability'    => $row->Probabilidad,
                                      'impact'         => $row->Impacto,
                                      'user_id_update' => $IdUsuario]);
                }
        }

        return $Query;
    }

    private function ObtenerAreas($parametros){
        $IdCalendario = $parametros['IdCalendario'];
        
        $Areas = $this->from('sysdev.rm_calendars_client as CalCli')
                        ->join('sysdev.rm_clients_risks_areas as CRA', 'CRA.client_id', '=', 'CalCli.client_id')
                        ->join('sysdev.rm_nombre_areas as NA', 'NA.id', '=', 'CRA.risk_area_id')
                        ->join('sysdev.rm_areas as Area', 'Area.id', '=', 'NA.area_id')

                        ->select('CRA.id as idClientRiskArea',
                                 'CRA.name as NombreClientRiskArea', 
                                 'NA.id as IdNombreArea',
                                 'NA.name as NombreArea',
                                 'Area.id as IdArea',
                                 'Area.name as Area')
                        ->where([['CalCli.id', '=', $IdCalendario],
                                 ['CRA.status', '=', 1],
                                 ['NA.status', '=', 1],
                                 ['Area.status', '=', 1]])
                        ->orderBy('NombreClientRiskArea')                   
                        ->get();
        $data = [];
        foreach ($Areas as $index => $row) {
            $Parametros = array('IdNombreArea' => $row->IdNombreArea,
                                'IdCalendario' => $IdCalendario);
            $Riesgos = $this->ObtenerRiesgos($Parametros);
            $Fila = array('NombreClientRiskArea' => $row->NombreClientRiskArea,
                          'NombreArea'           => $row->NombreArea,
                          'IdArea'               => $row->IdArea,
                          'Area'                 => $row->Area,
                          'Riesgos'              => $Riesgos);
            $data[$index]=$Fila;
        }

        return $data;
    }

    private function ObtenerRiesgos($parametros){
        $IdNombreArea = $parametros['IdNombreArea'];
        $IdCalendario = $parametros['IdCalendario'];
        
        $Riesgos = $this->from('sysdev.rm_calendars_client as CalCli')
                        ->join('sysdev.rm_clients_risks_areas as CRA', 'CRA.client_id', '=', 'CalCli.client_id')
                        ->join('sysdev.rm_nombre_areas as NA', 'NA.id', '=', 'CRA.risk_area_id')
                        ->join('sysdev.rm_areas as Area', 'Area.id', '=', 'NA.area_id')
                        ->join('sysdev.rm_nombre_areas_risks as NAR', 'NAR.nombre_areas_id', '=', 'NA.id')
                        ->join('sysdev.rm_risks as Riesgo', 'Riesgo.id', '=', 'NAR.risk_id')
                        ->leftJoin('sysdev.rm_ratings_calendar_clients as RCC', function ($join) {
                            $join->on('CalCli.id', '=', 'RCC.calendar_client_id')->on('NA.id', '=', 'RCC.nombre_area_id')->on('NAR.id', '=', 'RCC.nombre_area_risk_id');
                        })

                        ->select(DB::raw('ifnull(RCC.id,"") as IdRespuestas'),
                                 'Riesgo.name as Riesgo',
                                  DB::raw('ifnull(RCC.probability,"") as Probabilidad'), 
                                  DB::raw('ifnull(RCC.impact,"") as Impacto'))
                        ->where([['CalCli.id', '=', $IdCalendario],
                                 ['NA.id', '=', $IdNombreArea],
                                 ['CRA.status', '=', 1],
                                 ['NA.status', '=', 1],
                                 ['Area.status', '=', 1],
                                 ['NAR.status', '=', 1],
                                 ['Riesgo.status', '=', 1]])
                        ->orderBy('Riesgo')                   
                        ->get();
        return $Riesgos;
    }

    private function ObtenerRiesgosSI($parametros){
        $IdCalendario = $parametros['IdCalendario'];
        
        $Riesgos = $this->from('sysdev.rm_calendars_sites_interest as CSI')
                        ->join('sysdev.rm_risk_sites_interest as RSI', 'RSI.sites_of_interest_id', '=', 'CSI.sites_interest_id')
                        ->join('sysdev.rm_risks as Riesgo', 'Riesgo.id', '=', 'RSI.risk_id')
                        ->leftJoin('sysdev.rm_ratings_calendar_sites_interest as RCSI', function ($join) {
                            $join->on('RCSI.calendar_site_interest', '=', 'CSI.id')->on('RCSI.risk_sites_interests_id', '=', 'RSI.id');
                        })
                        ->select(DB::raw('ifnull(RCSI.id,"") as IdRespuestas'),
                                 'Riesgo.id as IdRiesgo', 
                                 'Riesgo.name as Riesgo',
                                 DB::raw('ifnull(RCSI.probability,"") as Probabilidad'),
                                 DB::raw('ifnull(RCSI.impact,"") as Impacto'))
                        ->where([['CSI.id', '=', $IdCalendario],
                                 ['RSI.status', '=', 1],
                                 ['Riesgo.status', '=', 1]])
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
}
