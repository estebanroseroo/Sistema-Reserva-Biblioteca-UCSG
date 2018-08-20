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
		<h2>Nueva área de estudio</h2>

	{!!Form::open(array('url'=>'mantenimiento/areas', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
	<div class="form-group">
		<label for="nombre">Nombre</label>
		<input id="nombre" type="text" class="form-control" name="nombre" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" oncopy="return false" onpaste="return false" placeholder="Nombre" value="{{ old('nombre') }}">
	</div>
	<div class="form-group">
		<label for="capacidad">Capacidad Máxima</label>
		<input id="capacidad" type="text" placeholder="Capacidad Máxima" oncopy="return false" onpaste="return false" class="form-control" name="capacidad" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ old('capacidad') }}">
	</div>
	<div class="form-group">
		<label for="minimo">Capacidad Mínima</label>
		<input id="minimo" type="text" placeholder="Capacidad Mínima" oncopy="return false" onpaste="return false" class="form-control" name="minimo" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ old('minimo') }}">
	</div>

	<div class="form-group row">
        <div class="col-md-8">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;"></label>
        </div>
    </div>

	<div class="form-group">
		<button class="my-button" type="submit" onclick="return validateForm();"><i class="fa fa-save"><b> Guardar</b></i></button>
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