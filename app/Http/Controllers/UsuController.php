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


class UsuController extends Controller
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
    ->where('u.estado','=','I')
    ->orwhere('f.nombre','LIKE','%'.$query.'%')
    ->where('u.estado','=','I')
    ->orwhere('c.nombre','LIKE','%'.$query.'%')
    ->where('u.estado','=','I')
    ->orwhere('t.nombre','LIKE','%'.$query.'%')
    ->where('u.estado','=','I')
    ->orderBy('u.name','asc')
    ->paginate(9);

    	if(Auth::user()->idtipousuario<2){
        return view('mantenimiento.usuarioseli.index',["usuarios"=>$usuarios,"searchText"=>$query]);
        }
        else{
        return Redirect::to('/logout');
        }  
    }
   	}

    public function destroy($id){
    $usuario=User::findOrFail($id);
    $usuario->estado='A';
    $usuario->update();

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
}


   
