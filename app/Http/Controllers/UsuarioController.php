<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\UsuarioFormRequest;
use sistemaReserva\User;
use DB;

class UsuarioController extends Controller
{
   public function __construct(){
   	$this->middleware('auth');
   }

   public function index(Request $request){
   	if($request){
   		$query=trim($request->get('searchText'));
   		$usuarios=DB::table('users as u')
         ->join('facultad as f','u.idfacultad','=','f.idfacultad')
         ->join('carrera as c','c.idcarrera','=','u.idcarrera')
         ->select('u.id','u.name','u.email','u.telefono','f.nombre as facultad','c.nombre as carrera')
         ->where('u.name','LIKE','%'.$query.'%')
         ->where('u.estado','=','A')
         ->orwhere('f.nombre','LIKE','%'.$query.'%')
         ->where('u.estado','=','A')
         ->orwhere('c.nombre','LIKE','%'.$query.'%')
         ->where('u.estado','=','A')
   		->orderBy('u.id','asc')
   		->paginate(9);
   		return view('mantenimiento.usuarios.index',["usuarios"=>$usuarios,"searchText"=>$query]);
   	}
   }

   public function getStates($id) {
        $carreras = DB::table('carrera')
        ->where('idfacultad',$id)
        ->where('estado','=','A')
        ->pluck('nombre','idcarrera');
        return json_encode($carreras);
    }

    public function create(){
        $facultades=DB::table('facultad')
        ->where('estado','=','A')
        ->pluck('nombre','idfacultad');
        return view("mantenimiento.usuarios.create",compact('facultades'));
    }

   public function store(UsuarioFormRequest $request){
   	$usuario=new User;
   	$usuario->name=$request->get('name');
   	$usuario->email=$request->get('email');
   	$usuario->password=bcrypt($request->get('password'));
    $usuario->telefono=$request->get('telefono');
    $usuario->idfacultad=$request->get('idfacultad');
    $usuario->idcarrera=$request->get('idcarrera');
    $usuario->estado='A';
   	$usuario->save();
   	return Redirect::to('mantenimiento/usuarios');
   }

   public function edit($id){
      $usuario=User::findOrFail($id);
      $facultades=DB::table('facultad')->where('estado','=','A')->get();
      $carreras=DB::table('carrera as c')
      ->join('facultad as f','f.idfacultad','=','c.idfacultad')
      ->join('users as u', 'c.idcarrera','=','u.idcarrera')
      ->select('c.idcarrera','c.nombre')
      ->where('c.estado','=','A')->get();
      return view("mantenimiento.usuarios.edit",["usuario"=>$usuario,"facultades"=>$facultades,"carreras"=>$carreras]);
   }

   public function update(UsuarioFormRequest $request, $id){
      $usuario=User::findOrFail($id);
      $usuario->name=$request->get('name');
      $usuario->email=$request->get('email');
      $usuario->password=bcrypt($request->get('password'));
      $usuario->telefono=$request->get('telefono');
      $usuario->idfacultad=$request->get('idfacultad');
      $usuario->idcarrera=$request->get('idcarrera');
      $usuario->update();
      return Redirect::to('mantenimiento/usuarios');
   }

   public function destroy($id){
      $usuario=User::findOrFail($id);
      $usuario->estado='I';
      $usuario->update();
      return Redirect::to('mantenimiento/usuarios');
   }
}
