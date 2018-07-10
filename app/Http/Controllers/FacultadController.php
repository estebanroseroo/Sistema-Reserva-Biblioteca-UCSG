<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Facultad;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\FacultadFormRequest;
use DB;

class FacultadController extends Controller
{
	public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
    		$facultades=DB::table('facultad')
            ->where('nombre','LIKE','%'.$query.'%')
			->where('estado','=','A')
			->orderBy('idfacultad','asc')
			->paginate(9);

			return view('mantenimiento.facultades.index',["facultades"=>$facultades, "searchText"=>$query]);
    	}
    }

    public function create(){
    	return view("mantenimiento.facultades.create");
    }

    public function store(FacultadFormRequest $request){
    	$facultad=new Facultad;
    	$facultad->nombre=$request->get('nombre');
    	$facultad->estado='A';
    	$facultad->save();
    	return Redirect::to('mantenimiento/facultades');
    }

    public function show($id){
    	return view("mantenimiento.facultades.show",["facultad"=>Facultad::findOrFail($id)]);
    }

    public function edit($id){
    	return view("mantenimiento.facultades.edit",["facultad"=>Facultad::findOrFail($id)]);
    }

    public function update(FacultadFormRequest $request, $id){
    	$facultad=Facultad::findOrFail($id);
    	$facultad->nombre=$request->get('nombre');
    	$facultad->update();
    	return Redirect::to('mantenimiento/facultades');
    }

    public function destroy($id){
    	$facultad=Facultad::findOrFail($id);
    	$facultad->estado='I';
    	$facultad->update();
    	return Redirect::to('mantenimiento/facultades');
    }
}
