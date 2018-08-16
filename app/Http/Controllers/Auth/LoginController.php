<?php

namespace sistemaReserva\Http\Controllers\Auth;

use sistemaReserva\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use sistemaReserva\User;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user){
        if($user->idtipousuario < 3) {
            return redirect('/operacion/adminreservas');
        }
            return redirect('/menu/perfiles');
    }

    //protected $redirectTo = '/mantenimiento/areas';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
