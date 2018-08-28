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
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h2><b>Editar área de estudio</b></h2>
        <br>
	{!!Form::model($area,['method'=>'PATCH','route'=>['areas.update', $area->idarea]])!!}
	{{Form::token()}}
	<div class="form-group row">
    <label for="nombre" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Nombre') }}</label>
    <div class="col-md-6">
		<input id="nombre" type="text" class="form-control" name="nombre" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" onkeypress="return alpha(event);" oncopy="return false" onpaste="return false" placeholder="Nombre" value="{{ $area->nombre }}" maxlength="40" style="color: #000;">
    </div>
	</div>

	<div class="form-group row">
    <label for="capacidad" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Capacidad Máxima') }}</label>
    <div class="col-md-6">
		<input id="capacidad" type="text" placeholder="Capacidad Máxima" oncopy="return false" onpaste="return false" class="form-control" name="capacidad" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ $area->capacidad }}" style="color: #000;">
    </div>
	</div>

	<div class="form-group row">
    <label for="minimo" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Capacidad Mínima') }}</label>
    <div class="col-md-6"> 
		<input id="minimo" type="text" placeholder="Capacidad Mínima" oncopy="return false" onpaste="return false" class="form-control" name="minimo" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ $area->minimo }}" style="color: #000;">
	</div>
    </div>

	<div class="form-group row">
    <label for="disponibilidad" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Disponibilidad') }}</label>
    <div class="col-md-6"> 
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
	</div>

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right" style="color: #000;"></label>
        <div class="col-md-6">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
        </div>
    </div>

    @if($sms!='' || $area->disponibilidad=='No Disponible')
    <div class="form-group row">
    <label for="fechaini" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Fecha inicio') }}</label>
    <div class="col-md-6"> 
    <div class="input-group">
    <div class="input-group-addon"><i class="fa fa-calendar" style="color: #000;"></i></div>
    @if($area->disponibilidad=='No Disponible') 
    <input type="text" class="form-control datepicker" placeholder="Fecha inicio" name="fechaini" value="{{$fechai}}" style="color: #000;">
    @else
    <input type="text" class="form-control datepicker" placeholder="Fecha inicio" name="fechaini" value="{{$hoy}}" style="color: #000;">
    @endif
    </div>
    </div>
    </div>

    <div class="form-group row">
    <label for="fechafin" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Fecha final') }}</label>
    <div class="col-md-6"> 
    <div class="input-group">
    <div class="input-group-addon"><i class="fa fa-calendar" style="color: #000;"></i></div> 
    @if($area->disponibilidad=='No Disponible')
    <input type="text" class="form-control datepicker" placeholder="Fecha final" name="fechafin" value="{{$fechaf}}" style="color: #000;">
    @else
    <input type="text" class="form-control datepicker" placeholder="Fecha final" name="fechafin" value="{{$hoy}}" style="color: #000;">
    @endif
    </div>
    </div>
    </div>

    <div class="form-group row">
    <label for="horaini" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Hora inicio') }}</label>
    <div class="col-md-6">  
    <div class="input-group">
    <div class="input-group-addon"><i class="fa fa-clock-o" style="color: #000;"></i></div>
    <select id="horaini" name="horaini" class="form-control" style="color: #000;">
    @foreach ($horarios as $hor)
    <option value="{{$hor->hora}}" @if($hor->hora==$hi) selected="selected" @endif>
    {{$horainicio=substr($hor->hora,0,5)}}
    </option>
    @endforeach
    </select>
    </div> 
    </div>
    </div>

    <div class="form-group row">
    <label for="horafin" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Hora final') }}</label>
    <div class="col-md-6">  
    <div class="input-group">
    <div class="input-group-addon"><i class="fa fa-clock-o" style="color: #000;"></i></div>
    <select id="horafin" name="horafin" class="form-control" style="color: #000;">
    @foreach ($horariosf as $hor)
    <option value="{{$hor->hora}}" @if($hor->hora==$hf) selected="selected" @endif>
    {{$horafinal=substr($hor->hora,0,5)}}
    </option>
    @endforeach
    </select>
    </div> 
    </div>
    </div>
    @endif

	<div class="form-group row">
    <label class="col-md-3 col-form-label text-md-right" style="color: #000;"></label>
    <div class="col-md-6">
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
var jQuery_1_3_2 = $.noConflict(true);
jQuery_1_3_2('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
        orientation: "bottom",
        todayHighlight: true,
        daysOfWeekDisabled: [0,6],
});
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