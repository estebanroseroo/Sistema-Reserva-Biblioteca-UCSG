<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sistemaReserva\Http\Requests\HorarioFormRequest;
use Illuminate\Support\Collection;
use sistemaReserva\Http\Controllers\Controller;
use sistemaReserva\Horario;
use sistemaReserva\Reserva;
use sistemaReserva\User;
use sistemaReserva\Area;
use DB;
use Auth;
use DateTime;
use Mail;
use Carbon\Carbon;

class HorarioController extends Controller
{
   public function __construct(){
   	$this->middleware('auth');
    }

   public function index(Request $request){
   	if($request){
         $hoy = Carbon::now()->format('d/m/Y');
   		$query=trim($request->get('searchText'));
   		$horarios=DB::table('horario as h')
         ->leftjoin('reserva as r','r.idhora','=','h.idhora')
         ->select('h.hora', 'h.idhora', DB::raw('(CASE 
            WHEN r.fecha="'.$hoy.'"
            AND r.estado="A"
            THEN "lleno"
            ELSE "vacio"
            END) AS temporal'))
         ->where('h.hora','LIKE','%'.$query.'%')
         ->where('h.estado','=','A')
         ->orderBy('h.hora','asc')
         ->distinct('hora')
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
      $sms='';
      $qhora='00:00';
      if(Auth::user()->idtipousuario<2){
            return view("mantenimiento.horarios.create",["sms"=>$sms,"qhora"=>$qhora]);
            }
            else{
            return Redirect::to('/logout');
            } 
    }

   public function store(HorarioFormRequest $request){
      $qhora=trim($request->get('hora')).':00';
      $horaexiste= Horario::where('estado','=','A')->where('hora','=',$qhora)->first();
      if($qhora=='00:00:00'){
      $sms='El campo hora es obligatorio';
      return view("mantenimiento.horarios.create",["sms"=>$sms,"qhora"=>$qhora]);
      }
      if($horaexiste){
      $sms='La hora ya existe';
      return view("mantenimiento.horarios.create",["sms"=>$sms,"qhora"=>$qhora]);
      }
      else{
         $horarios=Horario::where('estado','=','A')->orderBy('hora','asc')->get();
         foreach ($horarios as $h) {
         $start = new DateTime($h->hora);
         $end = new DateTime($qhora);
         $d = $start->diff($end); 
         $dif= $d->format("%H:%I");
         $difnum=(int)$dif;
            if($difnum<1){
            $sms='Existe un cruce de horario por menos de una hora';
            return view("mantenimiento.horarios.create",["sms"=>$sms,"qhora"=>$qhora]);
            }
         }
         $horario=new Horario;
         $horario->hora=$request->get('hora');
         $horario->estado='A';
         $horario->save();
         return Redirect::to('mantenimiento/horarios');
      }
   }

   public function destroy($id){
      $horario=Horario::findOrFail($id);
      $busco=(int)$horario->hora;//18:00:00
      $reserva=Reserva::all();
      foreach($reserva as $r){
      $res=DB::table('reserva')
      ->select('idreserva','idarea',DB::raw(
            '("'.(int)$r->horainicio.'") AS temporalinicio,
             ("'.(int)$r->horafinal.'") AS temporalfinal'))
      ->where('idreserva','=',$r->idreserva)
      ->where('estado','=','A')
      ->get();
         if($r->horainicio<=$busco && $r->horafinal>=$busco){
            $reservaeli=Reserva::where('estado','A')->where('idreserva',$r->idreserva)->get();
               foreach($reservaeli as $reli){
               $reli->estado='I';
               $reli->update();
               $usu=User::findOrFail($reli->id);
               $area=Area::findOrFail($reli->idarea);
               Mail::send('email.mensajehorario',['usu' => $usu,'reli' => $reli,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Horario no disponible')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );
               }   
         }
      }
      $horario=Horario::findOrFail($id);
      $horario->estado='I';
      $horario->update();
      return Redirect::to('mantenimiento/horarios');
   } 
}
