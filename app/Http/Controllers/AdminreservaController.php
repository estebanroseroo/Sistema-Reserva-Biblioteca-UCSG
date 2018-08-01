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

class AdminreservaController extends Controller
{
    public function __construct(){
   	$this->middleware('auth');
   }

   public function index(Request $request){
   	if($request){
   		$query=trim($request->get('searchText'));
   		$reservas=DB::table('reserva as r')
      	->leftjoin('users as u','u.id','=','r.id')
      	->leftjoin('area as a','a.idarea','=','r.idarea')
      	->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad')
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

        $usuarios=DB::table('users')->where('estado','=','A')->get();

        $areas=DB::table("area")
        ->where('estado','=','A')
        ->where('nombre','=',$qnombre)->first();

        $horarioinicio=DB::table('horario')
        ->where('estado','=','A')
        ->where('horainicio','LIKE', explode("-", $qhorainicio))->first();
      
        //var_dump($horarioinicio);
        //die();
 
        return view("operacion.adminreservas.edit",["enombre"=>$qnombre,"ecapacidad"=>$qcapacidad,"efecha"=>$qfecha,"ehorainicio"=>$qhorainicio,"usuarios"=>$usuarios,"areas"=>$areas,"horarioinicio"=>$horarioinicio]);
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
        //print_r($diferentes);

        return view("operacion.adminreservas.create",["fecha"=>$query,"inicio"=>$queryinicio,"horarios"=>$horarios,"reservas"=>$reservas,"areas"=>$areas,"diferentes"=>$diferentes]);
      }
    }

    public function store(AdminreservaFormRequest $request){
    $reserva=new Reserva;
    $reserva->fecha=$request->get('fecha');
    $reserva->horainicio=$request->get('horainicio');
    $reserva->horafinal=$request->get('horafinal');
    $reserva->horallegada=$request->get('horallegada');
    $reserva->tiempoespera=$request->get('tiempoespera');
    $reserva->tiempocancelar=$request->get('tiempocancelar');
    $reserva->cantidad=$request->get('cantidad');
    $reserva->id=$request->get('id');
    $reserva->idarea=$request->get('idarea');
    $reserva->estado='A';
    $reserva->save();
    return Redirect::to('operacion/adminreservas');
   }


   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();
      return Redirect::to('operacion/adminreservas');
   }
}
