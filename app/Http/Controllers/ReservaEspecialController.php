<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Http\Requests\AdminreservaFormRequest;
use sistemaReserva\Reserva;
use sistemaReserva\Horario;
use sistemaReserva\Area;
use DB;
use Auth;
use Carbon\Carbon;
use sistemaReserva\User;
use Mail;
use DateTime;

class ReservaEspecialController extends Controller
{
    public function __construct(){
    $this->middleware('auth');
   }

    public function index(Request $request){
    if($request){
    	$usuarios=DB::table('users')->where('estado','=','A')->where('idtipousuario','>',1)->orderBy('idtipousuario','asc')->get();
    	$areas=DB::table('area')->where('estado','=','A')->where('disponibilidad','=','Disponible')->get();
    	$horarios=DB::table('horario')->where('estado','=','A')->orderBy('hora','asc')->get();
        $primerhora= Horario::where('estado','=','A')->orderBy('hora','asc')->first();
        $horariosf=DB::table('horario')->where('hora','!=',$primerhora->hora)->where('estado','=','A')->orderBy('hora','asc')->get();
    	$idquery=trim($request->get('id'));
    	$areaquery=trim($request->get('area'));
    	$horaini=trim($request->get('horaini'));
    	$horafin=trim($request->get('horafin'));
    	$cantidad=trim($request->get('cantidad'));
    	$hoy = Carbon::now()->format('d/m/Y');
    	$sms='';
        if(Auth::user()->idtipousuario<2){
        return view("operacion.reservasespeciales.index",["usuarios"=>$usuarios,"idquery"=>$idquery,"sms"=>$sms,"areas"=>$areas,"areaquery"=>$areaquery,"horarios"=>$horarios,"horariosf"=>$horariosf,"horaini"=>$horaini,"horafin"=>$horafin,"hoy"=>$hoy,"cantidad"=>$cantidad]);
        }
        else{
        return Redirect::to('/logout');
        } 
    }
    }

