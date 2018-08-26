@extends ('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-wrench" style="color: #000;"></i>
<h3 class="box-title"><b>Mantenimiento</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2><b>Editar área de estudio</b></h2>
        <br>
	{!!Form::model($area,['method'=>'PATCH','route'=>['areas.update', $area->idarea]])!!}
	{{Form::token()}}
	<div class="form-group">
		<label for="nombre" style="color: #000;">Nombre</label>
		<input id="nombre" type="text" class="form-control" name="nombre" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" onkeypress="return alpha(event);" oncopy="return false" onpaste="return false" placeholder="Nombre" value="{{ $area->nombre }}" maxlength="40" style="color: #000;">
	</div>
	<div class="form-group">
		<label for="capacidad" style="color: #000;">Capacidad Máxima</label>
		<input id="capacidad" type="text" placeholder="Capacidad Máxima" oncopy="return false" onpaste="return false" class="form-control" name="capacidad" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ $area->capacidad }}" style="color: #000;">
	</div>
	<div class="form-group">
		<label for="minimo" style="color: #000;">Capacidad Mínima</label>
		<input id="minimo" type="text" placeholder="Capacidad Mínima" oncopy="return false" onpaste="return false" class="form-control" name="minimo" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ $area->minimo }}" style="color: #000;">
	</div>
	<div class="form-group">
		<label for="disponibilidad" style="color: #000;">Disponibilidad</label>
		<select id="disponibilidad" name="disponibilidad" class="form-control" style="color: #000;">
                @if($area->disponibilidad=='Disponible')
                <option value="Disponible" selected>Disponible</option>
                <option value="No Disponible">No Disponible</option>
                @else
                <option value="Disponible">Disponible</option>
        		<option value="No Disponible" selected>No Disponible</option>
                @endif
    </select>
	</div>
	<div class="form-group row">
        <div class="col-md-8">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;"></label>
        </div>
    </div>
	<div class="form-group row">
        <div class="col-md-8">
		<button class="my-button" type="submit" onclick="return validateForm();"><i class="fa fa-save"><b> Guardar</b></i></button>
          </div>
	</div>

	{!!Form::close()!!}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
<script>
function alpha(e) {
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
}

function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true; 
        }
function validateForm() {
	if(document.getElementById('nombre').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo nombre es obligatorio';
        return false;
    }
    else if(document.getElementById('capacidad').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo capacidad máxima es obligatorio';
        return false;
    }
    else if(document.getElementById('minimo').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo capacidad mínimo es obligatorio';
        return false;
    }
    else{
        var cap=Number(document.getElementById('capacidad').value);
        var min=Number(document.getElementById('minimo').value);
        if(cap<min){
        document.getElementById('mensaje').innerHTML = 'La capacidad mínima no puede exceder la máxima';
        return false;
        } 
    }
}
</script>
@endsection