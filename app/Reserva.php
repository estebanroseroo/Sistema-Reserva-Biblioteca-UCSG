<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table='reserva';
    protected $primaryKey='idreserva';
    public $timestamps=false;
    protected $fillable=[
	'fecha','horainicio','horafinal','horallegada','tiempoespera','tiempocancelar','cantidad','estado','id','idarea'
    ];
    protected $guarded=[
    	
    ];
}