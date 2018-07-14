<?php

namespace sistemaReserva;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table='tipousuario';
    protected $primaryKey='idtipousuario';
    public $timestamps=false;
    protected $fillable=[
	'nombre','estado'
    ];
    protected $guarded=[
    	
    ];
}
