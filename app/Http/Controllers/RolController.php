<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Rol;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\RolFormRequest;
use DB;


class RolController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
    		$roles=DB::table('tipousuario')
            ->where('nombre','LIKE','%'.$query.'%')
			->where('estado','=','A')
			->orderBy('idtipousuario','asc')
			->paginate(9);

			return view('mantenimiento.roles.index',["roles"=>$roles, "searchText"=>$query]);
    	}
    }

    public function create(){
    	return view("mantenimiento.roles.create");
    }

    public function store(RolFormRequest $request){
    	$rol=new Rol;
    	$rol->nombre=$request->get('nombre');
    	$rol->estado='A';
    	$rol->save();
    	return Redirect::to('mantenimiento/roles');
    }

    public function show($id){
    	return view("mantenimiento.roles.show",["tipousuario"=>Rol::findOrFail($id)]);
    }

    public function edit($id){
    	return view("mantenimiento.roles.edit",["tipousuario"=>Rol::findOrFail($id)]);
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
