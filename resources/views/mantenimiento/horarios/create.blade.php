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
		<h2>Nuevo horario</h2>
		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::open(array('url'=>'mantenimiento/horarios', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group">
        <label for="horainicio">Hora Inicio</label>
         <input id="horainicio" type="text" placeholder="Hora Inicio" class="form-control{{ $errors->has('horainicio') ? ' is-invalid' : '' }}" name="horainicio">
     </div>

    <div class="form-group">
        <label for="horafinal">Hora Final</label>
        <input id="horafinal" type="text" placeholder="Hora Final" class="form-control{{ $errors->has('horafinal') ? ' is-invalid' : '' }}" name="horafinal">
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

