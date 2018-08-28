@extends('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-bar-chart" style="color: #000;"></i>
<h3 class="box-title"><b>Reporte</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
{!! Form::open(array('url'=>'reporte/chartreserva','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<h2><b>Número de reservas</b></h2>
<br>

<div class="form-group row">
<label for="fechaini" class="col-md-1 col-form-label text-md-right" style="color: #000;">{{ __('Fecha inicio') }}</label>
<div class="col-md-4"> 
<div class="input-group">
<div class="input-group-addon"><i class="fa fa-calendar" style="color: #000;"></i></div> 
@if($fechaini=='')
<input type="text" class="form-control datepicker" placeholder="Fecha inicio" name="fechaini" value="{{$hoy}}" style="color: #000;">
@else
<input type="text" class="form-control datepicker" placeholder="Fecha inicio" name="fechaini" value="{{$fechaini}}" style="color: #000;">
@endif
</div>
</div>

<label for="fechafin" class="col-md-1 col-form-label text-md-right" style="color: #000;">{{ __('Fecha final') }}</label>
<div class="col-md-4"> 
<div class="input-group">
<div class="input-group-addon"><i class="fa fa-calendar" style="color: #000;"></i></div> 
@if($fechafin=='') 
<input type="text" class="form-control datepicker" placeholder="Fecha fin" name="fechafin" value="{{$hoy}}" style="color: #000;">
@else
<input type="text" class="form-control datepicker" placeholder="Fecha fin" name="fechafin" value="{{$fechafin}}" style="color: #000;">
@endif
</div>
</div>
</div>

<div class="form-group row">
<label for="horaini" class="col-md-1 col-form-label text-md-right" style="color: #000;">{{ __('Hora inicio') }}</label>
<div class="col-md-4">  
<div class="input-group">
<div class="input-group-addon"><i class="fa fa-clock-o" style="color: #000;"></i></div>
<select id="horaini" name="horaini" class="form-control" style="color: #000;">
@foreach ($horarios as $hor)
<option value="{{$hor->hora}}" @if($hor->hora==$horaini) selected="selected" @endif>
{{$horainicio=substr($hor->hora,0,5)}}
</option>
@endforeach
</select>
</div> 
</div>

<label for="horafin" class="col-md-1 col-form-label text-md-right" style="color: #000;">{{ __('Hora final') }}</label>
<div class="col-md-4">  
<div class="input-group">
<div class="input-group-addon"><i class="fa fa-clock-o" style="color: #000;"></i></div>
<select id="horafin" name="horafin" class="form-control" style="color: #000;">
@foreach ($horariosf as $hor)
<option value="{{$hor->hora}}" 
@if($hor->hora==$horafin) selected="selected" @endif>
{{$horafinal=substr($hor->hora,0,5)}}
</option>
@endforeach
</select>
</div> 
</div>
</div>

<div class="form-group row">
<label class="col-md-1 col-form-label text-md-right" style="color: #000;">Facultad</label>
<div class="col-md-4">
<select id="facuchart" name="facuchart" class="form-control" style="color: #000;">
<option value="0" @if(0==$facu) selected="selected" @endif>N/A</option>
<option value="999" @if(999==$facu) selected="selected" @endif>Todas</option>
@foreach ($facultades as $f)
<option value="{{$f->idfacultad}}" @if($f->idfacultad==$facu) selected="selected" @endif>{{$f->nombre}}</option>
@endforeach
</select>
</div>

<label class="col-md-1 col-form-label text-md-right" style="color: #000;">Carrera</label>
<span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
<div class="col-md-4">
<select id="carrechart" name="carrechart" class="form-control" style="color: #000;">
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-1 col-form-label text-md-right" style="color: #000;">Área de estudio</label>
<div class="col-md-4">
<select id="areachart" name="areachart" class="form-control" style="color: #000;">
<option value="999" @if(999==$facu) selected="selected" @endif>Todas</option>
@foreach ($areas as $a)
<option value="{{$a->idarea}}" @if($a->idarea==$area) selected="selected" @endif>{{$a->nombre}}</option>
@endforeach
</select>
</div>
</div>

@if($sms!='')
<div class="form-group row">
<label class="col-md-1 col-form-label text-md-right"></label>
<div class="col-md-4">
<label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
</div>
</div>
@endif

<div class="form-group row">
<label class="col-md-1 col-form-label text-md-right"></label>
<div class="col-md-4">
<button type="submit" class="my-button" name="boton" value="consultar"><i class="fa fa-eye"><b> Consultar</b></i></button>
<button type="submit" class="my-button" name="boton" value="pdf"><i class="fa fa-file"><b> PDF</b></i></button>
<button type="submit" class="my-button" name="boton" value="excel"><i class="fa fa-file-excel-o"><b> Excel</b></i></button>
</div>
</div>
{{Form::close()}}
</div>
</div>


<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
    @if($sms=='' && $diagrama=='areag')
    {!! $chartag->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areae')
    {!! $chartae->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areagfg')
    {!! $chartagfg->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areaefg')
    {!! $chartaefg->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areagfe')
    {!! $chartagfe->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areaefe')
    {!! $chartaefe->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areagfecg')
    {!! $chartagfecg->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areagfece')
    {!! $chartagfece->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areaefece')
    {!! $chartaefece->render() !!}
    @endif
    @if($sms=='' && $diagrama=='areaefecg')
    {!! $chartaefecg->render() !!}
    @endif
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
</script>
@endsection
