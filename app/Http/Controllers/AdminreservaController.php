<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\AdminreservaFormRequest;
use sistemaReserva\Reserva;
use DB;
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

    public function create(){
        $usuarios=DB::table('users')->where('estado','=','A')->get();
        $areas=DB::table('area')->where('estado','=','A')->get();
        return view("operacion.adminreservas.create",["usuarios"=>$usuarios,"areas"=>$areas]);
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