    public function show(Request $request){
    $usuarios=DB::table('users')->where('estado','=','A')->where('idtipousuario','>',1)->orderBy('idtipousuario','asc')->get();
    $areas=DB::table('area')->where('estado','=','A')->where('disponibilidad','=','Disponible')->get();
    $horarios=DB::table('horario')->where('estado','=','A')->orderBy('hora','asc')->get();
    $primerhora= Horario::where('estado','=','A')->orderBy('hora','asc')->first();
    $horariosf=DB::table('horario')->where('hora','!=',$primerhora->hora)->where('estado','=','A')->orderBy('hora','asc')->get();
    $idquery=trim($request->get('id'));
    $areaquery=trim($request->get('area'));
    $horaini=trim($request->get('horaini'));
    $horafin=trim($request->get('horafin'));
    $fechaini=trim($request->get('fechaini'));
    $fechafin=trim($request->get('fechafin'));
    $cantidad=trim($request->get('cantidad'));
    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
    $sms='';
    $validaarea=Area::findOrFail($areaquery);
    if($cantidad==''){
    $sms='El campo cantidad es obligatorio';
    }
    if($cantidad<$validaarea->minimo || $cantidad>$validaarea->capacidad){
    $sms='Ingrese una cantidad válida';	
    }
    if($cantidad!=''){
    $vhoy=explode("/",$hoy);
    $vini=explode("/",$fechaini);
    $vfin=explode("/",$fechafin);
    $dhoy=$vhoy[0];
    $mhoy=$vhoy[1];
    $ahoy=$vhoy[2];
    $dini=$vini[0];
    $mini=$vini[1];
    $aini=$vini[2];
    $dfin=$vfin[0];
    $mfin=$vfin[1];
    $afin=$vfin[2];
    	if(($afin<$aini)||($afin==$aini && $mfin<$mini)||($afin==$aini && $mfin==$mini && $dfin<$dini)){
		$sms='La fecha seleccionada no es válida';
    	}
        else{
            if($aini<$ahoy || $afin<$ahoy){//2017
            $sms='La fecha seleccionada no es válida';
            }  
            elseif($aini>$ahoy || $afin>$ahoy){//2019
            $sms='La fecha seleccionada no es válida';
            }
            else{//2018
            	if($mini<$mhoy || $mfin<$mhoy){//Julio
            	$sms='La fecha seleccionada no es válida';
            	}
            	elseif($mini!=$mfin){//Septiembre
            	$sms='La fecha seleccionada no es válida';
            	}
            	else{
            	if(($mini==$mhoy && $dini<$dhoy)||($mfin==$mhoy && $dfin<$dhoy)){//ayer
                $sms='La fecha seleccionada no es válida';
                }	
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

    if($sms=='' && $cantidad!=''){
    	if($fechaini==$fechafin){
    	$uid=User::where('id',$request->get('id'))->first();
    	$hid=Horario::where('hora',$request->get('horaini'))->first();
    	$sfecha=explode("/",$request->get('fechaini'));
    	$nfechaini=$sfecha[2]."/".$sfecha[1]."/".$sfecha[0]." ".$request->get('horaini');
    	$sfechafin=explode("/",$request->get('fechafin'));
    	$nfechafin=$sfechafin[2]."/".$sfechafin[1]."/".$sfechafin[0]." ".$request->get('horafin');

        $reserva=Reserva::where('estado','!=','I')
        ->where('idarea',$request->get('area'))
        ->where('fechainicio','>=',$nfechaini)
        ->where('fechainicio','<=',$nfechafin)
        ->get();
        	foreach($reserva as $r){
            $r->estado='I';
            $r->update();
            $usu=User::findOrFail($request->get('id'));
            $area=Area::findOrFail($request->get('area'));
            	Mail::send('email.mensajearea',['usu' => $usu,'r' => $r,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Área reservada no disponible')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                    }
            	); 
            }  

    	$reserva=new Reserva;
    	$reserva->fecha=$request->get('fechaini');
    	$reserva->horainicio=$request->get('horaini');
    	$reserva->horafinal=$request->get('horafin');
    	$reserva->horallegada=$hola=NULL;
    	$vreserva=explode(":",$reserva->horainicio);//16:00:00
    	$ms=":".$vreserva[1].":".$vreserva[2];//:00:00
    	$quince = strtotime("+15 minutes", strtotime($ms));//:15:00
    	$uno=date(':i:s', $quince);
    	$espera=$vreserva[0].$uno;//16:15:00
    	$reserva->tiempoespera=$espera;
    	$reserva->cantidad=$request->get('cantidad');
    	$reserva->estado='A';
    	$reserva->id=$uid->id;
    	$reserva->idarea=$request->get('area');
    	$reserva->codigoqr=str_random(10);
    	$reserva->idhora=$hid->idhora;
    	$reserva->fechacrea=$hoy;
    	$reserva->horacrea=$hora;
    	$sfecha=explode("/",$request->get('fechaini'));
    	$nfecha=$sfecha[2]."/".$sfecha[1]."/".$sfecha[0]." ".$request->get('horaini');
    	$reserva->fechainicio=$nfecha;
    	$reserva->save();

    	if($hoy==$reserva->fecha && $reserva->horacrea>=$espera){
      	$fin = $reserva->horacrea;
      	$separa=explode(":",$fin);
      		if($separa[0]>11 && $separa[1]>44){
      		$nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
      		$separan=explode(":",$nuevote);
      		$nhora=$separa[0]+1;
      		$nnuevote=$nhora.":".$separan[1].":00";
      			if($nnuevote>=$reserva->horafinal){
      			$reserva->tiempoespera=$reserva->horafinal;
      			$reserva->update();
      			}
     			else{
      			$reserva->tiempoespera=$nnuevote;
      			$reserva->update();
      			}
      		}			
      		else{
      			if($separa[0]>11){
      			$nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
      			$separan=explode(":",$nuevote);
      			$nhora=$separa[0];
      			$nnuevote=$nhora.":".$separan[1].":00";
      			$reserva->tiempoespera=$nnuevote;
     	 		$reserva->update();
      			}	
      			else{
      			$nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
      			$separan=explode(":",$nuevote);
      			$nnuevote=$separan[0].":".$separan[1].":00";
      			$reserva->tiempoespera=$nnuevote;
      			$reserva->update();
      			}
      		}	
    	}

    	$area=Area::findOrFail($request->get('area'));
    	$reservas=DB::table('reserva')->where('estado','=','A')->get();
    	$qrcod = $reserva->codigoqr;
    	$usu=User::where('id',$request->get('id'))->first();
    	Mail::send('email.mensajeqr',['usu' => $usu,'reservas' => $reservas,'qrcod' => $qrcod,'area'=>$area],
            function ($m) use ($usu) {
            $m->to($usu->email, $usu->name)
            ->subject('Código QR de su reserva')
            ->from('roseroesteban@gmail.com', 'Administrador');
            }
        ); 
    	return Redirect::to('operacion/adminreservas');
    	}
    	else{
    	$vini=explode("/",$fechaini);
    	$vfin=explode("/",$fechafin);
    	$dif=$vfin[0]-$vini[0];
    	$nuevodia=[];
    	$uid=User::where('id',$request->get('id'))->first();
    	$hid=Horario::where('hora',$request->get('horaini'))->first();
    	$vfin=explode("/",$request->get('fechafin'));
    	$nfechafin=$vfin[2]."/".$vfin[1]."/".$vfin[0]." ".$request->get('horafin');
    		for($i = 0; $i <= $dif; $i++){
        	$ndia=$vini[0]+$i;
        	$nuevodia[]=$ndia;
        		if($nuevodia[$i]<10){
				$nuevafecha="0".$nuevodia[$i]."/".$vini[1]."/".$vini[2];
        		}
        		else{
        		$nuevafecha=$nuevodia[$i]."/".$vini[1]."/".$vini[2];	
        		}
        	$sfecha=explode("/",$nuevafecha);
    		$nfechaini=$sfecha[2]."/".$sfecha[1]."/".$sfecha[0]." ".$request->get('horaini');
       		$reserva=Reserva::where('estado','!=','I')
        	->where('idarea',$request->get('area'))
        	->where('fechainicio','>=',$nfechaini)
        	->where('fechainicio','<=',$nfechafin)
        	->get();
        		foreach($reserva as $r){
            	$r->estado='I';
            	$r->update();
            	$usu=User::findOrFail($request->get('id'));
            	$area=Area::findOrFail($request->get('area'));
            		Mail::send('email.mensajearea',['usu' => $usu,'r' => $r,'area'=>$area],
                    	function ($m) use ($usu) {
                        	$m->to($usu->email, $usu->name)
                          	->subject('Área reservada no disponible')
                          	->from('roseroesteban@gmail.com', 'Administrador');
                    	}
            		); 
            	} 

            $sfecha=explode("/",$nuevafecha);
    		$date=$sfecha[2]."-".$sfecha[1]."-".$sfecha[0];
			$timestamp = strtotime($date);
			$weekday= date("l", $timestamp );
			$normalized_weekday = strtolower($weekday);
			if($normalized_weekday!='saturday' && $normalized_weekday!='sunday'){
        	$reserva=new Reserva;
        	$reserva->fecha=$nuevafecha;
        	$reserva->horainicio=$request->get('horaini');
    		$reserva->horafinal=$request->get('horafin');
    		$reserva->horallegada=$hola=NULL;
    		$vreserva=explode(":",$reserva->horainicio);//16:00:00
    		$ms=":".$vreserva[1].":".$vreserva[2];//:00:00
    		$quince = strtotime("+15 minutes", strtotime($ms));//:15:00
    		$uno=date(':i:s', $quince);
    		$espera=$vreserva[0].$uno;//16:15:00
    		$reserva->tiempoespera=$espera;
    		$reserva->cantidad=$request->get('cantidad');
    		$reserva->estado='A';
    		$reserva->id=$uid->id;
    		$reserva->idarea=$request->get('area');
    		$reserva->codigoqr=str_random(10);
    		$reserva->idhora=$hid->idhora;
    		$reserva->fechacrea=$hoy;
    		$reserva->horacrea=$hora;
    		$sfecha=explode("/",$nuevafecha);
    		$nfecha=$sfecha[2]."/".$sfecha[1]."/".$sfecha[0]." ".$request->get('horaini');
    		$reserva->fechainicio=$nfecha;
    		$reserva->save();

    		if($hoy==$reserva->fecha && $reserva->horacrea>=$espera){
      		$fin = $reserva->horacrea;
      		$separa=explode(":",$fin);
      		if($separa[0]>11 && $separa[1]>44){
      		$nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
      		$separan=explode(":",$nuevote);
      		$nhora=$separa[0]+1;
      		$nnuevote=$nhora.":".$separan[1].":00";
      			if($nnuevote>=$reserva->horafinal){
      			$reserva->tiempoespera=$reserva->horafinal;
      			$reserva->update();
      			}
     			else{
      			$reserva->tiempoespera=$nnuevote;
      			$reserva->update();
      			}
      		}			
      		else{
      			if($separa[0]>11){
      			$nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
      			$separan=explode(":",$nuevote);
      			$nhora=$separa[0];
      			$nnuevote=$nhora.":".$separan[1].":00";
      			$reserva->tiempoespera=$nnuevote;
     	 		$reserva->update();
      			}	
      			else{
      			$nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
      			$separan=explode(":",$nuevote);
      			$nnuevote=$separan[0].":".$separan[1].":00";
      			$reserva->tiempoespera=$nnuevote;
      			$reserva->update();
      			}
      		}	
    		}

    		$area=Area::findOrFail($request->get('area'));
    		$reservas=DB::table('reserva')->where('estado','=','A')->get();
    		$qrcod = $reserva->codigoqr;
    		$usu=User::where('id',$request->get('id'))->first();
    		Mail::send('email.mensajeqr',['usu' => $usu,'reservas' => $reservas,'qrcod' => $qrcod,'area'=>$area],
            	function ($m) use ($usu) {
            	$m->to($usu->email, $usu->name)
            	->subject('Código QR de su reserva')
            	->from('roseroesteban@gmail.com', 'Administrador');
            	}
        	);
    		}
    		}
    		return Redirect::to('operacion/adminreservas');
    	}
    }
    else{
    return view("operacion.reservasespeciales.index",["usuarios"=>$usuarios,"idquery"=>$idquery,"sms"=>$sms,"areas"=>$areas,"areaquery"=>$areaquery,"horarios"=>$horarios,"horariosf"=>$horariosf,"horaini"=>$horaini,"horafin"=>$horafin,"hoy"=>$hoy,"cantidad"=>$cantidad,"fechaini"=>$fechaini,"fechafin"=>$fechafin]);
    }
	}
}
