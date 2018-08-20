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

	{!!Form::open(array('url'=>'mantenimiento/horarios', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group">
        <label for="hora">Hora</label>
         <input id="hora" type="time" placeholder="Hora" class="form-control" name="hora" value="{{$qhora}}">
    </div>

     <div class="form-group row">
        <div class="col-md-8">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
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
	function validateForm() {
	if(document.getElementById('hora').value==""){
     	document.getElementById('mensaje').innerHTML = 'El campo hora es obligatorio';
        return false;
    }
}
</script>
@endsection

