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
use DateTime;

class UsureservaController extends Controller
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
            
      $monitorear=Reserva::where('estado','!=','I')->get();
      $hoy = Carbon::now()->format('d/m/Y');
      $hora = Carbon::now()->format('H:i:s');
      $query=trim($request->get('searchText'));
      
      foreach ($monitorear as $m) {
          $vhoy=explode("/",$hoy);
          $vquery=explode("/",$m->fecha);
          $vcrea=explode("/",$m->fechacrea);
          $dhoy=$vhoy[0];
          $mhoy=$vhoy[1];
          $ahoy=$vhoy[2];
          $dquery=$vquery[0];
          $mquery=$vquery[1];
          $aquery=$vquery[2];
          $dcrea=$vcrea[0];
          $mcrea=$vcrea[1];
          $acrea=$vcrea[2];
          if(($ahoy>$aquery) || ($ahoy==$aquery && $mhoy>$mquery) || ($ahoy==$aquery && $mhoy==$mquery && $dhoy>$dquery) || ($ahoy==$aquery && $mhoy==$mquery && $dhoy==$dquery && $m->horacrea<$m->tiempoespera && $hora>=$m->tiempoespera && $m->estado=='A')){
            $m->estado='I';
            $m->update();
            $area=Area::findOrFail($m->idarea);
            $usu = User::where('email',Auth::user()->email)->where('estado','A')->first();
            $reservas=Reserva::findOrFail($m->idreserva);
            Mail::send('email.mensajeresexpiro',['usu' => $usu,'reservas' => $reservas,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Código QR expiró')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );
          }
          else{
            if($m->estado=='C' && $hora>=$m->horafinal){
            $m->estado='I';
            $m->update();
            $area=Area::findOrFail($m->idarea);
            $usu = User::where('email',Auth::user()->email)->where('estado','A')->first();
            $reservas=Reserva::findOrFail($m->idreserva);
            Mail::send('email.mensajeresfinalizo',['usu' => $usu,'reservas' => $reservas,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Reserva finalizada')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );
            }
          }
      }

      $reservas=DB::table('reserva as r')
      ->leftjoin('users as u','u.id','=','r.id')
      ->leftjoin('area as a','a.idarea','=','r.idarea')
      ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad','r.codigoqr','r.estado','r.fecha')
      ->where('r.fecha','LIKE','%'.$query.'%')
      ->where('u.email','=',Auth::user()->email)
      ->where('r.estado','!=','I')
      ->orwhere('a.nombre','LIKE','%'.$query.'%')
      ->where('u.email','=',Auth::user()->email)
      ->where('r.estado','!=','I')
      ->orderBy('r.fechainicio','asc')
      ->paginate(9);

      if(Auth::user()->idtipousuario>2){
      return view('menu.reservas.index',["reservas"=>$reservas,"searchText"=>$query]);
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
        ->where('r.horainicio','<=',$queryinicio)
        ->where('r.horafinal','>',$queryinicio)
        ->where('r.estado','!=','I')
        ->orWhere('r.fecha','=',$query)
        ->where('r.horainicio','<',$queryfinal)
        ->where('r.horafinal','>=',$queryfinal)
        ->where('r.estado','!=','I')
        ->union($areas)
        ->get();
        $diferentes=$reservas->unique('nombre');
        $diferentes->values()->all();

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
        $r = Reserva::where('id', '=', Auth::user()->id)->where('estado','!=','I')->get();
        $rcont = $r->count();
        if($rcont>=5){
          $sms='No se puede realizar más de cinco reservas';
        }
        if($hmin<1){//hora final antes de hora inicio
          $sms='Se puede reservar mínimo una hora';
        }
        if($hmin>5){
          $sms='Se puede reservar máximo cinco horas';
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

          $contador=0;
          $contselect=0;
          $shi=(int)$queryinicio;
          $shf=(int)$queryfinal;
          $difsh=$shf-$shi;
          $reshora = Reserva::where('id', '=', Auth::user()->id)
          ->where('estado','!=','I')->where('fecha','=',$query)->get();
          foreach ($reshora as $rh) {
          $hi=(int)$rh->horainicio;
          $hf=(int)$rh->horafinal;
          $difh=$hf-$hi;
          $contador=$contador+$difh;
          }
          $sum=$difsh+$contador;
          if($sum>5){
          $sms='No se puede reservar más de cinco horas al día';
          }
          $resval = DB::table('reserva')
          ->where('id', '=', Auth::user()->id)
          ->where('estado','!=','I')
          ->where('fecha','=',$query)
          ->where('horainicio','<=',$queryinicio)
          ->where('horafinal','>',$queryinicio)
          ->orWhere('id', '=', Auth::user()->id)
          ->where('estado','!=','I')
          ->where('fecha','=',$query)
          ->where('horainicio','<',$queryfinal)
          ->where('horafinal','>=',$queryfinal)
          ->get();
          $contval = $resval->count();
          if($contval>0){
          $sms='Ya dispone de una reserva en el horario seleccionado';
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
    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
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
    $reserva->fechacrea=$hoy;
    $reserva->horacrea=$hora;
    $sfecha=explode("/",$request->get('fecha'));
    $nfecha=$sfecha[2]."/".$sfecha[1]."/".$sfecha[0]." ".$request->get('horainicio');
    $reserva->fechainicio=$nfecha;
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

    $area=Area::findOrFail($reserva->idarea);
    $usu = User::where('email',Auth::user()->email)->where('estado','A')->first();
    Mail::send('email.mensajereseli',['usu' => $usu,'reserva' => $reserva,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Eliminación exitosa')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );

      return Redirect::to('menu/reservas');
   }
        
}
