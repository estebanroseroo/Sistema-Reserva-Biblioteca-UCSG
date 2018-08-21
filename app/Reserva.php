<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table='reserva';
    protected $primaryKey='idreserva';
    public $timestamps=false;
    protected $fillable=[
	'fecha','horainicio','horafinal','horallegada','tiempoespera','cantidad','estado','id','idarea','idhora','fechacrea','horacrea'
    ];
    protected $guarded=[
    	
    ];
}
