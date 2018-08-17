<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\UsuarioFormRequest;
use sistemaReserva\Http\Requests\PerfilFormRequest;
use sistemaReserva\User;
use sistemaReserva\Area;
use sistemaReserva\Reserva;
use sistemaReserva\Facultad;
use sistemaReserva\Carrera;
use DB;
use Auth;
use Mail;

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
      ->select('u.id','u.name','u.email','u.telefono','f.nombre as facultad','c.nombre as carrera','t.nombre as rol','u.idtipousuario')
      ->where('u.name','LIKE','%'.$query.'%')
      ->where('u.estado','=','A')
      ->orwhere('f.nombre','LIKE','%'.$query.'%')
      ->where('u.estado','=','A')
      ->orwhere('c.nombre','LIKE','%'.$query.'%')
      ->where('u.estado','=','A')
      ->orwhere('t.nombre','LIKE','%'.$query.'%')
      ->where('u.estado','=','A')
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

    public function getFacu($idtipousu) {
        $facultades = DB::table('facultad')
        ->where('estado','=','A')
        ->pluck('nombre','idfacultad');
        return json_encode($facultades);
    }

    public function create(){
        $facultades=DB::table('facultad')
        ->where('estado','=','A')
        ->pluck('nombre','idfacultad');
        $roles=DB::table('tipousuario')
        ->where('estado','=','A')->get();
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
    if($request->get('idtipousuariousu')<3){
      $idfac=$request->get('idfacultad');//NULL
      $idcar=$request->get('idcarrera');//NULL
    }
    else{
      $idfac=$request->get('idfacultadusu');
      $idcar=$request->get('idcarrerausu');
    }
    $usuario->idfacultad=$idfac;
    $usuario->idcarrera=$idcar;
    $usuario->idtipousuario=$request->get('idtipousuariousu');
    $usuario->estado='A';
    $usuario->save();

    if($usuario->idtipousuario>2){
    $facultad=Facultad::findOrFail($usuario->idfacultad);
    $carrera=Carrera::findOrFail($usuario->idcarrera);
    Mail::send('email.mensajeusu',['usuario' => $usuario,'facultad'=>$facultad,'carrera'=>$carrera],
        function ($m) use ($usuario) {
          $m->to($usuario->email, $usuario->name)
            ->subject('Registro exitoso')
            ->from('roseroesteban@gmail.com', 'Administrador');
        }
    );
    }

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
      if($request->get('idtipousuariousu')<3){
      $idfac=$request->get('idfacultad');//NULL
      $idcar=$request->get('idcarrera');//NULL
      }
      else{
      $idfac=$request->get('idfacultadusu');
      $idcar=$request->get('idcarrerausu');
      }
      $usuario->idfacultad=$idfac;
      $usuario->idcarrera=$idcar;
      $usuario->idtipousuario=$request->get('idtipousuariousu');
      $usuario->update();

      if($usuario->idtipousuario>2){
      $facultad=Facultad::findOrFail($usuario->idfacultad);
      $carrera=Carrera::findOrFail($usuario->idcarrera);
      Mail::send('email.mensajeusuedit',['usuario' => $usuario,'facultad'=>$facultad,'carrera'=>$carrera],
        function ($m) use ($usuario) {
          $m->to($usuario->email, $usuario->name)
            ->subject('Actualización exitosa')
            ->from('roseroesteban@gmail.com', 'Administrador');
        }
      );
      }

      return Redirect::to('mantenimiento/usuarios');
   }

   public function destroy($id){
      $usuario=User::findOrFail($id);
      $usuario->estado='I';
      $usuario->update();

      Mail::send('email.mensajeusueli',['usuario' => $usuario],
        function ($m) use ($usuario) {
          $m->to($usuario->email, $usuario->name)
            ->subject('Usuario eliminado')
            ->from('roseroesteban@gmail.com', 'Administrador');
        }
      );

      $reserva=Reserva::where('estado','A')->where('id',$usuario->id)->get();
      foreach($reserva as $r){
      $r->estado='I';
      $r->update();
      $area=Area::findOrFail($r->idarea);
      Mail::send('email.mensajeusuario',['usuario' => $usuario,'r' => $r,'area'=>$area],
        function ($m) use ($usuario) {
          $m->to($usuario->email, $usuario->name)
            ->subject('Reserva cancelada')
            ->from('roseroesteban@gmail.com', 'Administrador');
        }
      );
      }

      return Redirect::to('mantenimiento/usuarios');
   }
}
