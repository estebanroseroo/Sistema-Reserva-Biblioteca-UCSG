<?php

namespace sistemaReserva\Http\Controllers\Auth;

use Illuminate\Http\Request;

use sistemaReserva\User;
use Illuminate\Support\Facades\DB;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use sistemaReserva\Facultad;
use sistemaReserva\Carrera;
use Mail;


class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo = '/logout';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'telefono' => 'nullable|min:7|max:10',
            'idfacultad'=>'nullable',
            'idcarrera'=>'nullable',
            'idtipousuario'=>'required',
        ]);
    }

    protected function create(array $data)
    {
        $usuario=User::create([
            'name' => $data['name'].".".$data['apellido'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono' => $data['telefono'],
            'idfacultad' => $data['idfacultad'],
            'idcarrera' => $data['idcarrera'],
            'idtipousuario' => $data['idtipousuario'],
            'estado'=>$data['estado'],
        ]);

        $facultad=Facultad::findOrFail($usuario->idfacultad);
        $carrera=Carrera::findOrFail($usuario->idcarrera);
        Mail::send('email.mensajeusu',['usuario' => $usuario,'facultad'=>$facultad,'carrera'=>$carrera],
        function ($m) use ($usuario) {
          $m->to($usuario->email, $usuario->name)
            ->subject('Registro exitoso')
            ->from('roseroesteban@gmail.com', 'Administrador');
        }
        );

        return $usuario;
    }

    public function showRegistrationForm(){
        $facultades = DB::table('facultad')
        ->where("estado","=","A")
        ->pluck("nombre","idfacultad");
        $roles = DB::table('tipousuario')
        ->where("estado","=","A")
        ->where("idtipousuario",">","2")
        ->pluck("nombre","idtipousuario");
        return view('auth.register',['facultades'=>$facultades,'roles'=>$roles]);
    }

     public function getStates($id) {
        $carreras = DB::table("carrera")
        ->where("idfacultad",$id)
        ->where("estado","=","A")
        ->pluck("nombre","idcarrera");
        return json_encode($carreras);
    }
}
