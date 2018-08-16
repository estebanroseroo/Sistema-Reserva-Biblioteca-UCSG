<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Reserva;
use sistemaReserva\Horario;
use DB;
use Auth;
use Carbon\Carbon;

class QrLoginController extends Controller
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

        if(Auth::user()->idtipousuario<3){
            return view("operacion.consultas.index",["reservas"=>$reservas,"searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }  
   	}
   }

   public function create(Request $request){
    $cod=$request->get('cod');
    $res = Reserva::where('codigoqr',$cod)->first();
    $codfecha=$res->fecha;
    $codhorario=$res->horainicio."-".$res->horafinal;
    $codcantidad=$res->cantidad;

    $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('u.name as nombreusuario','a.nombre as nombrearea')
        ->where('codigoqr',$cod)->first();

    $codnombre=$reservas->nombreusuario;
    $codarea=$reservas->nombrearea;

    if(Auth::user()->idtipousuario<3){
            return view("operacion.consultas.create",["codfecha"=>$codfecha,"codcantidad"=>$codcantidad,"codhorario"=>$codhorario,"codnombre"=>$codnombre,"codarea"=>$codarea]);
            }
            else{
            return Redirect::to('/logout');
            }  
   }

  function check(Request $request) {  
    $result =0;
    $hoy = Carbon::now()->format('d/m/Y');
    $hora = Carbon::now()->format('H:i:s');
    if ($request->data) {
        $res = Reserva::where('codigoqr',$request->data)->where('estado','A')->first();
        if($res){//verifica reserva activa
          if ($res->fecha==$hoy && $res->tiempoespera>$hora) {//verifica reserva es hoy y llega puntual
            if($res->horainicio<$hora){//llega impuntual
              $res->estado='C';
              $res->horallegada=$hora;
              $res->update();//se queda con estado C porque ya empezo su reserva
              }
            else{
              $res->horallegada=$hora;
              $res->update();//se queda con estado A porque aun no empieza su reserva
              }
              $result =$res->codigoqr;
              return $result;
          }
        }
    }
    else{
      return $result;
    }
  }

}
