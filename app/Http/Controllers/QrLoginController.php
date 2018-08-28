<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Reserva;
use sistemaReserva\Horario;
use sistemaReserva\User;
use sistemaReserva\Area;
use DB;
use Auth;
use Carbon\Carbon;
use Mail;

class QrLoginController extends Controller
{
	public function __construct(){
   	$this->middleware('auth');
   }

   public function index(Request $request){
   	if($request){
        if(Auth::user()->idtipousuario<3){
          $cod='';
          $sms='';
          return view("operacion.consultas.index",["cod"=>$cod,"sms"=>$sms]);
        }
        else{
          return Redirect::to('/logout');
        }  
   	}
   }

  public function create(Request $request){
  $r = Reserva::where('codigoqr', '=', $request->get('cod'))->get();
  $rcont = $r->count();
    if($rcont<1){
    $query='';
    $sms='El código QR ingresado no existe';
    $cod='';
    $reservas=DB::table('reserva as r')
    ->leftjoin('users as u','u.id','=','r.id')
    ->leftjoin('area as a','a.idarea','=','r.idarea')
    ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.horallegada','r.cantidad','r.codigoqr')
    ->where('r.fecha','LIKE','%'.$query.'%')
    ->where('r.estado','=','C')
    ->orwhere('u.name','LIKE','%'.$query.'%')
    ->where('r.estado','=','C')
    ->orwhere('a.nombre','LIKE','%'.$query.'%')
    ->where('r.estado','=','C')
    ->orderBy('r.fecha','asc')
    ->paginate(9);
    return view("operacion.consultas.index",["reservas"=>$reservas,"searchText"=>$query,"cod"=>$cod,"sms"=>$sms]);
    }
    else{
    $sms='';
    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
    $cod=$request->get('cod');
      if($rcont==1){
      $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('u.name as nombreusuario','r.fecha','r.horainicio','r.horafinal','a.nombre as nombrearea','r.cantidad','r.tiempoespera','r.estado','r.idarea','r.id','r.codigoqr')
        ->where('codigoqr',$cod)->first();
      $codnombre=$reservas->nombreusuario;
      $codfecha=$reservas->fecha;
      $codhorario=$reservas->horainicio."-".$reservas->horafinal;
      $codarea=$reservas->nombrearea;
      $codcantidad=$reservas->cantidad;
      if(Auth::user()->idtipousuario<3){
      return view("operacion.consultas.create",["sms"=>$sms,"cod"=>$cod,"codnombre"=>$codnombre,"codfecha"=>$codfecha,"codhorario"=>$codhorario,"codarea"=>$codarea,"codcantidad"=>$codcantidad]);
      }
      else{
      return Redirect::to('/logout');
      }  
      }
      else{
      $reservas=DB::table('reserva as r')
      ->leftjoin('users as u','u.id','=','r.id')
      ->leftjoin('area as a','a.idarea','=','r.idarea')
      ->select('u.name as nombreusuario','r.fecha','r.horainicio','r.horafinal','a.nombre as nombrearea','r.cantidad','r.tiempoespera','r.estado','r.idarea','r.id','r.codigoqr')
      ->where('codigoqr',$cod)->where('fecha',$hoy)->first(); 
        if($reservas==NULL){
        $query='';
        $sms='El código QR no se puede validar';
        $cod='';
        $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.horallegada','r.cantidad','r.codigoqr')
        ->where('r.fecha','LIKE','%'.$query.'%')
        ->where('r.estado','=','C')
        ->orwhere('u.name','LIKE','%'.$query.'%')
        ->where('r.estado','=','C')
        ->orwhere('a.nombre','LIKE','%'.$query.'%')
        ->where('r.estado','=','C')
        ->orderBy('r.fecha','asc')
        ->paginate(9);
        return view("operacion.consultas.index",["reservas"=>$reservas,"searchText"=>$query,"cod"=>$cod,"sms"=>$sms]);
        }
        else{
        $codnombre=$reservas->nombreusuario;
        $codfecha=$reservas->fecha;
        $codhorario=$reservas->horainicio."-".$reservas->horafinal;
        $codarea=$reservas->nombrearea;
        $codcantidad=$reservas->cantidad;
        if(Auth::user()->idtipousuario<3){
        return view("operacion.consultas.create",["sms"=>$sms,"cod"=>$cod,"codnombre"=>$codnombre,"codfecha"=>$codfecha,"codhorario"=>$codhorario,"codarea"=>$codarea,"codcantidad"=>$codcantidad]);
        }
        else{
        return Redirect::to('/logout');
        }  
        } 
      }
    }
  }

