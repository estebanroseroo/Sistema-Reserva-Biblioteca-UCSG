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
use DB;
use Auth;
use Carbon\Carbon;

class UsureservaController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    }

  public function index(Request $request){
    if($request){
      $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad','r.codigoqr')
        ->where('u.email','=',Auth::user()->email)
        ->where('r.estado','=','A')->get();

        return view('menu.reservas.index',["reservas"=>$reservas]);
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

        $areas=DB::table("area")
        ->where('estado','=','A')
        ->where('nombre','=',$qnombre)->first();

        $horarioinicio=DB::table('horario')
        ->where('estado','=','A')
        ->where('horainicio','LIKE', explode("-", $qhorainicio))->first();
      
        //var_dump($horarioinicio);
        //die();
 
        return view("menu.reservas.edit",["enombre"=>$qnombre,"ecapacidad"=>$qcapacidad,"efecha"=>$qfecha,"ehorainicio"=>$qhorainicio,"areas"=>$areas,"horarioinicio"=>$horarioinicio]);
      }
    }

  public function create(Request $request){
    if($request){
        $query=trim($request->get('fecha'));
        $queryinicio=trim($request->get('horarios'));

        $hi=explode("-",$queryinicio);
        $h=$hi[0];

        $horarios=DB::table('horario')->where('estado','=','A')->get();

        $areas = DB::table("area as a")
        ->select("a.nombre","a.capacidad","a.disponibilidad","a.estado","a.idarea")
        ->where('estado','=','A');

        $reservas = DB::table("reserva as r")
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select("a.nombre","a.capacidad","r.fecha","r.horainicio","r.horafinal")
        ->where('r.fecha','=',$query)
        ->where('r.horainicio','=',$h)
        ->where('r.estado','=','A')
        ->union($areas)
        ->get();

        $diferentes=$reservas->unique('nombre');
        $diferentes->values()->all();
        //print_r($reservas);

        return view("menu.reservas.create",["fecha"=>$query,"inicio"=>$queryinicio,"horarios"=>$horarios,"reservas"=>$reservas,"areas"=>$areas,"diferentes"=>$diferentes]);
      }
    }

    public function store(UsureservaFormRequest $request){
    $reserva=new Reserva;
    $reserva->fecha=$request->get('fecha');
    $reserva->horainicio=$request->get('horainicio');
    $reserva->horafinal=$request->get('horafinal');
    $reserva->horallegada=$request->get('horallegada');
    $reserva->tiempoespera=$request->get('tiempoespera');
    $reserva->tiempocancelar=$request->get('tiempocancelar');
    $reserva->cantidad=$request->get('cantidad');
    $reserva->id=Auth::user()->id;
    $reserva->idarea=$request->get('idarea');
    $reserva->estado='A';
    $reserva->codigoqr=str_random(40);
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

    return Redirect::to('menu/reservas');
   }

   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();
      return Redirect::to('menu/reservas');
   }
        
}
