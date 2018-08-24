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
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<h2><b>Nuevo horario</b></h2>
        <br>
	{!!Form::open(array('url'=>'mantenimiento/horarios', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group">
        <label for="hora" style="color: #000;">Hora</label>
         <input id="hora" type="time" placeholder="Hora" class="form-control" name="hora" value="{{$qhora}}" style="color: #000;">
    </div>

    @if($sms!='')
     <div class="form-group row">
        <div class="col-md-8">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
        </div>
    </div>
    @endif

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

