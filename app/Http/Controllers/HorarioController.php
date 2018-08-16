<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\HorarioFormRequest;
use sistemaReserva\Horario;
use DB;
use Auth;

class HorarioController extends Controller
{
   public function __construct(){
   	$this->middleware('auth');
    }

   public function index(Request $request){
   	if($request){
   		$query=trim($request->get('searchText'));
   		$horarios=DB::table('horario')
      	->where('horainicio','LIKE','%'.$query.'%')
      	->where('estado','=','A')
      	->orwhere('horafinal','LIKE','%'.$query.'%')
      	->where('estado','=','A')
   		->orderBy('horainicio','asc')
   		->paginate(9);
      if(Auth::user()->idtipousuario<2){
            return view('mantenimiento.horarios.index',["horarios"=>$horarios,"searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }   
   	}
   }

    public function create(){
      if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.horarios.create");
            }
            else{
            return Redirect::to('/logout');
            } 
    }

   public function store(HorarioFormRequest $request){
   	$horario=new Horario;
   	$horario->horainicio=$request->get('horainicio');
   	$horario->horafinal=$request->get('horafinal');
    $horario->estado='A';
   	$horario->save();
   	return Redirect::to('mantenimiento/horarios');
   }

   public function destroy($id){
      $horario=Horario::findOrFail($id);
      $horario->estado='I';
      $horario->update();
      return Redirect::to('mantenimiento/horarios');
   } 
}
