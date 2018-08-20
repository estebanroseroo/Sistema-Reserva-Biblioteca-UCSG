<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Rol;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\RolFormRequest;
use DB;
use Auth;


class RolController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
    		$roles=DB::table('tipousuario as t')
            ->leftjoin('users as u','u.idtipousuario','=','t.idtipousuario')
            ->select('t.nombre', 't.idtipousuario', DB::raw('(CASE 
            WHEN t.idtipousuario=u.idtipousuario
            AND u.estado="A"
            THEN "lleno"
            ELSE "vacio"
            END) AS temporal'))
            ->where('t.nombre','LIKE','%'.$query.'%')
			->where('t.estado','=','A')
			->orderBy('t.idtipousuario','asc')
			->paginate(9);

            if(Auth::user()->idtipousuario<2){
            return view('mantenimiento.roles.index',["roles"=>$roles, "searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }   
    	}
    }

    public function create(){
        if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.roles.create");
            }
            else{
            return Redirect::to('/logout');
            }
    }

    public function store(RolFormRequest $request){
    	$rol=new Rol;
    	$rol->nombre=$request->get('nombre');
    	$rol->estado='A';
    	$rol->save();
    	return Redirect::to('mantenimiento/roles');
    }

    public function edit($id){
          if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.roles.edit",["tipousuario"=>Rol::findOrFail($id)]);
            }
            else{
            return Redirect::to('/logout');
            }
    }

    public function update(RolFormRequest $request, $id){
    	$rol=Rol::findOrFail($id);
    	$rol->nombre=$request->get('nombre');
    	$rol->update();
    	return Redirect::to('mantenimiento/roles');
    }

    public function destroy($id){
    	$rol=Rol::findOrFail($id);
    	$rol->estado='I';
    	$rol->update();
    	return Redirect::to('mantenimiento/roles');
    }
}
