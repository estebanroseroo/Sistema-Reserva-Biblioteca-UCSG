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
use DB;
use Auth;
use Carbon\Carbon;
use sistemaReserva\User;
use Mail;

class AdminreservaController extends Controller
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
      
      $query=trim($request->get('searchText'));
      $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad','r.codigoqr')
        ->where('r.fecha','LIKE','%'.$query.'%')
        ->where('r.estado','=','A')
        ->orwhere('u.name','LIKE','%'.$query.'%')
        ->where('r.estado','=','A')
        ->orwhere('a.nombre','LIKE','%'.$query.'%')
        ->where('r.estado','=','A')
        ->orderBy('r.fecha','asc')
        ->paginate(9);
      return view("operacion.adminreservas.index",["reservas"=>$reservas,"searchText"=>$query]);
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
        $qhoraid=trim($request->get('ehoraid'));

        $usuarios=DB::table('users')->where('estado','=','A')->get();

        $areas=DB::table("area")
        ->where('estado','=','A')
        ->where('nombre','=',$qnombre)->first();

        $horarioinicio=DB::table('horario')
        ->where('estado','=','A')
        ->where('horainicio','LIKE', explode("-", $qhorainicio))->first();
      
        //var_dump($horarioinicio);
        //die();
 
        return view("operacion.adminreservas.edit",["enombre"=>$qnombre,"ecapacidad"=>$qcapacidad,"efecha"=>$qfecha,"ehorainicio"=>$qhorainicio,"usuarios"=>$usuarios,"areas"=>$areas,"horarioinicio"=>$horarioinicio,"ehoraid"=>$qhoraid]);
      }
    }

   public function create(Request $request){
    if($request){
        $query=trim($request->get('fecha'));
        $queryinicio=trim($request->get('horarios'));

        $hi=explode("-",$queryinicio);
        $h=$hi[0];

        $horarios=DB::table('horario')->where('estado','=','A')->get();

        $horaid=DB::table('horario')->where('horainicio','=',$h)->where('estado','=','A')->first();

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
        //print_r($diferentes);

        return view("operacion.adminreservas.create",["fecha"=>$query,"inicio"=>$queryinicio,"horarios"=>$horarios,"reservas"=>$reservas,"areas"=>$areas,"diferentes"=>$diferentes,"horaid"=>$horaid]);
      }
    }

    public function store(AdminreservaFormRequest $request){
    $reserva=new Reserva;
    $reserva->fecha=$request->get('fecha');
    $reserva->horainicio=$request->get('horainicio');
    $reserva->horafinal=$request->get('horafinal');
    $reserva->horallegada=$request->get('horallegada');
    $reserva->tiempoespera=$request->get('tiempoespera');
    $reserva->cantidad=$request->get('cantidad');
    $reserva->id=$request->get('id');
    $reserva->idarea=$request->get('idarea');
    $reserva->estado='A';
    $reserva->codigoqr=str_random(40);
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

    $reservas=DB::table('reserva')->where('estado','=','A')->get();
    $qrcod = $reserva->codigoqr;
    $usu = User::findOrFail($request->get('id'));
    Mail::send('email.mensajeqr',['usu' => $usu,'reservas' => $reservas,'qrcod' => $qrcod],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Código QR de su reserva')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );
    
    return Redirect::to('operacion/adminreservas');
   }


   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();
      return Redirect::to('operacion/adminreservas');
   }
}
