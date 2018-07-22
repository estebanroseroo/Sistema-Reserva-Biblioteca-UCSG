@extends ('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-th"></i>
<h3 class="box-title"><b>Reserva</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2>Nueva reserva</h2>
		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::open(array('url'=>'operacion/adminreservas', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
    <div class="form-group row">
        <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
            </div>  
            <input type="text" class="form-control datepicker" placeholder="Fecha" name="fecha">
        </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="horainicio" class="col-md-4 col-form-label text-md-right">{{ __('Hora Inicio') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
            </div>  
            <select name="horainicio" class="form-control">
            <option value="80000">08:00 AM</option>
            <option value="90000">09:00 AM</option>
            <option value="100000">10:00 AM</option>
            <option value="110000">11:00 AM</option>
            <option value="120000">12:00 PM</option>
            <option value="130000">13:00 PM</option>
            <option value="140000">14:00 PM</option>
            <option value="150000">15:00 PM</option>
            <option value="160000">16:00 PM</option>
            <option value="170000">17:00 PM</option>
            <option value="180000">18:00 PM</option>
            <option value="190000">19:00 PM</option>
            <option value="200000">20:00 PM</option>
            </select>
            </div> 
        </div>
    </div>

    <div class="form-group row">
        <label for="horafinal" class="col-md-4 col-form-label text-md-right">{{ __('Hora Final') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
            </div>  
            <select name="horafinal" class="form-control">
            <option value="90000">09:00 AM</option>
            <option value="100000">10:00 AM</option>
            <option value="110000">11:00 AM</option>
            <option value="120000">12:00 PM</option>
            <option value="130000">13:00 PM</option>
            <option value="140000">14:00 PM</option>
            <option value="150000">15:00 PM</option>
            <option value="160000">16:00 PM</option>
            <option value="170000">17:00 PM</option>
            <option value="180000">18:00 PM</option>
            <option value="190000">19:00 PM</option>
            <option value="200000">20:00 PM</option>
            </select>
            </div> 
        </div>
    </div>

    <div class="form-group row">
        <label for="tiempoespera" class="col-md-4 col-form-label text-md-right">{{ __('Hora Espera') }}</label>
        <div class="col-md-6">  
        <input type="text" id="tiempoespera" readonly="readonly" class="form-control" placeholder="Hora Espera" name="tiempoespera">
        </div>
    </div>

    <div class="form-group row">
        <label for="tiempocancelar" class="col-md-4 col-form-label text-md-right">{{ __('Hora Cancelación') }}</label>
        <div class="col-md-6">  
        <input type="text" id="tiempocancelar" readonly="readonly" class="form-control" placeholder="Hora Cancelación" name="tiempocancelar">
        </div>
    </div>

    <div class="form-group row">
        <label for="id" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>
        <div class="col-md-6">
        <select id="id" name="id" class="form-control">
            @foreach ($usuarios as $usu)
            <option value="{{$usu->id}}">{{$usu->name}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="idarea" class="col-md-4 col-form-label text-md-right">{{ __('Área') }}</label>
        <div class="col-md-6">
        <select id="idarea" name="idarea" class="form-control">
            @foreach ($areas as $are)
            <option value="{{$are->idarea}}">{{$are->nombre}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row"> 
        <label for="cantidad" class="col-md-4 col-form-label text-md-right">{{ __('Ocupantes') }}</label>
         <div class="col-md-6">
         <input id="cantidad" type="number" min="0" max="100" placeholder="Ocupantes"  class="form-control" name="cantidad">
        </div>
    </div>

	<div class="form-group">
		<button class="my-button" type="submit"><i class="fa fa-save"><b> Guardar</b></i></button>
		<button class="my-button" type="reset" id="bt_limpiar"><i class="fa fa-eraser"><b> Limpiar</b></i></button>
	</div>

	{!!Form::close()!!}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->

<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
        orientation: "bottom",
        todayHighlight: true
    });
</script>
@endsection

