<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='area';
    protected $primaryKey='idarea';
    public $timestamps=false;
    protected $fillable=[
	'nombre','disponibilidad','estado','capacidad','minimo','fechainicio','fechafin'
    ];
    protected $guarded=[
    	
    ];
}