  function check(Request $request) {  
     if(Auth::user()->idtipousuario<3){       
        $result =0;
        $hoy = Carbon::now()->format('d/m/Y');
        $hora = Carbon::now()->format('H:i:s');
        if ($request->data) {
          $res = Reserva::where('codigoqr',$request->data)->get();
          if($res){
            if ($res->fecha==$hoy && $res->tiempoespera>$hora && $res->estado=='A') {//llega antes de TE
              if($res->horainicio<$hora){//llega despues de HI
                $res->estado='C';
                $res->horallegada=$hora;
                $res->update();
                }
              else{
                $res->horallegada=$hora;
                $res->update();
                }
              $result =$res->codigoqr;
              return $result;
            }
            else{
              $result =$res->codigoqr;
              return $result;
            }
          }
          else{
            return Redirect::to('operacion/consultas');
          }
      }
    }
    else{
          return Redirect::to('/logout');
    }
  }

  public function show(Request $request){
    if($request){
      $sms='';
      $cod=$request->get('cod');
      $fec=$request->get('fecha');
      $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('u.name as nombreusuario','r.fecha','r.horainicio','r.horafinal','a.nombre as nombrearea','r.cantidad','r.id','r.estado','r.idarea','r.idreserva','r.horallegada','r.horacrea','r.tiempoespera')
        ->where('codigoqr',$cod)->where('r.fecha',$fec)->first();
      $codnombre=$reservas->nombreusuario;
      $codfecha=$reservas->fecha;
      $codhorario=$reservas->horainicio."-".$reservas->horafinal;
      $codarea=$reservas->nombrearea;
      $codcantidad=$reservas->cantidad;
      $idres=$reservas->id;
      $idr=$reservas->idreserva;
      $codespera=$reservas->tiempoespera;

      $r = Reserva::where('id', '=', $idres)->where('estado','!=','I')->get();
      $rcont = $r->count();
      $vqinicio=explode(":",$reservas->horainicio);
      $vqfinal=explode(":",$reservas->horafinal);
      $hvqinicio=$vqinicio[0];
      $hvqfinal=$vqfinal[0];
      $hmin=$hvqfinal-$hvqinicio;
      $hoy = Carbon::now()->format('d/m/Y');
      $hora = Carbon::now()->format('H:i:s');
      $vhora=explode(":",$hora);
      $hvhora=$vhora[0];
      if($rcont>=5 && $reservas->estado=='I'){
        $sms='No se puede realizar más de cinco reservas';
      }
      if($hmin<1){
        $sms='Se puede reservar mínimo una hora';
      }
      if($hmin>5){
        $sms='Se puede reservar máximo cinco horas';
      }
      if($codfecha==$hoy && $hvqinicio<$hvhora && $hvqfinal<$hvhora){
        $sms='La hora seleccionada no es válida';
      }

          $vhoy=explode("/",$hoy);
          $vquery=explode("/",$codfecha);
          $dhoy=$vhoy[0];
          $mhoy=$vhoy[1]+0;
          $mhoymas=$vhoy[1]+1;
          $ahoy=$vhoy[2];
          $dquery=$vquery[0];
          $mquery=$vquery[1]+0;
          $aquery=$vquery[2];
          if($ahoy>$aquery){//2017
          $sms='La fecha seleccionada no es válida';
          }
          elseif($ahoy<$aquery){//2019
          $sms='La fecha seleccionada no es válida'; 
          }
          else{//2018
            if($mhoy>$mquery){//Julio
            $sms='La fecha seleccionada no es válida';  
            }  
            elseif ($mhoymas<$mquery) {//Octubre
            $sms='La fecha seleccionada no es válida'; 
            } 
            else{//Agosto o Septiembre
              if($mhoy==$mquery && $dhoy>$dquery){//Agosto 17
              $sms='La fecha seleccionada no es válida';
              }
              elseif($mhoymas==$mquery && $dhoy<$dquery){//Septiembre 19
              $sms='La fecha seleccionada no es válida'; 
              }
            }
          }

          $contador=0;
          $contselect=0;
          $shi=(int)$reservas->horainicio;
          $shf=(int)$reservas->horafinal;
          $difsh=$shf-$shi;
          $reshora = Reserva::where('id', '=', $idres)
          ->where('estado','!=','I')->where('fecha','=',$codfecha)->get();
          foreach ($reshora as $rh) {
          $hi=(int)$rh->horainicio;
          $hf=(int)$rh->horafinal;
          $difh=$hf-$hi;
          $contador=$contador+$difh;
          }
          $sum=$difsh+$contador;
          if($sum>5 && $reservas->estado=='I'){
          $sms='No se puede reservar más de cinco horas al día';
          }

          $resval = DB::table('reserva')
          ->where('id', '=', $idres)
          ->where('estado','!=','I')
          ->where('fecha','=',$codfecha)
          ->where('horainicio','<=',$reservas->horainicio)
          ->where('horafinal','>',$reservas->horainicio)
          ->orWhere('id', '=', $idres)
          ->where('estado','!=','I')
          ->where('fecha','=',$codfecha)
          ->where('horainicio','<',$reservas->horafinal)
          ->where('horafinal','>=',$reservas->horafinal)
          ->get();
          $contval = $resval->count();
          if($contval>0 && $reservas->estado=='I'){
          $sms='Ya dispone de una reserva en el horario seleccionado';
          }

          $resvaltodos = DB::table('reserva')
          ->where('estado','!=','I')
          ->where('fecha','=',$codfecha)
          ->where('idarea','=',$reservas->idarea)
          ->where('horainicio','<=',$reservas->horainicio)
          ->where('horafinal','>',$reservas->horainicio)
          ->orWhere('estado','!=','I')
          ->where('fecha','=',$codfecha)
          ->where('idarea','=',$reservas->idarea)
          ->where('horainicio','<',$reservas->horafinal)
          ->where('horafinal','>=',$reservas->horafinal)
          ->get();
          $contvaltodos = $resvaltodos->count();
          if($contvaltodos>0 && $reservas->estado=='I'){
          $sms='Está ocupada el área de estudio seleccionada';
          }

          if(($codfecha>$hoy) || ($codfecha==$hoy && $reservas->horainicio>$hora) || ($mquery>$mhoy)){
          $sms='Aún no puede comenzar esta reserva';
          }

          if(Auth::user()->idtipousuario<3){
            if($sms!=''){
            return view("operacion.consultas.create",["sms"=>$sms,"cod"=>$cod,"codnombre"=>$codnombre,"codfecha"=>$codfecha,"codhorario"=>$codhorario,"codarea"=>$codarea,"codcantidad"=>$codcantidad]);
            }
            else{

        $valida=Reserva::findOrFail($idr);
        if($valida->estado!='C'){
            $valida->horallegada=$hora;
            $valida->horacrea=$hora;
            $valida->estado='C';
            $valida->update();
          if($hora>=$valida->tiempoespera){
            $fin = $hora;
            $separa=explode(":",$fin);
            if($separa[0]>11 && $separa[1]>44){
            $nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
            $separan=explode(":",$nuevote);
            $nhora=$separa[0]+1;
            $nnuevote=$nhora.":".$separan[1].":00";
              if($nnuevote>=$valida->horafinal){
              $valida->tiempoespera=$valida->horafinal;
              $valida->update();
              }
              else{
              $valida->tiempoespera=$nnuevote;
              $valida->update();
              }
            }
            else{
              if($separa[0]>11){
              $nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
              $separan=explode(":",$nuevote);
              $nhora=$separa[0];
              $nnuevote=$nhora.":".$separan[1].":00";
              $valida->tiempoespera=$nnuevote;
              $valida->update();
              }
              else{
              $nuevote= date('h:i:s',strtotime($fin . ' +15 minutes'));
              $separan=explode(":",$nuevote);
              $nnuevote=$separan[0].":".$separan[1].":00";
              $valida->tiempoespera=$nnuevote;
              $valida->update();
              }
            }
          }
        }

          if($codfecha==$hoy && $valida->tiempoespera>$hora && $valida->horainicio<$hora && $valida->estado=='C'){
        $area=Area::findOrFail($valida->idarea);
        $usu = User::where('id', $valida->id)->where('estado','A')->first();
        Mail::send('email.mensajeresasistencia',['usu' => $usu,'valida' => $valida,'area'=>$area],
                function ($m) use ($usu) {
                  $m->to($usu->email, $usu->name)
                    ->subject('Reserva exitosa')
                    ->from('roseroesteban@gmail.com', 'Administrador');
                }
        );
        }

            $query='';
            $reservas=DB::table('reserva as r')
            ->leftjoin('users as u','u.id','=','r.id')
            ->leftjoin('area as a','a.idarea','=','r.idarea')
            ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.horallegada','r.cantidad','r.codigoqr')
            ->where('r.fecha','LIKE','%'.$query.'%')
            ->where('r.estado','=','C')
            ->orwhere('u.name','LIKE','%'.$query.'%')
            ->where('r.estado','=','C')
            ->orwhere('a.nombre','LIKE','%'.$query.'%')
            ->where('r.estado','=','C')
            ->orderBy('r.fecha','asc')
            ->paginate(9);
            return view("operacion.reservasconfirmadas.index",["reservas"=>$reservas,"searchText"=>$query]);
            }
          }
          else{
            return Redirect::to('/logout');
          } 
    }
  }

}
