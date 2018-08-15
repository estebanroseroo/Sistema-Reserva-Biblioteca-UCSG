<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\UsuarioFormRequest;
use sistemaReserva\Http\Requests\PerfilFormRequest;
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
      ->leftjoin('facultad as f','u.idfacultad','=','f.idfacultad')
      ->leftjoin('carrera as c','u.idcarrera','=','c.idcarrera')
      ->leftjoin('tipousuario as t','u.idtipousuario','=','t.idtipousuario')
      ->select('u.id','u.name','u.email','u.telefono','f.nombre as facultad','c.nombre as carrera','t.nombre as rol')
      ->where('u.name','LIKE','%'.$query.'%')
      ->where('u.estado','=','A')
      ->orwhere('f.nombre','LIKE','%'.$query.'%')
      ->orwhere('c.nombre','LIKE','%'.$query.'%')
      ->orwhere('t.nombre','LIKE','%'.$query.'%')
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
        $roles=DB::table('tipousuario')
        ->where('estado','=','A')
        ->pluck('nombre','idtipousuario');
        return view("mantenimiento.usuarios.create",["facultades"=>$facultades,"roles"=>$roles]);
    }

   public function store(UsuarioFormRequest $request){
    $usuario=new User;
    $usuario->name=$request->get('name').".".$request->get('apellido');
    $usuario->email=$request->get('email');
    $usuario->password=bcrypt($request->get('password'));
    $usuario->telefono=$request->get('telefono');
    $usuario->idfacultad=$request->get('idfacultad');
    $usuario->idcarrera=$request->get('idcarrera');
    $usuario->idtipousuario=$request->get('idtipousuario');
    $usuario->estado='A';
    $usuario->save();
    return Redirect::to('mantenimiento/usuarios');
   }

   public function edit($id){
      $usuario=User::findOrFail($id);
      $separa=explode(".",$usuario->name);
      $usunombre=$separa[0];
      $usuapellido=$separa[1];
      $facultades=DB::table('facultad')->where('estado','=','A')->get();
      $carreras=DB::table('carrera as c')
      ->join('facultad as f','f.idfacultad','=','c.idfacultad')
      ->join('users as u', 'c.idcarrera','=','u.idcarrera')
      ->select('c.idcarrera','c.nombre')
      ->where('c.estado','=','A')->get();
       $roles=DB::table('tipousuario')
        ->where('estado','=','A')
        ->pluck('nombre','idtipousuario');
      return view("mantenimiento.usuarios.edit",["usuario"=>$usuario,"facultades"=>$facultades,"carreras"=>$carreras,"usunombre"=>$usunombre,"usuapellido"=>$usuapellido,"roles"=>$roles]);
   }

   public function update(PerfilFormRequest $request, $id){
      $usuario=User::findOrFail($id);
      $usuario->name=$request->get('name').".".$request->get('apellido');
      $usuario->telefono=$request->get('telefono');
      $usuario->idfacultad=$request->get('idfacultadedit');
      $usuario->idcarrera=$request->get('idcarreraedit');
      $usuario->idtipousuario=$request->get('idtipousuario');
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
