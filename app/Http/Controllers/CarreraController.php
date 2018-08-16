<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Http\Requests;
use sistemaReserva\Carrera;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sistemaReserva\Http\Requests\CarreraFormRequest;
use DB;
use Auth;

class CarreraController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
    		$carreras=DB::table('carrera as c')
			->join('facultad as f','c.idfacultad','=','f.idfacultad')
			->select('c.idcarrera','c.nombre','f.nombre as facultad')
            ->where('c.nombre','LIKE','%'.$query.'%') 
            ->where('c.estado','=','A')
            ->orwhere('f.nombre','LIKE','%'.$query.'%')
            ->where('c.estado','=','A')
			->orderBy('f.nombre','asc')
			->paginate(9);

            if(Auth::user()->idtipousuario<2){
            return view('mantenimiento.carreras.index',["carreras"=>$carreras, "searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }	
    	}
    }

    public function create(){
    	$facultades=DB::table('facultad')->where('estado','=','A')->get(); 
        if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.carreras.create",["facultades"=>$facultades]);
            }
        else{
            return Redirect::to('/logout');
            }   
    }

    public function store(CarreraFormRequest $request){
    	$carrera=new Carrera;
    	$carrera->idfacultad=$request->get('idfacultad');
    	$carrera->nombre=$request->get('nombre');
    	$carrera->estado='A';
    	$carrera->save();
    	return Redirect::to('mantenimiento/carreras');
    }
    
    public function edit($id){
    	$carrera=Carrera::findOrFail($id);
    	$facultades=DB::table('facultad')->where('estado','=','A')->get();
        if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.carreras.edit",["carrera"=>$carrera,"facultades"=>$facultades]);
            }
        else{
            return Redirect::to('/logout');
            }   
    }

    public function update(CarreraFormRequest $request, $id){
    	$carrera=Carrera::findOrFail($id);
		$carrera->idfacultad=$request->get('idfacultad');
    	$carrera->nombre=$request->get('nombre');
    	$carrera->update();
    	return Redirect::to('mantenimiento/carreras');
    }

    public function destroy($id){
    	$carrera=Carrera::findOrFail($id);
    	$carrera->estado='I';
    	$carrera->update();
    	return Redirect::to('mantenimiento/carreras');
    }
}
