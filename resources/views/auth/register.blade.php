@extends('layouts.app')
@section('content')

<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card my-center-register">
<div class="card-header">
<div class="col-md-6 offset-md-3">
    <img src="{{asset('img/logologin.png')}}" class="my-logo-login"/>
</div>
</div>

<div class="card-body">
<form method="POST" action="" onsubmit="return validateForm()" >
{{ csrf_field() }}
    <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>
    <div class="col-md-6">
    <input id="name" type="text" class="form-control" name="name" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" onkeypress="return lettersOnly(event)" placeholder="Nombre" value="{{ old('name') }}">
    </div>
    </div>

    <div class="form-group row">
    <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellido') }}</label>
    <div class="col-md-6">
    <input id="apellido" type="text" class="form-control" name="apellido" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" onkeypress="return lettersOnly(event)" placeholder="Apellido" value="{{ old('apellido') }}">
    </div>
    </div>

    <div class="form-group row">
    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo') }}</label>
    <div class="col-md-6">
    <input id="email" type="email" class="form-control" name="email" placeholder="Correo" value="{{ old('name').".".old('apellido')."@cu.ucsg.edu.ec" }}" disabled>
    </div>
    </div>

    <div class="form-group row">
    <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>
    <div class="col-md-6">
    <input id="telefono" type="text" placeholder="Teléfono" class="form-control" name="telefono" onkeypress="return isNumberKey(event)" maxlength="10" value="{{ old('telefono') }}">
    </div>
    </div>

    <div class="form-group row">
    <label class="col-md-4 col-form-label text-md-right">Rol</label>
    <div class="col-md-6">
    <select id="idtipousuario" name="idtipousuario" class="form-control">
        @foreach ($roles as $rol=>$value)
        <option value="{{$rol}}">{{$value}}</option>
        @endforeach
    </select>
    </div>
    </div>

    <div class="form-group row">
    <label for="idfacultad" class="col-md-4 col-form-label text-md-right">{{ __('Facultad') }}</label>
    <div class="col-md-6">
    <select id="idfacultad" name="idfacultad" class="form-control">
        @foreach ($facultades as $fac => $value)
        <option value="{{ $fac }}"> {{ $value }}</option>   
        @endforeach
    </select>
    </div>
    </div>

    <div class="form-group row">
    <label for="idcarrera" class="col-md-4 col-form-label text-md-right">{{ __('Carrera') }}</label>
    <div class="col-md-6">
    <select id="idcarrera" name="idcarrera" class="form-control" disabled>
    </select>
    </div>
        <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
    </div>

    <div class="form-group row">
    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>
    <div class="col-md-6">
    <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña">
    </div>
    </div>

    <div class="form-group row">
    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>
    <div class="col-md-6">
    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar Contraseña">
    </div>
    </div>

    <input id="estado" name="estado" type="hidden" value="A">
    <div class="col-md-6 offset-md-4">
    <label id="mensaje" style="font-size: 12px; color:red; font-weight:bold;"></label>
    </div>

    <div class="form-group row mb-0">
    <div class="col-md-6 offset-md-4">
    <button class="my-button" type="submit"><b>Registrarse</b></button>
    <a class="btn btn-link" href="{{ route('login') }}" style="color:#3d4244">{{ __('Inicio de sesión') }}</a>
    </div>
    </div>
</form>
</div>
</div>
</div>
</div>
</div>

<script>
    function lettersOnly(){
        var charCode = event.keyCode;
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8)
            return true;
        else
            return false;
        }
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true; 
        }
    function validateForm() {
    if(document.getElementById('name').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo nombre es obligatorio';
        return false;
        }
    if(document.getElementById('apellido').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo apellido es obligatorio';
        return false;
        }
    if(document.getElementById('password').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo contraseña es obligatorio';
        return false;
        }
    if(document.getElementById('password').value.length<6){
        document.getElementById('mensaje').innerHTML = 'El campo contraseña debe tener mínimo 6 caracteres';
        return false;
        }
    if(document.getElementById('password').value!=document.getElementById('password-confirm').value){
        document.getElementById('mensaje').innerHTML = 'El campo contraseña no es igual a confirmar contraseña';
        return false;
        }
    document.getElementById('email').value = document.getElementById('name').value+"."+document.getElementById('apellido').value+"@cu.ucsg.edu.ec"; 
    document.getElementById('email').disabled = false;
    document.getElementById('idcarrera').disabled = false;
    }
</script>
@endsection
