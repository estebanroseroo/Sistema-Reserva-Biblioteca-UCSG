<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\ContrasenaFormRequest;
use sistemaReserva\User;
use DB;
use Auth;

class ContrasenaController extends Controller
{
   public function __construct(){
   	$this->middleware('auth');
   }

   public function index(Request $request){
   	if($request){
   		$query=trim($request->get('variable'));
   		$usuarios=DB::table('users as u')
      ->leftjoin('facultad as f','u.idfacultad','=','f.idfacultad')
      ->leftjoin('carrera as c','u.idcarrera','=','c.idcarrera')
      ->select('u.id','u.name','u.email','u.telefono','f.nombre as facultad','c.nombre as carrera')
      ->where('u.email','=', $query)
      ->where('u.estado','=','A')->get();
   		return view('menu.perfiles.index',["usuarios"=>$usuarios,"variable"=>$query]);
   	}
   }

   public function edit($id){
   $usuario=User::findOrFail($id);
   if(Auth::user()->idtipousuario==1){
    $layout='layouts.admin';
   }
   else if (Auth::user()->idtipousuario==2){
    $layout='layouts.gestor';
   }
   else{
    $layout='layouts.usu';
   }
   return view("menu.change",["usuario"=>$usuario,"layout"=>$layout]);
   }

   public function update(ContrasenaFormRequest $request, $id){
      $usuario=User::findOrFail($id);
      $usuario->password=bcrypt($request->get('password'));
      $usuario->update();

      $usuarios=DB::table('users as u')
      ->leftjoin('facultad as f','u.idfacultad','=','f.idfacultad')
      ->leftjoin('carrera as c','u.idcarrera','=','c.idcarrera')
      ->select('u.id','u.name','u.email','u.telefono','f.nombre as facultad','c.nombre as carrera')
      ->where('u.id','=', $id)
      ->where('u.estado','=','A')->get();

      if(Auth::user()->idtipousuario<3){
         $query='';
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
      else{
         return view('menu.perfiles.index',["usuarios"=>$usuarios]);
      }
   }
}
