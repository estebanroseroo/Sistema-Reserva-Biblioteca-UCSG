<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Area;
use sistemaReserva\Reserva;
use sistemaReserva\User;
use sistemaReserva\Horario;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\AreaFormRequest;
use DB;
use Auth;
use Mail;
use Carbon\Carbon;

class AreaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
            $mdisponibilidad=Area::where('estado','A')->where('disponibilidad','=','No Disponible')->get();
            $hoy = Carbon::now()->format('Y-m-d');
            $hora = Carbon::now()->format('H:i:s');
            foreach ($mdisponibilidad as $m) {
            $sff=explode(" ",$m->fechafin);
                if($hoy==$sff[0] && $hora>=$sff[1]){
                $m->disponibilidad='Disponible';
                $m->fechainicio=$hola=NULL;
                $m->fechafin=$hola=NULL;
                $m->update();
                }
            }

    		$query=trim($request->get('searchText'));
    		$areas=DB::table('area')
            ->where('nombre','LIKE','%'.$query.'%')
			->where('estado','=','A')
            ->orwhere('capacidad','LIKE','%'.$query.'%')
            ->where('estado','=','A')
			->orderBy('capacidad','desc')
			->paginate(9);

        if(Auth::user()->idtipousuario<2){
        return view('mantenimiento.areas.index',["areas"=>$areas, "searchText"=>$query]);
        }
        else{
        return Redirect::to('/logout');
        }	
    	}
    }

    public function create(){
        if(Auth::user()->idtipousuario<2){
        return view("mantenimiento.areas.create");
        }
        else{
        return Redirect::to('/logout');
        }
    }

    public function store(AreaFormRequest $request){
    	$area=new Area;
    	$area->nombre=$request->get('nombre');
    	$area->disponibilidad='Disponible';
        $area->capacidad=$request->get('capacidad');
        $area->minimo=$request->get('minimo');
    	$area->estado='A';
    	$area->save();
    	return Redirect::to('mantenimiento/areas');
    }

    public function edit($id){
        if(Auth::user()->idtipousuario<2){
        $area=Area::findOrFail($id);
        $fechai='';
        $fechaf='';
        $hi='';
        $hf='';
        if($area->disponibilidad=='No Disponible'){
        $fi=$area->fechainicio;
        $ff=$area->fechafin;
        $sfi=explode(" ",$fi);
        $sff=explode(" ",$ff);
        $fein=explode("-",$sfi[0]);
        $fefi=explode("-",$sff[0]);
        $fechai=$fein[2]."/".$fein[1]."/".$fein[0];
        $fechaf=$fefi[2]."/".$fefi[1]."/".$fefi[0];
        $hi=$sfi[1];
        $hf=$sff[1];
        }
        $hoy = Carbon::now()->format('d/m/Y');
        $horarios=DB::table('horario')->where('estado','=','A')->orderBy('hora','asc')->get();
        $primerhora= Horario::where('estado','=','A')->orderBy('hora','asc')->first();
        $horariosf=DB::table('horario')->where('hora','!=',$primerhora->hora)->where('estado','=','A')->orderBy('hora','asc')->get();
        $sms='';
        return view("mantenimiento.areas.edit",["area"=>$area,"sms"=>$sms,"horarios"=>$horarios,"horariosf"=>$horariosf,"hoy"=>$hoy,"fechai"=>$fechai,"fechaf"=>$fechaf,"hi"=>$hi,"hf"=>$hf]);
        }
        else{
        return Redirect::to('/logout');
        }
    }

    public function update(AreaFormRequest $request, $id){
        $area=Area::findOrFail($id);
        $fechai='';
        $fechaf='';
        $hi='';
        $hf='';
        if($area->disponibilidad=='No Disponible'){
        $fi=$area->fechainicio;
        $ff=$area->fechafin;
        $sfi=explode(" ",$fi);
        $sff=explode(" ",$ff);
        $fein=explode("-",$sfi[0]);
        $fefi=explode("-",$sff[0]);
        $fechai=$fein[2]."/".$fein[1]."/".$fein[0];
        $fechaf=$fefi[2]."/".$fefi[1]."/".$fefi[0];
        $hi=$sfi[1];
        $hf=$sff[1];
        }
        $hoy = Carbon::now()->format('d/m/Y');
        $hora = Carbon::now()->format('H:i:s');
        $sms='';
        $horarios=DB::table('horario')->where('estado','=','A')->orderBy('hora','asc')->get();
        $primerhora= Horario::where('estado','=','A')->orderBy('hora','asc')->first();
        $horariosf=DB::table('horario')->where('hora','!=',$primerhora->hora)->where('estado','=','A')->orderBy('hora','asc')->get();
        $fechaini=trim($request->get('fechaini'));
        $fechafin=trim($request->get('fechafin'));
        $horaini=trim($request->get('horaini'));
        $horafin=trim($request->get('horafin'));

        if($fechaini!='' && $fechafin!=''){
        $vhoy=explode("/",$hoy);
        $separa=explode("/",$fechaini);
        $separaf=explode("/",$fechafin);
        $dhoy=$vhoy[0];
        $mhoy=$vhoy[1];
        $ahoy=$vhoy[2];
        $dsepara=$separa[0];
        $msepara=$separa[1];
        $asepara=$separa[2];
        $dseparaf=$separaf[0];
        $mseparaf=$separaf[1];
        $aseparaf=$separaf[2];
        if(($aseparaf<$asepara)||($aseparaf==$asepara && $mseparaf<$msepara)||($aseparaf==$asepara && $mseparaf==$msepara && $dseparaf<$dsepara)){
            $sms='La fecha seleccionada no es válida';
        }
        else{
            if($asepara<$ahoy || $aseparaf<$ahoy){//2017
            $sms='La fecha seleccionada no es válida';
            }  
            elseif($asepara>$ahoy || $aseparaf>$ahoy){//2019
            $sms='La fecha seleccionada no es válida';
            }
            else{//2018
                if(($msepara==$mhoy && $dsepara<$dhoy)||($mseparaf==$mhoy && $dseparaf<$dhoy)){//ayer
                    $sms='La fecha seleccionada no es válida';
                    }
                }
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
            if(($fechaini==$hoy && $hini<$hhora)||($fechafin==$hoy && $hfin<$hhora)){
            $sms='La hora seleccionada no es válida';
            }
        }
        if($sms!=''){
            return view("mantenimiento.areas.edit",["area"=>Area::findOrFail($id),"sms"=>$sms,"horarios"=>$horarios,"horariosf"=>$horariosf,"hoy"=>$hoy,"fechai"=>$fechai,"fechaf"=>$fechaf,"hi"=>$hi,"hf"=>$hf]);   
        }
        else{
            if($request->get('disponibilidad')=='No Disponible' && $request->get('fechaini')==''){
            $sms='Se cancelarán todas las reservas dentro del rango seleccionado, si desea cancelar seleccione Disponible y guarde';
            return view("mantenimiento.areas.edit",["area"=>Area::findOrFail($id),"sms"=>$sms,"horarios"=>$horarios,"horariosf"=>$horariosf,"hoy"=>$hoy,"fechai"=>$fechai,"fechaf"=>$fechaf,"hi"=>$hi,"hf"=>$hf]);
            }
            elseif($request->get('disponibilidad')=='No Disponible' && $request->get('fechaini')!=''){
            $nfechaini=$separa[2]."/".$separa[1]."/".$separa[0]." ".$horaini;
            $nfechafin=$separaf[2]."/".$separaf[1]."/".$separaf[0]." ".$horafin;
            $area->nombre=$request->get('nombre');
            $area->disponibilidad=$request->get('disponibilidad');
            $area->capacidad=$request->get('capacidad');
            $area->minimo=$request->get('minimo');
            $area->fechainicio=$nfechaini;
            $area->fechafin=$nfechafin;
            $area->update(); 

            $reserva=Reserva::where('estado','!=','I')
            ->where('idarea',$area->idarea)
            ->where('fechainicio','>=',$nfechaini)
            ->where('fechainicio','<=',$nfechafin)
            ->get();
            foreach($reserva as $r){
            $r->estado='I';
            $r->update();
            $usu=User::findOrFail($r->id);
            $area=Area::findOrFail($r->idarea);
            Mail::send('email.mensajearea',['usu' => $usu,'r' => $r,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Área reservada no disponible')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    ); 
            }      
            return Redirect::to('mantenimiento/areas');
            }
            else{
            $area->nombre=$request->get('nombre');
            $area->disponibilidad=$request->get('disponibilidad');
            $area->capacidad=$request->get('capacidad');
            $area->minimo=$request->get('minimo');
            $area->fechainicio=$hola=NULL;
            $area->fechafin=$hola=NULL;
            $area->update();
            return Redirect::to('mantenimiento/areas');    
            }
        }
    }

    public function destroy($id){
    	$area=Area::findOrFail($id);
    	$area->estado='I';
        $area->fechainicio=$hola=NULL;
        $area->fechafin=$hola=NULL;
    	$area->update();
    	return Redirect::to('mantenimiento/areas');
    }
}
