<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class EncuestaAplicacionModel extends Model
{
    public function ServiciosInspector($parametros){
        $Datos = $parametros['Datos'];
        try{
            $first = $this->from('sysdev.rm_calendars_client as CalCli')
                          ->join('sysdev.clients as cliente', 'cliente.id', '=', 'CalCli.client_id')
                          ->join('sysdev.rm_clients_risks_areas as CRA', 'CRA.client_id', '=', 'CalCli.client_id')
                          ->join('sysdev.rm_nombre_areas as NA', 'NA.id', '=', 'CRA.risk_area_id')
                          ->join('sysdev.rm_nombre_areas_risks as NAR', 'NAR.nombre_areas_id', '=', 'NA.id')
                          ->leftJoin('sysdev.rm_ratings_calendar_clients as RCC',  function ($join) {
                                $join->on('CalCli.id', '=', 'RCC.calendar_client_id')->on('NA.id', '=', 'RCC.nombre_area_id')->on('NAR.id', '=', 'RCC.nombre_area_risk_id');
                             })
                          ->select('CalCli.id as Id',
                                   DB::raw('2 as Tipo'),
                                   'CalCli.folio as Descripcion',
                                   DB::raw('date_format(CalCli.date,"%Y-%m-%d") as Fecha'),
                                   DB::raw('date_format(CalCli.date,"%H:%i:%s") as Hora'),
                                   'cliente.commercial_name as Titulo'
                                  )
                          ->where([ ['CalCli.inspector_id', '=',$Datos['Id']],
                                    ['CalCli.status_delete', '=', 1],
                                    ['CRA.status', '=', 1],
                                    ['NA.status', '=', 1],
                                    ['NAR.status', '=', 1],
                                    //['RCC.id', '=', null],
                                    [DB::raw('date(CalCli.date)'), '=', $Datos['Fecha']]])
                          ->groupBy('CalCli.id');

            $Query = $this->from('sysdev.rm_calendars_sites_interest as CSI')
                        ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'CSI.sites_interest_id')
                        ->join('sysdev.rm_risk_sites_interest as RSI', 'RSI.sites_of_interest_id', '=', 'CSI.sites_interest_id')
                        ->leftJoin('sysdev.rm_ratings_calendar_sites_interest as RCSI',  function ($join) {
                                $join->on('RCSI.calendar_site_interest', '=', 'CSI.id')->on('RCSI.risk_sites_interests_id', '=', 'RSI.id');
                            })
                        ->select('CSI.id as Id',
                                DB::raw('1 as Tipo'),
                                'CSI.folio as Descripcion',
                                DB::raw('date_format(CSI.date,"%Y-%m-%d") as Fecha'),
                                DB::raw('date_format(CSI.date,"%H:%i:%s") as Hora'),
                                'SI.name as Titulo'
                                )
                        ->where([['CSI.inspector_id', '=',$Datos['Id']],
                                 ['CSI.status_delete', '=', 1],
                                 ['RSI.status', '=', 1],
                                 //['RCSI.id', '=', null],
                                 [DB::raw('date(CSI.date)'),'=', $Datos['Fecha']]])
                        ->groupBy('CSI.id')
                        ->union($first)
                        ->orderBy('Descripcion')
                        ->get();
                        
            
            return $Query;
        } catch (\Throwable $th) {
            $Error = array('Error' => 'Error al obtener datos',
                           'Detalle' =>$th );
            return $Error;
        }
    }

    public function SeleccionarEncuesta($parametros){
        $Datos = $parametros['Datos'];
        try{
            if($Datos['Tipo'] == 1){
                $Query = $this->from('sysdev.rm_calendars_sites_interest as CalSI')
                              ->join('sysdev.sites_of_interest as SI', 'SI.id', '=', 'CalSI.sites_interest_id')
                              ->select('CalSI.id as IdEncuesta',
                                       'SI.name as SitioInteres'
                                    )
                             ->where([['CalSI.id', '=',$Datos['Id']]])
                             ->get();
                $data = [];
                foreach ($Query as $index => $row) {
                    $Parametros = array('IdEncuesta' => $row->IdEncuesta,
                                        'IdNombreArea' => 0,
                                        'Tipo'       => $Datos['Tipo']);
                    $Riesgos = $this->ObtenerRiesgos($Parametros);
                         $Fila = array('IdEncuesta'        => $row->IdEncuesta,
                                        'Titulo'     => $row->SitioInteres,
                                        'Riesgos' => $Riesgos);
                    array_push($data, $Fila);
                }
            }
            elseif($Datos['Tipo'] == 2){
                $Query = $this->from('sysdev.rm_calendars_client as CalCli')
                              ->join('sysdev.clients as Cliente', 'Cliente.id', '=', 'CalCli.client_id')
                              ->select('CalCli.id               as IdEncuesta',
                                       'Cliente.commercial_name as Cliente'
                                    )
                             ->where([['CalCli.id', '=',$Datos['Id']]])
                             ->get();
                $data = [];
                foreach ($Query as $index => $row) {
                    $Parametros = array('IdEncuesta' => $row->IdEncuesta,
                                        'Tipo'       => $Datos['Tipo']);
                    $Areas = $this->ObtenerAreas($Parametros);
                         $Fila = array('IdEncuesta' => $row->IdEncuesta,
                                        'Titulo'    => $row->Cliente,
                                        'Areas'     => $Areas);
                    array_push($data, $Fila);
                }
            }
            return $data;
        } catch (\Throwable $th) {
            $Error = array('Error' => 'Error al obtener datos',
                           'Detalle' =>$th );
            return $Error;
        }
    }

    public function ResponderEncuesta($parametros){
        $Datos = $parametros['Datos'];
        try {
            $data = [];
            if(1 == $Datos['Tipo']){
                foreach ($Datos['Riesgos'] as $index => $row) {
                    $Fila =['calendar_site_interest'  => $Datos['IdEncuesta'],
                            'risk_sites_interests_id' => $row['IdRSI'],
                            'probability'             => $row['Probabilidad'],
                            'impact'                  => $row['Impacto'],
                            'user_id'                 => $Datos['IdUsuario']
                            ];
                            array_push($data, $Fila);  
                }
                $Query = DB::table('sysdev.rm_ratings_calendar_sites_interest')
                ->insert($data);
            }
            elseif(2 == $Datos['Tipo']){
                foreach ($Datos['Areas'] as $index => $areas) { 
                    foreach ($areas['Riesgos'] as $index => $riesgos) {
                    $Fila =['calendar_client_id'  => $Datos['IdEncuesta'],
                            'nombre_area_id'      => $areas['IdNombreArea'],
                            'nombre_area_risk_id' => $riesgos['IdNombreARiesgo'],
                            'probability'         => $riesgos['Probabilidad'],
                            'impact'              => $riesgos['Impacto'],
                            'user_id'             => $Datos['IdUsuario']
                            ];
                            array_push($data, $Fila);  
                    } 
                }
              $Query = DB::table('sysdev.rm_ratings_calendar_clients')
                            ->insert($data);
            }
            
            return $Query;
        } catch (\Throwable $th) {
            $Error = array('Error' => 'Error al guardar datos',
                           'Detalle' =>$th );
            return $Error;
        }
    }

    private function ObtenerAreas($parametros){
        $IdEncuesta = $parametros['IdEncuesta'];
        $Tipo       = $parametros['Tipo'];

        $Areas = $this->from('sysdev.rm_calendars_client as CalCli')
                            ->join('sysdev.rm_clients_risks_areas as CRA', 'CRA.client_id', '=', 'CalCli.client_id')
                            ->join('sysdev.rm_nombre_areas as NA', 'NA.id', '=', 'CRA.risk_area_id')
                            ->join('sysdev.rm_areas as Area', 'Area.id', '=', 'NA.area_id')
                            ->select('NA.id as IdNombreArea',
                                     'NA.name as NombreArea',
                                     'Area.name as Area')
                            ->where([['CalCli.id', '=', $IdEncuesta],
                                    ['CRA.status', '=', 1],
                                    ['NA.status', '=', 1],
                                    ['Area.status', '=', 1]])
                            ->orderBy('NombreArea')                   
                            ->get();
        $data = [];
        foreach ($Areas as $index => $row) {
            $Parametros = array('IdEncuesta'   => $IdEncuesta,
                                'IdNombreArea' => $row->IdNombreArea,
                                'Tipo'         => $Tipo);
            $Riesgos = $this->ObtenerRiesgos($Parametros);
            $Fila = array('IdNombreArea' => $row->IdNombreArea,
                          'NombreArea'   => $row->NombreArea,
                          'Area'         => $row->Area,
                          'Riesgos'      => $Riesgos);
            array_push($data, $Fila);
        }
        return $data;
    }
    
    private function ObtenerRiesgos($parametros){
        $IdEncuesta   = $parametros['IdEncuesta'];
        $IdNombreArea = $parametros['IdNombreArea'];
        $Tipo         = $parametros['Tipo'];

        if($Tipo == 1){
        
            $Riesgos = $this->from('sysdev.rm_calendars_sites_interest as CalSI')
                            ->join('sysdev.rm_risk_sites_interest as RSI', 'RSI.sites_of_interest_id', '=', 'CalSI.sites_interest_id')
                            ->join('sysdev.rm_risks as Riesgos', 'Riesgos.id', '=', 'RSI.risk_id')
                            ->leftJoin('sysdev.rm_ratings_calendar_sites_interest as RCSI',  function ($join) {
                                $join->on('RCSI.calendar_site_interest', '=', 'CalSI.id')->on('RCSI.risk_sites_interests_id', '=', 'RSI.id');
                            })
                            ->select('RSI.id as IdRSI',
                                     DB::raw('ifnull(RCSI.id,"") as IdRespuesta'),
                                    'Riesgos.name as Riesgo',
                                    DB::raw('ifnull(RCSI.probability,"") as Probabilidad'),
                                    DB::raw('ifnull(RCSI.impact,"") as Impacto'))
                            ->where([['CalSI.id', '=', $IdEncuesta],
                                    ['RSI.status', '=', 1],
                                    ['Riesgos.status', '=', 1]])
                            ->orderBy('Riesgo')                   
                            ->get();
        }
        elseif($Tipo == 2){
        
            $Riesgos = $this->from('sysdev.rm_calendars_client as CalCli')
                            ->join('sysdev.rm_clients_risks_areas as CRA', 'CRA.client_id', '=', 'CalCli.client_id')
                            ->join('sysdev.rm_nombre_areas_risks as NAR', 'NAR.nombre_areas_id', '=', 'CRA.risk_area_id')
                            ->join('sysdev.rm_risks as Riesgos', 'Riesgos.id', '=', 'NAR.risk_id')
                            ->select('NAR.id as IdNombreARiesgo',
                                     'Riesgos.name as Riesgo')
                            ->where([['CalCli.id', '=', $IdEncuesta],
                                     ['NAR.nombre_areas_id', '=', $IdNombreArea],
                                     ['CRA.status', '=', 1],
                                     ['NAR.status', '=', 1],
                                     ['Riesgos.status', '=', 1]])
                            ->orderBy('Riesgo')                   
                            ->get();
        }
        return $Riesgos;
    }
}
