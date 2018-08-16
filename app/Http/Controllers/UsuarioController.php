<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\UsuarioFormRequest;
use sistemaReserva\Http\Requests\PerfilFormRequest;
use sistemaReserva\User;
use DB;
use Auth;

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
      ->orderBy('u.idtipousuario','asc')
      ->paginate(9);

      if(Auth::user()->idtipousuario<2){
            return view('mantenimiento.usuarios.index',["usuarios"=>$usuarios,"searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }  
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
        if(Auth::user()->idtipousuario<2){
             return view("mantenimiento.usuarios.create",["facultades"=>$facultades,"roles"=>$roles]);
            }
            else{
            return Redirect::to('/logout');
            }
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
      ->select('c.idcarrera','c.nombre','c.idfacultad')
      ->where('c.estado','=','A')
      ->where('c.idfacultad','=',$usuario->idfacultad)->get();
      $roles=DB::table('tipousuario')
      ->where('estado','=','A')->get();

      if(Auth::user()->idtipousuario<2){
          return view("mantenimiento.usuarios.edit",["usuario"=>$usuario,"facultades"=>$facultades,"carreras"=>$carreras,"usunombre"=>$usunombre,"usuapellido"=>$usuapellido,"roles"=>$roles]);
            }
            else{
            return Redirect::to('/logout');
            }
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
