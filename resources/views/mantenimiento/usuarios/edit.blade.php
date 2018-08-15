@extends ('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-wrench"></i>
<h3 class="box-title"><b>Mantenimiento</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
    
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
         onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $usunombre }}">
        </div>
     </div>

     <div class="form-group row">
        <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellido') }}</label>

         <div class="col-md-6">
         <input id="apellido" type="text" placeholder="Apellido" 
        onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control" name="apellido" value="{{ $usuapellido }}">
        </div>
     </div>

      <div class="form-group row">
        <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

        <div class="col-md-6">
        <input id="telefono" type="number" placeholder="Teléfono" class="form-control" name="telefono" value="{{ $usuario->telefono }}">
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
        <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
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
    </div>
	<div class="form-group">
		<button class="my-button" type="submit"><i class="fa fa-save"><b> Guardar</b></i></button>
	</div>

	{!!Form::close()!!}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection