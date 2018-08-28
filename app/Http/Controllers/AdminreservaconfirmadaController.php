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
      $mdisponibilidad=Area::where('estado','A')->where('disponibilidad','=','No Disponible')->get();
            $hoy = Carbon::now()->format('Y-m-d');
            $hora = Carbon::now()->format('H:i:s');
            foreach ($mdisponibilidad as $m) {
            $sff=explode(" ",$m->fechafin);
                if($hoy==$sff[0] && $hora>=$sff[1]){
                $m->disponibilidad='Disponible';
                $m->fechainicio=$hola=NULL;
                $m->fechafin=$hola=NULL;
                $m->update();
                }
            }
            
      $hoy = Carbon::now()->format('d/m/Y');
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

      $monitorear=Reserva::where('estado','A')->get();
      foreach ($monitorear as $m) {
          $vhoy=explode("/",$hoy);
          $vquery=explode("/",$m->fecha);
          $vcrea=explode("/",$m->fechacrea);
          $dhoy=$vhoy[0];
          $mhoy=$vhoy[1];
          $ahoy=$vhoy[2];
          $dquery=$vquery[0];
          $mquery=$vquery[1];
          $aquery=$vquery[2];
          $dcrea=$vcrea[0];
          $mcrea=$vcrea[1];
          $acrea=$vcrea[2];
          if(($ahoy>$aquery) || ($ahoy==$aquery && $mhoy>$mquery) || ($ahoy==$aquery && $mhoy==$mquery && $dhoy>$dquery) || ($ahoy==$aquery && $mhoy==$mquery && $dhoy==$dquery && $m->horacrea<$m->tiempoespera && $hora>=$m->tiempoespera && $m->estado=='A')){
            //($ahoy>=$acrea && $mhoy>=$mcrea && $dhoy>$dcrea && $hora>=$m->tiempoespera && is_null($m->horallegada)==1)
            $m->estado='I';
            $m->update();
            $area=Area::findOrFail($m->idarea);
            $usu = User::where('id',$m->id)->where('estado','A')->first();
            $reservas=Reserva::findOrFail($m->idreserva);
            Mail::send('email.mensajeresexpiro',['usu' => $usu,'reservas' => $reservas,'area'=>$area],
                    function ($m) use ($usu) {
                        $m->to($usu->email, $usu->name)
                          ->subject('Código QR expiró')
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
        ->orderBy('r.fechainicio','asc')
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
