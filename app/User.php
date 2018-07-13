<?php

namespace sistemaReserva;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table='users';
    protected $primaryKey='id';
    protected $fillable = [
        'name','email','password','estado','telefono','idfacultad','idcarrera',
    ];

    protected $hidden = [
        'password','remember_token',
    ];
}
