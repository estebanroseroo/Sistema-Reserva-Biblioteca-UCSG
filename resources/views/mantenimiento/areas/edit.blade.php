@extends ('layouts.admin')
@section('contenido')
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2>Editar área de estudio</h2>
		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::model($area,['method'=>'PATCH','route'=>['areas.update', $area->idarea]])!!}
	{{Form::token()}}
	<div class="form-group">
		<label for="nombre">Nombre</label>
		<input type="Text" name="nombre" class="form-control" value="{{$area->nombre}}" placeholder="Nombre">
	</div>
	<div class="form-group">
		<label for="disponibilidad">Disponibilidad</label>
		<input type="Text" name="disponibilidad" class="form-control" value="{{$area->disponibilidad}}" placeholder="Disponibilidad">
	</div>
	<div class="form-group">
		<button class="my-button" type="submit"><i class="fa fa-save"><b> Guardar</b></i></button>
		<button class="my-button" type="reset"><i class="fa fa-eraser"><b> Limpiar</b></i></button>
	</div>

	{!!Form::close()!!}
	</div>
</div>
@endsection