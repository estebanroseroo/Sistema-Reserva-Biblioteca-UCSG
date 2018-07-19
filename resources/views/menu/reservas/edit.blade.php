@extends ('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2>Editar usuario</h2>
		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::model($usuario,['method'=>'PATCH','route'=>['usuarios.update', $usuario->id]])!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

         <div class="col-md-6">
         <input id="name" type="text" placeholder="Nombre" 
         onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $usuario->name }}">
        </div>
     </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo') }}</label>

        <div class="col-md-6">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $usuario->email }}">
        </div>
    </div>

      <div class="form-group row">
        <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

        <div class="col-md-6">
        <input id="telefono" type="number" placeholder="Teléfono" class="form-control" name="telefono" value="{{ $usuario->telefono }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">Facultad</label>
        <div class="col-md-6">
        <select id="idfacultadedit" name="idfacultadedit" class="form-control">
                @foreach ($facultades as $fac)
                @if($fac->idfacultad==$usuario->idfacultad)
                <option value="{{$fac->idfacultad}}" selected>{{$fac->nombre}}</option>
                @else
                <option value="{{$fac->idfacultad}}">{{$fac->nombre}}</option>
                @endif
                @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">Carrera</label>
        <div class="col-md-6">
        <select id="idcarreraedit" name="idcarreraedit" class="form-control">
                @foreach ($carreras as $car)
                @if($car->idcarrera==$usuario->idcarrera)
                <option value="{{$car->idcarrera}}" selected>{{$car->nombre}}</option>
                @else
                <option value="{{$car->idcarrera}}">{{$car->nombre}}</option>
                @endif
                @endforeach
        </select>
        </div>
        <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

        <div class="col-md-6">
        <input id="password" type="password" placeholder="Contraseña" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>

        <div class="col-md-6">
        <input id="password-confirm" type="password" placeholder="Confirmar contraseña" class="form-control" name="password_confirmation">
        </div>
    </div>
	<div class="form-group">
		<button class="my-button" type="submit"><i class="fa fa-save"><b> Guardar</b></i></button>
	</div>

	{!!Form::close()!!}
	</div>
</div>
@endsection