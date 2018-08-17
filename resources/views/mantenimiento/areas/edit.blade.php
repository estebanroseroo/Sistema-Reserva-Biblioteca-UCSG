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
		<input id="nombre" type="text" class="form-control" name="nombre" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" oncopy="return false" onpaste="return false" placeholder="Nombre" value="{{ $area->nombre }}">
	</div>
	<div class="form-group">
		<label for="capacidad">Capacidad</label>
		<input id="capacidad" type="text" placeholder="Capacidad" oncopy="return false" onpaste="return false" class="form-control" name="capacidad" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ $area->capacidad }}">
	</div>
	<div class="form-group">
		<label for="disponibilidad">Disponibilidad</label>
		<select id="disponibilidad" name="disponibilidad" class="form-control">
                @if($area->disponibilidad=='Disponible')
                <option value="Disponible" selected>Disponible</option>
                <option value="No Disponible">No Disponible</option>
                @else
                <option value="Disponible">Disponible</option>
        		<option value="No Disponible" selected>No Disponible</option>
                @endif
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

function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true; 
        }
</script>
@endsection