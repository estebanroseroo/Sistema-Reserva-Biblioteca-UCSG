<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Facultad;
use sistemaReserva\Carrera;
use sistemaReserva\User;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\FacultadFormRequest;
use DB;
use Auth;

class FacultadController extends Controller
{
	public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
            $facultades=DB::table('facultad as f')
            ->leftjoin('users as u','u.idfacultad','=','f.idfacultad')
            ->select('f.nombre', 'f.idfacultad', DB::raw('(CASE 
            WHEN f.idfacultad=u.idfacultad
            AND u.estado="A"
            THEN "lleno"
            ELSE "vacio"
            END) AS temporal'))
            ->where('nombre','LIKE','%'.$query.'%')
            ->where('f.estado','=','A')
            ->orderBy('f.nombre','asc')
            ->paginate(9);

            if(Auth::user()->idtipousuario<2){
            return view('mantenimiento.facultades.index',["facultades"=>$facultades, "searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }   
    	}
    }

    public function create(){
         if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.facultades.create");
            }
            else{
            return Redirect::to('/logout');
            }
    }

    public function store(FacultadFormRequest $request){
    	$facultad=new Facultad;
    	$facultad->nombre=$request->get('nombre');
    	$facultad->estado='A';
    	$facultad->save();
    	return Redirect::to('mantenimiento/facultades');
    }

    public function edit($id){
        if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.facultades.edit",["facultad"=>Facultad::findOrFail($id)]);
            }
            else{
            return Redirect::to('/logout');
            }
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

        $carrera=Carrera::where('estado','A')->where('idfacultad',$facultad->idfacultad)->get();
        foreach($carrera as $c){
            $c->estado='I';
            $c->update();
        }
    	return Redirect::to('mantenimiento/facultades');
    }
}
