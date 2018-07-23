<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\UsureservaFormRequest;
use sistemaReserva\Reserva;
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
      	->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad')
        ->where('u.email','=',Auth::user()->email)
        ->where('r.estado','=','A')->get();
   		return view('menu.reservas.index',["reservas"=>$reservas]);
   	}
   }

   function showDate(Request $request){
       dd($request->date);
    }

	public function create(){
        $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.cantidad')
        ->where('u.email','=',Auth::user()->email)
        ->where('r.estado','=','A')->get();
        $usuarios=DB::table('users')->where('estado','=','A')->get();
        $areas=DB::table('area')->where('estado','=','A')->get();
        return view("menu.reservas.create",["usuarios"=>$usuarios,"areas"=>$areas,"reservas"=>$reservas]);
    }

   /*public function store(AdminreservaFormRequest $request){
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
   }*/

   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();
      return Redirect::to('menu/reservas');
   }
        
}
