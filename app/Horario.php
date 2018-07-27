<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table='horario';
    protected $primaryKey='idhora';
    public $timestamps=false;
    protected $fillable=[
	'horainicio','horafinal','estado'
    ];
    protected $guarded=[
    	
    ];
}
