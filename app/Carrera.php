<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table='carrera';
    protected $primaryKey='idcarrera';
    public $timestamps=false;
    protected $fillable=[
	'idfacultad','nombre','estado'
    ];
    protected $guarded=[
    	
    ];
}
