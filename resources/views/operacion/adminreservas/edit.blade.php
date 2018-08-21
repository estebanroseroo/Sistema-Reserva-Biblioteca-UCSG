@extends(Auth::user()->idtipousuario==1 ? 'layouts.admin' : 'layouts.gestor')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-university"></i>
<h3 class="box-title"><b>Reserva</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Nueva reserva</h2>

 <div class="form-group row">
    <div class="col-md-6"> 
    </div>
</div>
		
	{!!Form::open(array('url'=>'operacion/adminreservas', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
    <div class="form-group row">
        <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>
        <div class="col-md-6">  
        <input type="text" id="id" readonly="readonly" class="form-control" name="id" value="{{$usuarios->name}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <input type="text" id="fecha" readonly="readonly" class="form-control" name="fecha" value="{{$efecha}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="horario" class="col-md-4 col-form-label text-md-right">{{ __('Hora inicio') }}</label>
        <div class="col-md-6">  
        <input type="text" id="horainicio" readonly="readonly" class="form-control" name="horainicio" value="{{$ehorainicio}}">
        <input type="hidden" name="horaid" value="{{$ehoraid}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="horario" class="col-md-4 col-form-label text-md-right">{{ __('Hora final') }}</label>
        <div class="col-md-6">  
        <input type="text" id="horafinal" readonly="readonly" class="form-control" name="horafinal" value="{{$ehorafinal}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="area" class="col-md-4 col-form-label text-md-right">{{ __('Área') }}</label>
        <div class="col-md-6">  
        <input type="text" id="area" readonly="readonly" class="form-control" name="area" value="{{$enombre}}">
        <input type="hidden" name="idarea" value="{{$areas->idarea}}">
        </div>
    </div>

     <div class="form-group row">
        <label for="capacidad" class="col-md-4 col-form-label text-md-right">{{ __('Capacidad máxima') }}</label>
        <div class="col-md-6">  
        <input type="text" id="capacidad" readonly="readonly" class="form-control" name="capacidad" value="{{$ecapacidad}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="minimo" class="col-md-4 col-form-label text-md-right">{{ __('Capacidad mínima') }}</label>
        <div class="col-md-6">  
        <input type="text" id="minimo" readonly="readonly" class="form-control" name="minimo" value="{{$areas->minimo}}">
        </div>
    </div>

    <div class="form-group row"> 
        <label for="cantidad" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de ocupantes') }}</label>
        <div class="col-md-6">
        <input id="cantidad" type="text" oncopy="return false" onpaste="return false" placeholder="Cantidad de ocupantes" class="form-control" name="cantidad" onkeypress="return isNumberKey(event)" maxlength="3" value="{{ old('cantidad') }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right"></label>
        <div class="col-md-6">
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
<script type="text/javascript">
function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true; 
        }
function validateForm() {
    if(document.getElementById('cantidad').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo cantidad es obligatorio';
        return false;
    }
    else{
        var can=Number(document.getElementById('cantidad').value);
        var cap=Number(document.getElementById('capacidad').value);
        var min=Number(document.getElementById('minimo').value);
        if(can<min){
        document.getElementById('mensaje').innerHTML = 'La cantidad debe exceder la capacidad mínima';
        return false;
        }
        else{
        if(can>cap){
        document.getElementById('mensaje').innerHTML = 'La cantidad no puede exceder la capacidad máxima';
        return false;  
        } 
        }
    }
}
</script>
@endsection

