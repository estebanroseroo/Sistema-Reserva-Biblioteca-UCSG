@extends ('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2>Editar carrera</h2>
		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::model($carrera,['method'=>'PATCH','route'=>['carreras.update', $carrera->idcarrera]])!!}
	{{Form::token()}}
	<div class="form-group">
		<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
		<label for="nombre">Nombre</label>
		<input type="Text" name="nombre" required value="{{$carrera->nombre}}" class="form-control" placeholder="Nombre" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());">
		</div>
		</div>

		<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
		<label>Facultad</label>
		<select name="idfacultad" class="form-control">
				@foreach ($facultades as $fac)
				@if($fac->idfacultad==$carrera->idfacultad)
				<option value="{{$fac->idfacultad}}" selected>{{$fac->nombre}}</option>
				@else
				<option value="{{$fac->idfacultad}}">{{$fac->nombre}}</option>
				@endif
				@endforeach
		</select>
		</div>
		</div>

		<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
		<div class="form-group">
		<button class="my-button" type="submit"><i class="fa fa-save"><b> Guardar</b></i></button>
		<button class="my-button" type="reset"><i class="fa fa-eraser"><b> Limpiar</b></i></button>
		</div>
		</div>

	{!!Form::close()!!}
	</div>
</div>
@endsection