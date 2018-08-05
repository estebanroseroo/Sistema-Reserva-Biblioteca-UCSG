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

class AdminreservaconfirmadaController extends Controller
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

   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();
      return Redirect::to('operacion/reservasconfirmadas');
   }
}
