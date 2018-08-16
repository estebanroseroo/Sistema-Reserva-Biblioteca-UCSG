<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;
use sistemaReserva\Area;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\AreaFormRequest;
use DB;
use Auth;

class AreaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
    	if($request){
    		$query=trim($request->get('searchText'));
    		$areas=DB::table('area')
            ->where('nombre','LIKE','%'.$query.'%')
			->where('estado','=','A')
			->orderBy('idarea','asc')
			->paginate(9);

        if(Auth::user()->idtipousuario<2){
        return view('mantenimiento.areas.index',["areas"=>$areas, "searchText"=>$query]);
        }
        else{
        return Redirect::to('/logout');
        }	
    	}
    }

    public function create(){
        if(Auth::user()->idtipousuario<2){
        return view("mantenimiento.areas.create");
        }
        else{
        return Redirect::to('/logout');
        }
    }

    public function store(AreaFormRequest $request){
    	$area=new Area;
    	$area->nombre=$request->get('nombre');
    	$area->disponibilidad='Disponible';
        $area->capacidad=$request->get('capacidad');
    	$area->estado='A';
    	$area->save();
    	return Redirect::to('mantenimiento/areas');
    }

    public function edit($id){
        if(Auth::user()->idtipousuario<2){
        return view("mantenimiento.areas.edit",["area"=>Area::findOrFail($id)]);
        }
        else{
        return Redirect::to('/logout');
        }
    }

    public function update(AreaFormRequest $request, $id){
    	$area=Area::findOrFail($id);
    	$area->nombre=$request->get('nombre');
    	$area->disponibilidad='Disponible';
        $area->capacidad=$request->get('capacidad');
    	$area->update();
    	return Redirect::to('mantenimiento/areas');
    }

    public function destroy($id){
    	$area=Area::findOrFail($id);
    	$area->estado='I';
    	$area->update();
    	return Redirect::to('mantenimiento/areas');
    }
}
