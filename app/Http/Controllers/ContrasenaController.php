<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\ContrasenaFormRequest;
use sistemaReserva\User;
use DB;

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
   		return view('menu.perfil.index',["usuarios"=>$usuarios,"variable"=>$query]);
   	}
   }

   public function edit($id){
   $usuario=User::findOrFail($id);
   return view("menu.change",["usuario"=>$usuario]);
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
      return view('menu.perfiles.index',["usuarios"=>$usuarios]);
   }
}
