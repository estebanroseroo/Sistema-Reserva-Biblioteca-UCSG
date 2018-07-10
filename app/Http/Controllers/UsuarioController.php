<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\User;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\UsuarioFormRequest;
use DB;

class UsuarioController extends Controller
{
   public function __construct(){
   	$this->middleware('auth');
   }

   public function index(Request $request){
   	if($request){
   		$query=trim($request->get('searchText'));
   		$usuarios=DB::table('users')
         ->where('name','LIKE','%'.$query.'%')
         ->where('estado','=','A')
         ->orwhere('email','LIKE','%'.$query.'%')
         ->where('estado','=','A')
   		->orderBy('id','asc')
   		->paginate(9);
   		return view('mantenimiento.usuarios.index',["usuarios"=>$usuarios,"searchText"=>$query]);
   	}
   }

   public function create(){
   	return view("mantenimiento.usuarios.create");
   }

   public function store(UsuarioFormRequest $request){
   	$usuario=new User;
   	$usuario->name=$request->get('name');
   	$usuario->email=$request->get('email');
   	$usuario->password=bcrypt($request->get('password'));
      $usuario->telefono=$request->get('telefono');
      $usuario->estado='A';
   	$usuario->save();
   	return Redirect::to('mantenimiento/usuarios');
   }

   public function edit($id){
      return view("mantenimiento.usuarios.edit",["usuario"=>User::findOrFail($id)]);
   }

   public function update(UsuarioFormRequest $request, $id){
      $usuario=User::findOrFail($id);
      $usuario->name=$request->get('name');
      $usuario->email=$request->get('email');
      $usuario->password=bcrypt($request->get('password'));
      $usuario->telefono=$request->get('telefono');
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
