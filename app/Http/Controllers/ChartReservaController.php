<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Http\Controllers\Controller;
use sistemaReserva\User;
use sistemaReserva\Facultad;
use sistemaReserva\Carrera;
use sistemaReserva\Area;
use sistemaReserva\Horario;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;
use PdfReport;
use ExcelReport;

class ChartReservaController extends Controller
{
    public function __construct(){
    $this->middleware('auth');
   	}

   	public function index(Request $request){
    if($request){

    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
    $sms='';
    $diagrama='';
    $nfechaini='';
  	$nfechafin='';
    $fechaini=trim($request->get('fechaini'));
    $fechafin=trim($request->get('fechafin'));
    $horaini=trim($request->get('horaini'));
    $horafin=trim($request->get('horafin'));
    $area=trim($request->get('areachart'));
    $facu=trim($request->get('facuchart'));
    $carre=trim($request->get('carrechart'));
    $areas=DB::table('area')->where('estado','=','A')->where('disponibilidad','=','Disponible')->get();
    $facultades=DB::table('facultad')->where('estado','=','A')->get();
    $horarios=DB::table('horario')->where('estado','=','A')->orderBy('hora','asc')->get();
    $primerhora= Horario::where('estado','=','A')->orderBy('hora','asc')->first();
    $horariosf=DB::table('horario')->where('hora','!=',$primerhora->hora)->where('estado','=','A')->orderBy('hora','asc')->get();

    if($fechaini!='' && $fechafin!=''){
  	$vhoy=explode("/",$hoy);
	$separa=explode("/",$fechaini);
	$separaf=explode("/",$fechafin);
    $nfechaini=$separa[2]."/".$separa[1]."/".$separa[0]." "."00:00:00";
    $nfechafin=$separaf[2]."/".$separaf[1]."/".$separaf[0]." "."23:59:59";

    $dhoy=$vhoy[0];
    $mhoy=$vhoy[1];
    $ahoy=$vhoy[2];
    $dsepara=$separa[0];
    $msepara=$separa[1];
    $asepara=$separa[2];
    $dseparaf=$separaf[0];
    $mseparaf=$separaf[1];
    $aseparaf=$separaf[2];
    if(($aseparaf<$asepara)||($aseparaf==$asepara && $mseparaf<$msepara)||($aseparaf==$asepara && $mseparaf==$msepara && $dseparaf<=$dsepara)){
	$sms='La fecha seleccionada no es válida';
    }
    if($asepara<$ahoy || $aseparaf<$ahoy){//2017
    $sms='La fecha seleccionada no es válida';
    }
    if($asepara>$ahoy || $aseparaf>$ahoy){//2019
    $sms='La fecha seleccionada no es válida';
    }
	}

	if($horaini!='' && $horafin!=''){
	$vini=explode(":",$horaini);
    $vfin=explode(":",$horafin);
    $vhora=explode(":",$hora);
    $hini=$vini[0];
    $hfin=$vfin[0];
    $hhora=$vhora[0];
    $dif=$hfin-$hini;
    if($dif<1){
    $sms='La hora seleccionada no es válida';
    }
    }

    $areag = DB::table('area as a')
    ->select(array('a.nombre', DB::raw('COUNT(r.idarea) as cantidad')))
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.idarea', '=', 'a.idarea')
    ->groupBy('a.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areae = DB::table('area as a')
    ->select(array('a.nombre', DB::raw('COUNT(r.idarea) as cantidad')))
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$area)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.idarea', '=', 'a.idarea')
    ->groupBy('a.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areagfg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areaefg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$area)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areagfe = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('f.idfacultad','=',$facu)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areaefe = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('f.idfacultad','=',$facu)
    ->where('r.idarea','=',$area)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areagfecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('c.idfacultad','=',$facu)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areagfece = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('c.idfacultad','=',$facu)
    ->where('c.idcarrera','=',$carre)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areaefece = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$area)
    ->where('c.idfacultad','=',$facu)
    ->where('c.idcarrera','=',$carre)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $areaefecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$area)
    ->where('c.idfacultad','=',$facu)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $aglabel=[];
    $agdata=[];
    $aelabel=[];
    $aedata=[];
    $agfglabel=[];
    $agfgdata=[];
    $aefglabel=[];
    $aefgdata=[];
    $agfelabel=[];
    $agfedata=[];
    $aefelabel=[];
    $aefedata=[];
    $agfecglabel=[];
    $agfecgdata=[];
    $agfecelabel=[];
    $agfecedata=[];
    $aefecelabel=[];
    $aefecedata=[];
    $aefecglabel=[];
    $aefecgdata=[];
    $bgcolor=[
    'rgba(255, 159, 64, 0.8)', //naranja
    'rgba(204, 0, 0, 0.8)',	   //rojo
    'rgba(54, 162, 235, 0.8)', //azul
    'rgba(255, 206, 86, 0.8)', //amarillo
    'rgba(75, 192, 192, 0.8)', //verde
    'rgba(153, 102, 255, 0.8)',//morado
    'rgba(255, 99, 132, 0.8)', //rosado
    'rgba(31,210,202,0.8)',    //celeste
    'rgba(128,41,6,0.8)',      //cafe
    'rgba(126,170,0,0.8)',     //verdeclaro
    'rgba(0,76,43,0.8)',       //verdeoscuro
    'rgba(255, 159, 64, 0.5)', //naranja
    'rgba(204, 0, 0, 0.5)',	   //rojo
    'rgba(54, 162, 235, 0.5)', //azul
    'rgba(255, 206, 86, 0.5)', //amarillo
    'rgba(75, 192, 192, 0.5)', //verde
    'rgba(153, 102, 255, 0.5)',//morado
    'rgba(255, 99, 132, 0.5)', //rosado
    'rgba(31,210,202,0.5)',    //celeste
    'rgba(128,41,6,0.5)',      //cafe
    'rgba(126,170,0,0.5)',     //verdeclaro
    'rgba(0,76,43,0.5)'        //verdeoscuro
    ];

    foreach($areag as $ag){
    $aglabel[]=$ag->nombre;
    $agdata[]=$ag->cantidad;
    }
    $chartag = app()->chartjs->name('DiagramaReservaAreaGeneral')->type('bar')
    ->labels($aglabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $agdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areae as $ae){
    $aelabel[]=$ae->nombre;
    $aedata[]=$ae->cantidad;
    }
    $chartae = app()->chartjs->name('DiagramaReservaAreaEspecifico')->type('bar')
    ->labels($aelabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $aedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areagfg as $agfg){
    $agfglabel[]=$agfg->nombre;
    $agfgdata[]=$agfg->cantidad;
    }
    $chartagfg = app()->chartjs->name('DiagramaReservaAreaGENFacultadGEN')->type('bar')
    ->labels($agfglabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $agfgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areaefg as $aefg){
    $aefglabel[]=$aefg->nombre;
    $aefgdata[]=$aefg->cantidad;
    }
    $chartaefg = app()->chartjs->name('DiagramaReservaAreaESPFacultadGEN')->type('bar')
    ->labels($aefglabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $aefgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areagfe as $agfe){
    $agfelabel[]=$agfe->nombre;
    $agfedata[]=$agfe->cantidad;
    }
    $chartagfe = app()->chartjs->name('DiagramaReservaAreaGENFacultadESP')->type('bar')
    ->labels($agfelabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $agfedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areaefe as $aefe){
    $aefelabel[]=$aefe->nombre;
    $aefedata[]=$aefe->cantidad;
    }
    $chartaefe = app()->chartjs->name('DiagramaReservaAreaESPFacultadESP')->type('bar')
    ->labels($aefelabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $aefedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areagfecg as $agfecg){
    $agfecglabel[]=$agfecg->nombre;
    $agfecgdata[]=$agfecg->cantidad;
    }
    $chartagfecg = app()->chartjs->name('DiagramaReservaAreaGENFacultadESPCarreraGEN')->type('bar')
    ->labels($agfecglabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $agfecgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areagfece as $agfece){
    $agfecelabel[]=$agfece->nombre;
    $agfecedata[]=$agfece->cantidad;
    }
    $chartagfece = app()->chartjs->name('DiagramaReservaAreaGENFacultadESPCarreraESP')->type('bar')
    ->labels($agfecelabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $agfecedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areaefece as $aefece){
    $aefecelabel[]=$aefece->nombre;
    $aefecedata[]=$aefece->cantidad;
    }
    $chartaefece = app()->chartjs->name('DiagramaReservaAreaESPFacultadESPCarreraESP')->type('bar')
    ->labels($aefecelabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $aefecedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($areaefecg as $aefecg){
    $aefecglabel[]=$aefecg->nombre;
    $aefecgdata[]=$aefecg->cantidad;
    }
    $chartaefecg = app()->chartjs->name('DiagramaReservaAreaESPFacultadESPCarreraGEN')->type('bar')
    ->labels($aefecglabel)
    ->datasets([[
    	'label' => 'Reservas',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $aefecgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>12, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>12, 'fontColor'=>'#000']]]
    	]
    ]);

    if($sms=='' && $area==999 && $facu==0 && $carre==0){
    $rcont = $areag->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areag';}	
    }
    if($sms=='' && $area!=999 && $area!=0 && $facu==0 && $carre==0){
    $rcont = $areae->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areae';}	
    }
    if($sms=='' && $area==999 && $facu==999 && $carre==0){
    $rcont = $areagfg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areagfg';}	
    }
    if($sms=='' && $area!=999 && $area!=0 && $facu==999 && $carre==0){
    $rcont = $areaefg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areaefg';}	
    }
    if($sms=='' && $area==999 && $facu!=999 && $facu!=0 && $carre==0){
    $rcont = $areagfe->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areagfe';}	
    }
    if($sms=='' && $area!=999 && $area!=0 && $facu!=999 && $facu!=0 && $carre==0){
    $rcont = $areaefe->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areaefe';}	
    }
    if($sms=='' && $area==999 && $facu!=999 && $facu!=0 && $carre==999){
    $rcont = $areagfecg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areagfecg';}	
    }
    if($sms=='' && $area==999 && $facu!=999 && $facu!=0 && $carre!=999 && $carre!=0){
    $rcont = $areagfece->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areagfece';}	
    }
    if($sms=='' && $area!=999 && $area!=0 && $facu!=999 && $facu!=0 && $carre!=999 && $carre!=0){
    $rcont = $areaefece->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areaefece';}	
    }
    if($sms=='' && $area!=999 && $area!=0 && $facu!=999 && $facu!=0 && $carre==999){
    $rcont = $areaefecg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='areaefecg';}	
    }

    if(($request->boton=='') || ($request->boton=='consultar') || ($request->boton=='pdf' && $sms!='') || ($request->boton=='excel' && $sms!='')){
        if(Auth::user()->idtipousuario<2){
        return view("reporte.chartreserva.index",["sms"=>$sms,"fechaini"=>$fechaini,"fechafin"=>$fechafin,"horaini"=>$horaini,"horafin"=>$horafin,"areas"=>$areas,"facultades"=>$facultades,"facu"=>$facu,"carre"=>$carre,"area"=>$area,"horarios"=>$horarios,"horariosf"=>$horariosf,"hoy"=>$hoy,"diagrama"=>$diagrama,"chartag"=>$chartag,"chartae"=>$chartae,"chartagfg"=>$chartagfg,"chartaefg"=>$chartaefg,"chartagfe"=>$chartagfe,"chartaefe"=>$chartaefe,"chartagfecg"=>$chartagfecg,"chartagfece"=>$chartagfece,"chartaefece"=>$chartaefece,"chartaefecg"=>$chartaefecg]);
        }
        else{
        return Redirect::to('/logout');
        } 
    }
    else{
    return $this->displayReport($request);
    }
    }
	}

   	public function getCarre($idfacu) {
        $carreras = DB::table('carrera')
        ->where('estado','=','A')
        ->where('idfacultad','=',$idfacu)
        ->pluck('nombre','idcarrera');
        return json_encode($carreras);
    }

    public function displayReport(Request $request){
    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
    $ini=explode("/",$request->get('fechaini'));
    $fin=explode("/",$request->get('fechafin'));
    $nfechaini=$ini[2]."/".$ini[1]."/".$ini[0]." "."23:59:59";
    $nfechafin=$fin[2]."/".$fin[1]."/".$fin[0]." "."23:59:59";
    $desde=$ini[0]."/".$ini[1]."/".$ini[2];
    $hasta=$fin[0]."/".$fin[1]."/".$fin[2];
    $horaini=$request->get('horaini');
    $horafin=$request->get('horafin');
    $a=trim($request->get('areachart'));
    $f=trim($request->get('facuchart'));
    $c=trim($request->get('carrechart'));

    if($a==999 && $f==0 && $c==0){$palabra='areag';  }
    if($a!=999 && $a!=0 && $f==0 && $c==0){$palabra='areae';}
    if($a==999 && $f==999 && $c==0){$palabra='areagfg';}
    if($a!=999 && $a!=0 && $f==999 && $c==0){$palabra='areaefg';}
    if($a==999 && $f!=999 && $f!=0 && $c==0){$palabra='areagfe';}
    if($a!=999 && $a!=0 && $f!=999 && $f!=0 && $c==0){$palabra='areaefe';}
    if($a==999 && $f!=999 && $f!=0 && $c==999){$palabra='areagfecg';}
    if($a==999 && $f!=999 && $f!=0 && $c!=999 && $c!=0){$palabra='areagfece';}
    if($a!=999 && $a!=0 && $f!=999 && $f!=0 && $c!=999 && $c!=0){$palabra='areaefece';}
    if($a!=999 && $a!=0 && $f!=999 && $f!=0 && $c==999){$palabra='areaefecg';}

    if($a!=999){
    $variable=Area::where('idarea',$a)->first();
    $ameta=$variable->nombre;
    }
    if($f!=0 && $f!=999){
    $variable=Facultad::where('idfacultad',$f)->first();
    $fmeta=$variable->nombre;
    }
    if($c!=0 && $c!=999){
    $variable=Carrera::where('idcarrera',$c)->first();
    $cmeta=$variable->nombre;
    }
    if($a==999){$ameta='Todas';}
    if($f==999){$fmeta='Todas';}
    if($c==999){$cmeta='Todas';}
    if($f==0){$fmeta='N/A';}
    if($c==0){$cmeta='N/A'; }
    
    $title = 'Reporte Número de Reservas'; 
    $meta = ['Desde'=>$desde,'Hasta'=>$hasta,'Hora inicio'=>$horaini,'Hora final'=>$horafin,'Facultad'=>$fmeta,'Carrera'=>$cmeta,'Área de estudio'=>$ameta];

    $areag = DB::table('area as a')
    ->select(array('a.nombre', DB::raw('COUNT(r.idarea) as cantidad')))
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.idarea', '=', 'a.idarea')
    ->groupBy('a.nombre')
    ->orderBy('cantidad', 'desc');
    $n1 = DB::table('area as a')
    ->select(array('a.nombre', DB::raw('COUNT(r.idarea) as cantidad')))
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.idarea', '=', 'a.idarea')
    ->groupBy('a.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();
    $tot1=$n1->sum('cantidad');
    $max1=0;
    $min1=0;
    $nomax1='';
    $nomin1='';
    foreach ($n1 as $n) {
    if($n->cantidad>=$max1){
    $max1=$n->cantidad;
    $nomax1=$n->nombre;
    }
    }
    foreach ($n1 as $n) {
    if($n->cantidad<$max1 || $min1==0){
    $min1=$n->cantidad;
    $nomin1=$n->nombre;
    }
    }
    $careag = [
    'Área de estudio'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $areae = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.idarea) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc');
    $n2 = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.idarea) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc')
    ->get();
    $tot2=$n2->sum('cantidada');
    $nomax2=round($n2->avg('cantidad'),0);
    $careae = [
    'Usuario'=>'name',
    'Ocupantes'=>'cantidad',
    'Cantidad'=>'cantidada'
    ];

    $areagfg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc');
    $n3 = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();
    $tot3=$n3->sum('cantidad');
    $max3=0;
    $min3=0;
    $nomax3='';
    $nomin3='';
    foreach ($n3 as $n) {
    if($n->cantidad>=$max3){
    $max3=$n->cantidad;
    $nomax3=$n->nombre;
    }
    }
    foreach ($n3 as $n) {
    if($n->cantidad<$max3 || $min3==0){
    $min3=$n->cantidad;
    $nomin3=$n->nombre;
    }
    }
    $careagfg = [
    'Facultad'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $areaefg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc');
    $n4 = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();
    $tot4=$n4->sum('cantidad');
    $max4=0;
    $min4=0;
    $nomax4='';
    $nomin4='';
    foreach ($n4 as $n) {
    if($n->cantidad>=$max4){
    $max4=$n->cantidad;
    $nomax4=$n->nombre;
    }
    }
    foreach ($n4 as $n) {
    if($n->cantidad<$max4 || $min4==0){
    $min4=$n->cantidad;
    $nomin4=$n->nombre;
    }
    }
    $careaefg = [
    'Facultad'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $areagfe = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.idarea) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('u.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc');
    $n5 = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.idarea) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('u.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc')
    ->get();
    $tot5=$n5->sum('cantidada');
    $nomax5=round($n5->avg('cantidad'),0);
    $careagfe = [
    'Usuario'=>'name',
    'Ocupantes'=>'cantidad',
    'Cantidad'=>'cantidada'
    ];

    $areaefe = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.idarea) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('a.idarea','=',$a)
    ->where('u.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc');
    $n6 = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.idarea) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('a.idarea','=',$a)
    ->where('u.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc')
    ->get();
    $tot6=$n6->sum('cantidada');
    $nomax6=round($n6->avg('cantidad'),0);
    $careaefe = [
    'Usuario'=>'name',
    'Ocupantes'=>'cantidad',
    'Cantidad'=>'cantidada'
    ];

    $areagfecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('c.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc');
    $n7 = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('c.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();
    $tot7=$n7->sum('cantidad');
    $max7=0;
    $min7=0;
    $nomax7='';
    $nomin7='';
    foreach ($n7 as $n) {
    if($n->cantidad>=$max7){
    $max7=$n->cantidad;
    $nomax7=$n->nombre;
    }
    }
    foreach ($n7 as $n) {
    if($n->cantidad<$max7 || $min7==0){
    $min7=$n->cantidad;
    $nomin7=$n->nombre;
    }
    }
    $careagfecg = [
    'Carrera'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $areagfece = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.id) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc');
    $n8 = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.id) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc')
    ->get();
    $tot8=$n8->sum('cantidada');
    $nomax8=round($n8->avg('cantidad'),0);
    $careagfece = [
    'Usuario'=>'name',
    'Ocupantes'=>'cantidad',
    'Cantidad'=>'cantidada'
    ];

    $areaefece = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.id) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc');
    $n9 = DB::table('users as u')
    ->select(array('u.name', 'r.cantidad', DB::raw('COUNT(r.id) as cantidada')))
    ->where('u.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('u.name','r.cantidad')
    ->orderBy('u.name', 'asc')
    ->get();
    $tot9=$n9->sum('cantidada');
    $nomax9=round($n9->avg('cantidad'),0);
    $careaefece = [
    'Usuario'=>'name',
    'Ocupantes'=>'cantidad',
    'Cantidad'=>'cantidada'
    ];

    $areaefecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('c.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc');
    $n10 = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(r.id) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('a.estado', '=', 'A')
    ->where('a.disponibilidad','=','Disponible')
    ->where('r.idarea','=',$a)
    ->where('c.idfacultad','=',$f)
    ->where('r.fechainicio','>=',$nfechaini)
    ->where('r.fechainicio','<',$nfechafin)
    ->where('r.horainicio','>=',$horaini)
    ->where('r.horafinal','>=',$horaini)
    ->where('r.horainicio','<=',$horafin)
    ->where('r.horafinal','<=',$horafin)
    ->leftjoin('users as u','u.idcarrera','=','c.idcarrera')
    ->leftjoin('reserva as r', 'r.id', '=', 'u.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();
    $tot10=$n10->sum('cantidad');
    $max10=0;
    $min10=0;
    $nomax10='';
    $nomin10='';
    foreach ($n10 as $n) {
    if($n->cantidad>=$max10){
    $max10=$n->cantidad;
    $nomax10=$n->nombre;
    }
    }
    foreach ($n10 as $n) {
    if($n->cantidad<$max10 || $min10==0){
    $min10=$n->cantidad;
    $nomin10=$n->nombre;
    }
    }
    $careaefecg = [
    'Carrera'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    if($palabra=='areag'){$queryBuilder=$areag; $columns=$careag; $tot=$tot1; $nomax=$nomax1; $nomin=$nomin1; $nombre='AreaGeneral';}
    if($palabra=='areae'){$queryBuilder=$areae; $columns=$careae; $tot=$tot2; $nomax=$nomax2; $nomin=''; $nombre='AreaEspecifica';}
    if($palabra=='areagfg'){$queryBuilder=$areagfg; $columns=$careagfg; $tot=$tot3; $nomax=$nomax3; $nomin=$nomin3; $nombre='AreaGeneralFacultadGeneral';}
    if($palabra=='areaefg'){$queryBuilder=$areaefg; $columns=$careaefg; $tot=$tot4; $nomax=$nomax4; $nomin=$nomin4; $nombre='AreaEspecificaFacultadGeneral';}
    if($palabra=='areagfe'){$queryBuilder=$areagfe; $columns=$careagfe; $tot=$tot5; $nomax=$nomax5; $nomin=''; $nombre='AreaGeneralFacultadEspecifica';}
    if($palabra=='areaefe'){$queryBuilder=$areaefe; $columns=$careaefe; $tot=$tot6; $nomax=$nomax6; $nomin=''; $nombre='AreaEspecificaFacultadEspecifica';}
    if($palabra=='areagfecg'){$queryBuilder=$areagfecg; $columns=$careagfecg; $tot=$tot7; $nomax=$nomax7; $nomin=$nomin7; $nombre='AreaGENFacultadESPCarreraGEN';}
    if($palabra=='areagfece'){$queryBuilder=$areagfece; $columns=$careagfece; $tot=$tot8; $nomax=$nomax8; $nomin=''; $nombre='AreaGENFacultadESPCarreraESP';}
    if($palabra=='areaefece'){$queryBuilder=$areaefece; $columns=$careaefece; $tot=$tot9; $nomax=$nomax9; $nomin=''; $nombre='AreaESPFacultadESPCarreraESP';}
    if($palabra=='areaefecg'){$queryBuilder=$areaefecg; $columns=$careaefecg; $tot=$tot10; $nomax=$nomax10; $nomin=$nomin10; $nombre='AreaESPFacultadESPCarreraGEN';}

    $op='reser';
    if($request->boton=='pdf'){
    return PdfReport::of($title, $meta, $queryBuilder, $columns, $palabra, $tot, $nomax, $nomin,$hoy,$hora,$op)
    ->setCss(['.head-content' => 'border-width: 1px',])
    ->limit(50)
    ->stream();
	}
	else{
    return ExcelReport::of($title, $meta, $queryBuilder, $columns, $palabra, $tot, $nomax, $nomin,$hoy,$hora,$op)
    ->setCss(['.head-content' => 'border-width: 1px',])
    ->limit(50)
    ->simpleDownload($nombre); 
    }
    }
}
