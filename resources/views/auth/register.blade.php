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
                    <form method="POST" action="">
                        {{ csrf_field() }}
                        <!--@csrf-->
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" placeholder="Nombre">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Correo">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>
                            <div class="col-md-6">
                            <input id="telefono" type="number" placeholder="Teléfono" class="form-control" name="telefono">
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
                                <option value="">--- Seleccione una facultad ---</option>
                                @foreach ($facultades as $fac => $value)
                                <option value="{{ $fac }}"> {{ $value }}</option>   
                                @endforeach
                            </select>
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="idcarrera" class="col-md-4 col-form-label text-md-right">{{ __('Carrera') }}</label>
                            <div class="col-md-6">
                            <select id="idcarrera" name="idcarrera" class="form-control">
                                <option value="">--- Seleccione una carrera ---</option>
                            </select>
                            </div>
                            <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Contraseña">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar Contraseña">
                            </div>
                        </div>

                        <input id="estado" name="estado" type="hidden" value="A">

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
@endsection
