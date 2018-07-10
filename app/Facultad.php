<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $table='facultad';
    protected $primaryKey='idfacultad';
    public $timestamps=false;
    protected $fillable=[
	'nombre','estado'
    ];
    protected $guarded=[
    	
    ];
}
