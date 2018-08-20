<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Http\Requests\UsureservaFormRequest;
use sistemaReserva\Reserva;
use sistemaReserva\Horario;
use sistemaReserva\Area;
use DB;
use Auth;
use Carbon\Carbon;
use sistemaReserva\User;
use Mail;

class UsureservaController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    }

  public function index(Request $request){
    if($request){
      $monitorear=Reserva::where('estado','A')->get();
      $hoy = Carbon::now()->format('d/m/Y');
      $hora = Carbon::now()->format('H:i:s');
      foreach ($monitorear as $m) {
         if ($m->fecha==$hoy && $m->tiempoespera<$hora && is_null($m->horallegada)==1){
             $m->estado='I';
             $m->update();
         }
      }
      
      $reservas=DB::table('reserva as r')
      ->leftjoin('users as u','u.id','=','r.id')
      ->leftjoin('area as a','a.idarea','=','r.idarea')
      ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad','r.codigoqr')
      ->where('u.email','=',Auth::user()->email)
      ->where('r.estado','=','A')->get();

      if(Auth::user()->idtipousuario>2){
      return view('menu.reservas.index',["reservas"=>$reservas]);
      }
      else{
      return Redirect::to('/logout');
      }  
    }
   }

   function showDate(Request $request){
       dd($request->date);
    }  

    public function show(Request $request){
    if($request){
        $qnombre=trim($request->get('enombre'));
        $qcapacidad=trim($request->get('ecapacidad'));
        $qfecha=trim($request->get('efecha'));
        $qhorainicio=trim($request->get('ehorainicio'));
        $qhorafinal=trim($request->get('ehorafinal'));
        $qhoraid=trim($request->get('ehoraid'));
        $areas=DB::table("area")
        ->where('estado','=','A')
        ->where('nombre','=',$qnombre)->first();
        $horarioinicio=DB::table('horario')
        ->where('estado','=','A')
        ->where('hora','=', $qhorainicio)->first();
        //var_dump($horarioinicio);
        //die();

      if(Auth::user()->idtipousuario>2){
      return view("menu.reservas.edit",["enombre"=>$qnombre,"ecapacidad"=>$qcapacidad,"efecha"=>$qfecha,"ehorainicio"=>$qhorainicio,"areas"=>$areas,"horarioinicio"=>$horarioinicio,"ehorafinal"=>$qhorafinal,"ehoraid"=>$qhoraid]);
      }
      else{
      return Redirect::to('/logout');
      }  
      }
    }

  public function create(Request $request){
    if($request){
        $query=trim($request->get('fecha'));
        $queryinicio=trim($request->get('horainicio'));//11:00:00
        $queryfinal=trim($request->get('horafinal'));//12:00:00
        $hoy = Carbon::now()->format('d/m/Y');
        $hora = Carbon::now()->format('H:i:s');
        $horarios=DB::table('horario')->where('estado','=','A')->orderBy('hora','asc')->get();
        $primerhora= Horario::where('estado','=','A')->orderBy('hora','asc')->first();
        $horariosf=DB::table('horario')->where('hora','!=',$primerhora->hora)->where('estado','=','A')->orderBy('hora','asc')->get();
        $horaid=DB::table('horario')->where('hora','=',$queryinicio)->where('estado','=','A')->first();
        $areas = DB::table("area as a")
        ->select("a.nombre","a.capacidad","a.disponibilidad","a.estado","a.idarea")
        ->where('estado','=','A')
        ->where('disponibilidad','=','Disponible');
        $reservas = DB::table("reserva as r")
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select("a.nombre","a.capacidad","r.fecha","r.horainicio","r.horafinal")
        ->where('r.fecha','=',$query)
        ->where('r.horainicio','=',$queryinicio)
        ->where('r.estado','=','A')
        ->union($areas)
        ->get();
        $diferentes=$reservas->unique('nombre');
        $diferentes->values()->all();
        //print_r($diferentes);

        $sms='';
        if($query=='' && $queryinicio!=''){//si fecha vacia
          $sms='El campo fecha es obligatorio';
        }
        if($query!=''){
        $vqinicio=explode(":",$queryinicio);
        $vqfinal=explode(":",$queryfinal);
        $vhora=explode(":",$hora);
        $hvqinicio=$vqinicio[0];
        $hvqfinal=$vqfinal[0];
        $hvhora=$vhora[0];
        $hmin=$hvqfinal-$hvqinicio;
        if($hmin<1){//hora final antes de hora inicio
          $sms='Se puede reservar mínimo una hora';
        }
        if($hmin>6){
          $sms='Se puede reservar máximo seis horas';
        }
        if($query==$hoy && $hvqinicio<$hvhora){//si hoy hora inicio es pasado
          $sms='La hora seleccionada no es válida';
        }
        
          $vhoy=explode("/",$hoy);
          $vquery=explode("/",$query);
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
          $sms='Se puede reservar máximo con un mes de anticipación'; 
          }
          else{//2018
            if($mhoy>$mquery){//Julio
            $sms='La fecha seleccionada no es válida';  
            }  
            elseif ($mhoymas<$mquery) {//Octubre
            $sms='Se puede reservar máximo con un mes de anticipación'; 
            } 
            else{//Agosto o Septiembre
              if($mhoy==$mquery && $dhoy>$dquery){//Agosto 17
              $sms='La fecha seleccionada no es válida';
              }
              elseif($mhoymas==$mquery && $dhoy<$dquery){//Septiembre 19
              $sms='Se puede reservar máximo con un mes de anticipación'; 
              }
            }
          }
        }
        
      if(Auth::user()->idtipousuario>2){
      return view("menu.reservas.create",["fecha"=>$query,"inicio"=>$queryinicio,"final"=>$queryfinal,"horarios"=>$horarios,"horariosf"=>$horariosf,"reservas"=>$reservas,"areas"=>$areas,"diferentes"=>$diferentes,"horaid"=>$horaid,"sms"=>$sms]);
      }
      else{
      return Redirect::to('/logout');
      }  

      }
    }

    public function store(UsureservaFormRequest $request){
    $reserva=new Reserva;
    $reserva->fecha=$request->get('fecha');
    $reserva->horainicio=$request->get('horainicio');
    $reserva->horafinal=$request->get('horafinal');
    $reserva->horallegada=$request->get('horallegada');
    $reserva->tiempoespera=$request->get('tiempoespera');
    $reserva->cantidad=$request->get('cantidad');
    $reserva->id=Auth::user()->id;
    $reserva->idarea=$request->get('idarea');
    $reserva->estado='A';
    $reserva->codigoqr=str_random(10);
    $reserva->idhora=$request->get('horaid');
    $reserva->save();

    $fin = $reserva->horainicio;
    $separa=explode(":",$fin);//16 00 00
    $separa[0];//16
    $ms=":".$separa[1].":".$separa[2];//:00:00
    $quince = strtotime("+15 minutes", strtotime($ms));//:15:00
    $uno=date(':i:s', $quince);
    $espera=$separa[0].$uno;//16:15:00

    $reserva->tiempoespera=$espera;
    $reserva->update();

    $area=Area::findOrFail($reserva->idarea);
    $reservas=DB::table('reserva')->where('estado','=','A')->get();
    $qrcod = $reserva->codigoqr;
    $usu = User::where('email',Auth::user()->email)->where('estado','A')->first();
    Mail::send('email.mensajeqr',['usu' => $usu,'reservas' => $reservas,'qrcod' => $qrcod,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Código QR de su reserva')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );

    return Redirect::to('menu/reservas');
   }

   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();
      return Redirect::to('menu/reservas');
   }
        
}
