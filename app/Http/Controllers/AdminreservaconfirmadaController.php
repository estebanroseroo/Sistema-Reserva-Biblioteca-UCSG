<?php

namespace sistemaReserva\Http\Controllers;

use Illuminate\Http\Request;

use sistemaReserva\Http\Requests;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;
use sistemaReserva\Http\Requests\AdminreservaFormRequest;
use sistemaReserva\Reserva;
use sistemaReserva\Horario;
use sistemaReserva\Area;
use DB;
use Auth;
use Carbon\Carbon;
use sistemaReserva\User;
use Mail;
use DateTime;

class AdminreservaconfirmadaController extends Controller
{
    public function __construct(){
    $this->middleware('auth');
   }

   public function index(Request $request){
    if($request){
      $hora = Carbon::now()->format('H:i:s');
      $query=trim($request->get('searchText'));
      $monitorear=Reserva::where('estado','=','C')->get();

      foreach ($monitorear as $m) {
        if($hora>=$m->horafinal){
            $m->estado='I';
            $m->update();
            $area=Area::findOrFail($m->idarea);
            $usu = User::where('id',$m->id)->where('estado','A')->first();
            $reservas=Reserva::findOrFail($m->idreserva);
            Mail::send('email.mensajeresfinalizo',['usu' => $usu,'reservas' => $reservas,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Reserva finalizada')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );
        }
      }

      $reservas=DB::table('reserva as r')
        ->leftjoin('users as u','u.id','=','r.id')
        ->leftjoin('area as a','a.idarea','=','r.idarea')
        ->select('r.idreserva','u.name as nombreusuario','a.nombre as nombrearea','r.fecha','r.horainicio','r.horafinal','r.horallegada','r.cantidad','r.codigoqr')
        ->where('r.fecha','LIKE','%'.$query.'%')
        ->where('r.estado','=','C')
        ->orwhere('u.name','LIKE','%'.$query.'%')
        ->where('r.estado','=','C')
        ->orwhere('a.nombre','LIKE','%'.$query.'%')
        ->where('r.estado','=','C')
        ->orderBy('r.fecha','asc')
        ->paginate(9);

        if(Auth::user()->idtipousuario<3){
             return view("operacion.reservasconfirmadas.index",["reservas"=>$reservas,"searchText"=>$query]);
            }
            else{
            return Redirect::to('/logout');
            }  
    }
   }

   public function destroy($id){
      $reserva=Reserva::findOrFail($id);
      $reserva->estado='I';
      $reserva->update();

      $area=Area::findOrFail($reserva->idarea);
      $usu = User::where('id',$reserva->id)->where('estado','A')->first();
      Mail::send('email.mensajereserva',['usu' => $usu,'reserva' => $reserva,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Código QR eliminado')
                          ->from('roseroesteban@gmail.com', 'Administrador');
                      }
                    );

      return Redirect::to('operacion/reservasconfirmadas');
   }
}
