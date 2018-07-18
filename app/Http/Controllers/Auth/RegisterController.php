<?php

namespace sistemaReserva\Http\Controllers\Auth;

use Illuminate\Http\Request;

use sistemaReserva\User;
use Illuminate\Support\Facades\DB;
use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;



class RegisterController extends Controller
{

    use RegistersUsers;

<<<<<<< HEAD
    protected $redirectTo = '/logout';
=======
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
>>>>>>> 6f1b6b1aac6c00ef1c47b5b3af997e166c257e80

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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono' => $data['telefono'],
            'idfacultad' => $data['idfacultad'],
            'idcarrera' => $data['idcarrera'],
            'idtipousuario' => $data['idtipousuario'],
            'estado'=>$data['estado'],
        ]);
    }

<<<<<<< HEAD
    public function showRegistrationForm(){
        $facultades = DB::table('facultad')
        ->where("estado","=","A")
        ->pluck("nombre","idfacultad");
        $roles = DB::table('tipousuario')
        ->where("estado","=","A")
        ->where("idtipousuario",">","1")
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
=======
    /*public function showRegistrationForm(){
        return redirect('login'); //deshabilita register
    }*/
>>>>>>> 6f1b6b1aac6c00ef1c47b5b3af997e166c257e80
}
