<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Http\Controllers\Controller;
use sistemaReserva\User;
use sistemaReserva\Facultad;
use sistemaReserva\Carrera;
use sistemaReserva\Rol;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

class ChartController extends Controller
{
    public function __construct(){
    $this->middleware('auth');
   	}

   	public function index(Request $request){
    if($request){
    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
    $limitefecha='01/07/2018';
    $sms='';
    $diagrama='';
    $nfechaini='';
  	$nfechafin='';
    $fechaini=trim($request->get('fechaini'));
    $fechafin=trim($request->get('fechafin'));
    $tipousu=trim($request->get('tipousuchart'));
    $facu=trim($request->get('facuchart'));
    $carre=trim($request->get('carrechart'));
    $roles=DB::table('tipousuario')->where('estado','=','A')->where('idtipousuario','>',2)->get();
    $facultades=DB::table('facultad')->where('estado','=','A')->get();
  	
  	if($fechaini!='' && $fechafin!=''){
  	$vhoy=explode("/",$hoy);
    $vlimite=explode("/",$limitefecha);
	$separa=explode("/",$fechaini);
	$separaf=explode("/",$fechafin);
    $nfechaini=$separa[2]."/".$separa[1]."/".$separa[0]." "."23:59:59";
    $nfechafin=$separaf[2]."/".$separaf[1]."/".$separaf[0]." "."23:59:59";

    $dhoy=$vhoy[0];
    $mhoy=$vhoy[1];
    $ahoy=$vhoy[2];
    $dlimite=$vlimite[0];
    $mlimite=$vlimite[1];
    $alimite=$vlimite[2];
    $dsepara=$separa[0];
    $msepara=$separa[1];
    $asepara=$separa[2];
    $dseparaf=$separaf[0];
    $mseparaf=$separaf[1];
    $aseparaf=$separaf[2];
    if(($aseparaf<$asepara)||($aseparaf==$asepara && $mseparaf<$msepara)||($aseparaf==$asepara && $mseparaf==$msepara && $dseparaf<=$dsepara)){
	$sms='La fecha seleccionada no es válida';
    }
    if($asepara<$alimite || $aseparaf<$alimite){//2017
    $sms='La fecha seleccionada no es válida';
    }
    elseif($asepara>$ahoy || $aseparaf>$ahoy){//2019
    $sms='La fecha seleccionada no es válida';
    }
    else{//2018
        if($msepara<$mlimite || $mseparaf<$mlimite){//Junio
        $sms='La fecha seleccionada no es válida';  
        }  
        elseif($msepara>$mhoy || $mseparaf>$mhoy) {//Septiembre
        $sms='La fecha seleccionada no es válida'; 
        } 
        else{//Julio o Agosto
            if(($msepara==$mlimite && $dsepara<$dlimite)||($mseparaf==$mlimite && $dseparaf<$dlimite)){//Jun31
            $sms='La fecha seleccionada no es válida';
            }
            elseif(($msepara==$mhoy && $dsepara>$dhoy)||($mseparaf==$mhoy && $dseparaf>$dhoy)){//Manana
            $sms='La fecha seleccionada no es válida'; 
            }
        }
    }
  	}
    
    $usurolg = DB::table('tipousuario as t')
    ->select(array('t.nombre', DB::raw('COUNT(u.idtipousuario) as cantidad')))
    ->where('t.estado', '=', 'A')
    ->where('t.idtipousuario','>',2)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idtipousuario', '=', 't.idtipousuario')
    ->groupBy('t.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurole= DB::table('tipousuario as t')
    ->select(array('t.nombre', DB::raw('COUNT(u.idtipousuario) as cantidad')))
    ->where('t.estado', '=', 'A')
    ->where('t.idtipousuario','=',$tipousu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idtipousuario', '=', 't.idtipousuario')
    ->groupBy('t.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurgfg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

     $usurgfe = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('f.idfacultad','=',$facu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurefg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('u.idtipousuario','=',$tipousu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurefe = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('f.idfacultad','=',$facu)
    ->where('u.idtipousuario','=',$tipousu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurgfecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$facu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurgfece = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$facu)
    ->where('c.idcarrera','=',$carre)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurefece = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$facu)
    ->where('c.idcarrera','=',$carre)
    ->where('u.idtipousuario','=',$tipousu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $usurefecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$facu)
    ->where('u.idtipousuario','=',$tipousu)
    ->where('u.created_at','>=',$nfechaini)
    ->where('u.created_at','<=',$nfechafin)
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('cantidad', 'desc')
    ->get();

    $urglabel=[];
    $urgdata=[];
    $urelabel=[];
    $uredata=[];
    $urgfglabel=[];
    $urgfgdata=[];
    $urgfelabel=[];
    $urgfedata=[];
    $urefglabel=[];
    $urefgdata=[];
    $urefelabel=[];
    $urefedata=[];
    $urgfecglabel=[];
    $urgfecgdata=[];
    $urgfecelabel=[];
    $urgfecedata=[];
    $urefecelabel=[];
    $urefecedata=[];
    $urefecglabel=[];
    $urefecgdata=[];
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
    foreach($usurolg as $urg){
    $urglabel[]=$urg->nombre;
    $urgdata[]=$urg->cantidad;
    }
    $charturg = app()->chartjs->name('DiagramaUsuarioRolDetalle')->type('bar')
    ->labels($urglabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>20, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurole as $ure){
    $urelabel[]=$ure->nombre;
    $uredata[]=$ure->cantidad;
    }
    $charture = app()->chartjs->name('DiagramaUsuarioRolDetalle')->type('bar')
    ->labels($urelabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $uredata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>20, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurgfg as $urgfg){
    $separa=explode(" ",$urgfg->nombre);
    if($urgfg->nombre=='CIENCIAS ECONOMICAS Y ADMINISTRATIVAS'){
    $urgfglabel[]=$separa[1];
    }
    else{
    $urgfglabel[]=$separa[0];	
    }	
    $urgfgdata[]=$urgfg->cantidad;
    }
    $charturgfg = app()->chartjs->name('DiagramaUsuarioFacultadGeneral')->type('bar')
    ->labels($urgfglabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urgfgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>10, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurgfe as $urgfe){
    $urgfelabel[]=$urgfe->nombre;
    $urgfedata[]=$urgfe->cantidad;
    }
    $charturgfe = app()->chartjs->name('DiagramaUsuarioFacultadDetalle')->type('bar')
    ->labels($urgfelabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urgfedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>20, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurefg as $urefg){
    $separa=explode(" ",$urefg->nombre);
    if($urefg->nombre=='CIENCIAS ECONOMICAS Y ADMINISTRATIVAS'){
    $urefglabel[]=$separa[1];
    }
    else{
    $urefglabel[]=$separa[0];	
    }
    $urefgdata[]=$urefg->cantidad;
    }
    $charturefg = app()->chartjs->name('DiagramaRolESPFacultadGEN')->type('bar')
    ->labels($urefglabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urefgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>10, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurefe as $urefe){
    $urefelabel[]=$urefe->nombre;
    $urefedata[]=$urefe->cantidad;
    }
    $charturefe = app()->chartjs->name('DiagramaRolESPFacultadESP')->type('bar')
    ->labels($urefelabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urefedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>20, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurgfecg as $urgfecg){
    $urgfecglabel[]=$urgfecg->nombre;
    $urgfecgdata[]=$urgfecg->cantidad;
    }
    $charturgfecg = app()->chartjs->name('DiagramaRolGENFacuESPCarreGEN')->type('bar')
    ->labels($urgfecglabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urgfecgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>10, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurgfece as $urgfece){
    $urgfecelabel[]=$urgfece->nombre;
    $urgfecedata[]=$urgfece->cantidad;
    }
    $charturgfece = app()->chartjs->name('DiagramaRolGENFacuESPCarreESP')->type('bar')
    ->labels($urgfecelabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urgfecedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>20, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurefece as $urefece){
    $urefecelabel[]=$urefece->nombre;
    $urefecedata[]=$urefece->cantidad;
    }
    $charturefece = app()->chartjs->name('DiagramaRolESPFacuESPCarreESP')->type('bar')
    ->labels($urefecelabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urefecedata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>20, 'fontColor'=>'#000']]]
    	]
    ]);

    foreach($usurefecg as $urefecg){
    $urefecglabel[]=$urefecg->nombre;
    $urefecgdata[]=$urefecg->cantidad;
    }
    $charturefecg = app()->chartjs->name('DiagramaRolESPFacuESPCarreGEN')->type('bar')
    ->labels($urefecglabel)
    ->datasets([[
    	'label' => 'Usuarios',
    	'backgroundColor' => $bgcolor,
    	'borderWidth' => 1,
    	'data' => $urefecgdata
    ]])
    ->options([
    	'legend' => ['display' => false],
    	'scales' => [
    		'yAxes' => [['ticks' => ['beginAtZero'=>true, 'fontSize'=>20, 'fontColor'=>'#000','stepSize'=>1]]],
    		'xAxes' => [['ticks' => ['fontSize'=>10, 'fontColor'=>'#000']]]
    	]
    ]);

    if($sms=='' && $tipousu==999 && $facu==0 && $carre==0){
    $rcont = $usurolg->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurolg';
    	}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu==0 && $carre==0){
    $rcont = $usurole->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurole';
    	}	
    }
    if($sms=='' && $tipousu==999 && $facu==999 && $carre==0){
    $rcont = $usurgfg->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurgfg';
    	}	
    }
    if($sms=='' && $tipousu==999 && $facu!=0 && $facu!=999 && $carre==0){
    $rcont = $usurgfe->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurgfe';
    	}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu==999 && $carre==0){
    $rcont = $usurefg->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurefg';
    	}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu!=0 && $facu!=999 && $carre==0){
    $rcont = $usurefe->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurefe';
    	}	
    }
    if($sms=='' && $tipousu==999 && $facu!=0 && $facu!=999 && $carre==999){
    $diagrama='usurgfecg';
    }
    if($sms=='' && $tipousu==999 && $facu!=0 && $facu!=999 && $carre!=0 && $carre!=999){
    	$rcont = $usurgfece->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurgfece';
    	}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu!=0 && $facu!=999 && $carre!=0 && $carre!=999){
    $rcont = $usurefece->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurefece';
    	}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu!=0 && $facu!=999 && $carre==999){
    $rcont = $usurefecg->count();
    if($rcont==0){
    	$sms='No hay resultados';
    	}
    else{
		$diagrama='usurefecg';
    	}	
    }
    
    if(Auth::user()->idtipousuario<2){
    return view("reporte.chart.index",["sms"=>$sms,"fechaini"=>$fechaini,"fechafin"=>$fechafin,"tipousu"=>$tipousu,"roles"=>$roles,"facultades"=>$facultades,"facu"=>$facu,"carre"=>$carre,"charturg"=>$charturg,"diagrama"=>$diagrama,"charture"=>$charture,"charturgfg"=>$charturgfg,"charturgfe"=>$charturgfe,"charturefg"=>$charturefg,"charturefe"=>$charturefe,"charturgfecg"=>$charturgfecg,"charturgfece"=>$charturgfece,"charturefece"=>$charturefece,"charturefecg"=>$charturefecg,"hoy"=>$hoy,"limitefecha"=>$limitefecha]);
    }
    else{
    return Redirect::to('/logout');
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
}
