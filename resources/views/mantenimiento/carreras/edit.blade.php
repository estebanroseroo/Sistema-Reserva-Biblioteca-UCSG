@extends ('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border" style="color: #000;">
<i class="fa fa-wrench"></i>
<h3 class="box-title"><b>Mantenimiento</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
	
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2><b>Editar carrera</b></h2>
		<br>
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
		<label for="nombre" style="color: #000;">Nombre</label>
		<input id="nombre" type="text" name="nombre" required value="{{$carrera->nombre}}" class="form-control" placeholder="Nombre" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" oncopy="return false" onpaste="return false" maxlength="50" style="color: #000;">
	</div>

	<div class="form-group">
		<label style="color: #000;">Facultad</label>
		<select name="idfacultad" class="form-control" style="color: #000;">
				@foreach ($facultades as $fac)
				@if($fac->idfacultad==$carrera->idfacultad)
				<option value="{{$fac->idfacultad}}" selected>{{$fac->nombre}}</option>
				@else
				<option value="{{$fac->idfacultad}}">{{$fac->nombre}}</option>
				@endif
				@endforeach
		</select>
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
<script>
$(document).on('keypress', '#nombre', function (event) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});
</script>
@endsection