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
use PdfReport;
use ExcelReport;

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
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurolg';}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu==0 && $carre==0){
    $rcont = $usurole->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurole';}	
    }
    if($sms=='' && $tipousu==999 && $facu==999 && $carre==0){
    $rcont = $usurgfg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurgfg';}	
    }
    if($sms=='' && $tipousu==999 && $facu!=0 && $facu!=999 && $carre==0){
    $rcont = $usurgfe->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurgfe';}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu==999 && $carre==0){
    $rcont = $usurefg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurefg';}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu!=0 && $facu!=999 && $carre==0){
    $rcont = $usurefe->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurefe';}	
    }
    if($sms=='' && $tipousu==999 && $facu!=0 && $facu!=999 && $carre==999){
    $rcont = $usurgfecg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurgfecg';}
    }
    if($sms=='' && $tipousu==999 && $facu!=0 && $facu!=999 && $carre!=0 && $carre!=999){
    	$rcont = $usurgfece->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurgfece';}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu!=0 && $facu!=999 && $carre!=0 && $carre!=999){
    $rcont = $usurefece->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurefece';}	
    }
    if($sms=='' && $tipousu!=0 && $tipousu!=999 && $facu!=0 && $facu!=999 && $carre==999){
    $rcont = $usurefecg->count();
        if($rcont==0){$sms='No hay resultados';}
        else{$diagrama='usurefecg';}	
    }
    
    if(($request->boton=='') || ($request->boton=='consultar') || ($request->boton=='pdf' && $sms!='') || ($request->boton=='excel' && $sms!='')){
        if(Auth::user()->idtipousuario<2){
        return view("reporte.chart.index",["sms"=>$sms,"fechaini"=>$fechaini,"fechafin"=>$fechafin,"tipousu"=>$tipousu,"roles"=>$roles,"facultades"=>$facultades,"facu"=>$facu,"carre"=>$carre,"charturg"=>$charturg,"diagrama"=>$diagrama,"charture"=>$charture,"charturgfg"=>$charturgfg,"charturgfe"=>$charturgfe,"charturefg"=>$charturefg,"charturefe"=>$charturefe,"charturgfecg"=>$charturgfecg,"charturgfece"=>$charturgfece,"charturefece"=>$charturefece,"charturefecg"=>$charturefecg,"hoy"=>$hoy,"limitefecha"=>$limitefecha]);
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
    $fechainicio=$ini[2]."/".$ini[1]."/".$ini[0]." "."23:59:59";
    $fechafin=$fin[2]."/".$fin[1]."/".$fin[0]." "."23:59:59";
    $desde=$ini[0]."/".$ini[1]."/".$ini[2];
    $hasta=$fin[0]."/".$fin[1]."/".$fin[2];
    $r=trim($request->get('tipousuchart'));
    $f=trim($request->get('facuchart'));
    $c=trim($request->get('carrechart'));

    if($r==999 && $f==0 && $c==0){$palabra='usurolg';  }
    if($r!=0 && $r!=999 && $f==0 && $c==0){$palabra='usurole';  }
    if($r==999 && $f==999 && $c==0){$palabra='usurgfg';  }
    if($r==999 && $f!=0 && $f!=999 && $c==0){$palabra='usurgfe';  }
    if($r!=0 && $r!=999 && $f==999 && $c==0){$palabra='usurefg';  }
    if($r!=0 && $r!=999 && $f!=0 && $f!=999 && $c==0){$palabra='usurefe';   }
    if($r==999 && $f!=0 && $f!=999 && $c==999){$palabra='usurgfecg';}
    if($r==999 && $f!=0 && $f!=999 && $c!=0 && $c!=999){$palabra='usurgfece'; }
    if($r!=0 && $r!=999 && $f!=0 && $f!=999 && $c!=0 && $c!=999){$palabra='usurefece';   }
    if($r!=0 && $r!=999 && $f!=0 && $f!=999 && $c==999){$palabra='usurefecg'; }

    if($r!=999){
    $variable=Rol::where('idtipousuario',$r)->first();
    $rmeta=$variable->nombre;
    }
    if($f!=0 && $f!=999){
    $variable=Facultad::where('idfacultad',$f)->first();
    $fmeta=$variable->nombre;
    }
    if($c!=0 && $c!=999){
    $variable=Carrera::where('idcarrera',$c)->first();
    $cmeta=$variable->nombre;
    }
    if($r==999){$rmeta='Usuarios';}
    if($f==999){$fmeta='Todas';}
    if($c==999){$cmeta='Todas';}
    if($f==0){$fmeta='N/A';}
    if($c==0){$cmeta='N/A'; }
    
    $title = 'Reporte Número de Usuarios'; 
    $meta = ['Desde'=>$desde,'Hasta'=>$hasta,'Facultad'=>$fmeta,'Carrera'=>$cmeta,'Rol'=>$rmeta];

    $usurolg = DB::table('tipousuario as t')
    ->select(array('t.nombre', DB::raw('COUNT(u.idtipousuario) as cantidad')))
    ->where('t.estado', '=', 'A')
    ->where('t.idtipousuario','>',2)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idtipousuario', '=', 't.idtipousuario')
    ->groupBy('t.nombre')
    ->orderBy('t.nombre', 'asc');
    $n1=DB::table('tipousuario as t')
    ->select(array('t.nombre', DB::raw('COUNT(u.idtipousuario) as cantidad')))
    ->where('t.estado', '=', 'A')
    ->where('t.idtipousuario','>',2)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idtipousuario', '=', 't.idtipousuario')
    ->groupBy('t.nombre')
    ->orderBy('t.nombre', 'asc')
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
    if($n->cantidad<$max1){
    $min1=$n->cantidad;
    $nomin1=$n->nombre;
    }
    }
    $cusurolg = [
    'Rol'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $usurole= DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc');
    $n2= DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc')
    ->get();
    $tot2=$n2->count();
    $cusurole = [
    'Usuario'=>'name',
    'Fecha registro'=>function($usurole){ 
    return date("d/m/Y", strtotime($usurole->created_at));
    }     
    ];

    $usurgfg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('f.nombre', 'asc');    
    $n3 = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('f.nombre', 'asc')
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
    if($n->cantidad<$max3){
    $min3=$n->cantidad;
    $nomin3=$n->nombre;
    }
    }
    $cusurgfg = [
    'Facultad'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $usurgfe = DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc');
     $n4 = DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc')
    ->get();
    $tot4=$n4->count();
    $cusurgfe = [
    'Usuario'=>'name',
    'Fecha registro'=>function($usurgfe){ 
    return date("d/m/Y", strtotime($usurgfe->created_at));
    }      
    ];

    $usurefg = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('f.nombre', 'asc');
    $n5 = DB::table('facultad as f')
    ->select(array('f.nombre', DB::raw('COUNT(u.idfacultad) as cantidad')))
    ->where('f.estado', '=', 'A')
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idfacultad', '=', 'f.idfacultad')
    ->groupBy('f.nombre')
    ->orderBy('f.nombre', 'asc')
    ->get();
    $tot5=$n5->sum('cantidad');
    $max5=0;
    $min5=0;
    $nomax5='';
    $nomin5='';
    foreach ($n5 as $n) {
    if($n->cantidad>=$max5){
    $max5=$n->cantidad;
    $nomax5=$n->nombre;
    }
    }
    foreach ($n5 as $n) {
    if($n->cantidad<$max5){
    $min5=$n->cantidad;
    $nomin5=$n->nombre;
    }
    }
    $cusurefg = [
    'Facultad'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $usurefe = DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc');
    $n6 = DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc')
    ->get();
    $tot6=$n6->count();
    $cusurefe = [
    'Usuario'=>'name',
    'Fecha registro'=>function($usurefe){ 
    return date("d/m/Y", strtotime($usurefe->created_at));
    }    
    ];

    $usurgfecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$f)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('c.nombre', 'asc');
    $n7 = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$f)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('c.nombre', 'asc')
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
    if($n->cantidad<$max7){
    $min7=$n->cantidad;
    $nomin7=$n->nombre;
    }
    }
    $cusurgfecg = [
    'Carrera'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    $usurgfece = DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc');
    $n8 = DB::table('users as u')
    ->select(array('u.name','u.created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc')
    ->get();
    $tot8=$n8->count();
    $cusurgfece = [
    'Usuario'=>'name',
    'Fecha registro'=>function($usurgfece){ 
    return date("d/m/Y", strtotime($usurgfece->created_at));
    }    
    ];

    $usurefece = DB::table('users as u')
    ->select(array('u.name', 'created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc');
    $n9 = DB::table('users as u')
    ->select(array('u.name', 'created_at'))
    ->where('u.estado', '=', 'A')
    ->where('u.idfacultad','=',$f)
    ->where('u.idcarrera','=',$c)
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->orderBy('u.name', 'asc')
    ->get();
    $tot9=$n9->count();
    $cusurefece = [
    'Usuario'=>'name',
    'Fecha registro'=>function($usurefece){ 
    return date("d/m/Y", strtotime($usurefece->created_at));
    }    
    ];

    $usurefecg = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$f)
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('c.nombre', 'asc');
    $n10 = DB::table('carrera as c')
    ->select(array('c.nombre', DB::raw('COUNT(u.idcarrera) as cantidad')))
    ->where('c.estado', '=', 'A')
    ->where('c.idfacultad','=',$f)
    ->where('u.idtipousuario','=',$r)
    ->whereBetween('u.created_at', [$fechainicio, $fechafin])
    ->leftjoin('users as u', 'u.idcarrera', '=', 'c.idcarrera')
    ->groupBy('c.nombre')
    ->orderBy('c.nombre', 'asc')
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
    if($n->cantidad<$max10){
    $min10=$n->cantidad;
    $nomin10=$n->nombre;
    }
    }
    $cusurefecg = [
    'Carrera'=>'nombre',
    'Cantidad'=>'cantidad'
    ];

    if($palabra=='usurolg'){$queryBuilder=$usurolg; $columns=$cusurolg; $tot=$tot1; $nomax=$nomax1; $nomin=$nomin1; $nombre='RolGeneral';}
    if($palabra=='usurole'){$queryBuilder=$usurole; $columns=$cusurole; $tot=$tot2; $nomax=''; $nomin=''; $nombre='RolEspecifico';}
    if($palabra=='usurgfg'){$queryBuilder=$usurgfg; $columns=$cusurgfg; $tot=$tot3; $nomax=$nomax3; $nomin=$nomin3; $nombre='RolGeneralFacultadGeneral';}
    if($palabra=='usurgfe'){$queryBuilder=$usurgfe; $columns=$cusurgfe; $tot=$tot4; $nomax=''; $nomin=''; $nombre='RolGeneralFacultadEspecifico';}
    if($palabra=='usurefg'){$queryBuilder=$usurefg; $columns=$cusurefg; $tot=$tot5; $nomax=$nomax5; $nomin=$nomin5; $nombre='RolEspecificoFacultadGeneral';}
    if($palabra=='usurefe'){$queryBuilder=$usurefe; $columns=$cusurefe; $tot=$tot6; $nomax=''; $nomin=''; $nombre='RolEspecificoFacultadEspecifico';}
    if($palabra=='usurgfecg'){$queryBuilder=$usurgfecg; $columns=$cusurgfecg; $tot=$tot7; $nomax=$nomax7; $nomin=$nomin7; $nombre='RolGen-FacultadEsp-CarreraGen';}
    if($palabra=='usurgfece'){$queryBuilder=$usurgfece; $columns=$cusurgfece; $tot=$tot8; $nomax=''; $nomin=''; $nombre='RolGen-FacultadGen-CarreraEsp';}
    if($palabra=='usurefece'){$queryBuilder=$usurefece; $columns=$cusurefece; $tot=$tot9; $nomax=''; $nomin=''; $nombre='RolEsp-FacultadEsp-CarreraEsp';}
    if($palabra=='usurefecg'){$queryBuilder=$usurefecg; $columns=$cusurefecg; $tot=$tot10; $nomax=$nomax10; $nomin=$nomin10; $nombre='RolEsp-FacultadEsp-CarreraGen';}

    if($request->boton=='pdf'){
    return PdfReport::of($title, $meta, $queryBuilder, $columns, $palabra, $tot, $nomax, $nomin,$hoy,$hora)               
    ->setCss(['.head-content' => 'border-width: 1px',])
    ->limit(50)
    ->stream(); 
    }
    else{
    return ExcelReport::of($title, $meta, $queryBuilder, $columns, $palabra, $tot, $nomax, $nomin,$hoy,$hora)               
    ->setCss(['.head-content' => 'border-width: 1px',])
    ->limit(50)
    ->simpleDownload($nombre); 
    }
    }
}
