@extends('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-university" style="color: #000;"></i>
<h3 class="box-title"><b>Reserva</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
{!! Form::open(array('url'=>'operacion/reservasespeciales/index','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<h2><b>Reserva especial</b></h2>
<br>
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Usuario') }}</label>
        <div class="col-md-6">
        <select id="id" name="id" class="form-control" style="color: #000;">
            <option value="0" @if(0==$idquery) selected="selected" @endif>N/A</option>
            @foreach ($usuarios as $u)
            <option value="{{$u->id}}" @if($u->id==$idquery) selected="selected" @endif>{{$u->name}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="fechaini" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Fecha inicio') }}</label>
        <div class="col-md-6">  
        <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-calendar" style="color: #000;"></i>
            </div>
            @if($sms=='')  
            <input type="text" class="form-control datepicker" placeholder="Fecha inicio" name="fechaini" value="{{$hoy}}" style="color: #000;">
            @else
            <input type="text" class="form-control datepicker" placeholder="Fecha inicio" name="fechaini" value="{{$fechaini}}" style="color: #000;">
            @endif
        </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="fechafin" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Fecha final') }}</label>
        <div class="col-md-6">  
        <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-calendar" style="color: #000;"></i>
            </div>  
            @if($sms=='')
            <input type="text" class="form-control datepicker" placeholder="Fecha final" name="fechafin" value="{{$hoy}}" style="color: #000;">
            @else
            <input type="text" class="form-control datepicker" placeholder="Fecha final" name="fechafin" value="{{$fechafin}}" style="color: #000;">
            @endif
        </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="horaini" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Hora inicio') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o" style="color: #000;"></i>
            </div>
            <select id="horaini" name="horaini" class="form-control" style="color: #000;">
            @foreach ($horarios as $hor)
            <option value="{{$hor->hora}}" 
            @if($hor->hora==$horaini) selected="selected" @endif>
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
            <div class="input-group-addon">
            <i class="fa fa-clock-o" style="color: #000;"></i>
            </div>
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
        <label class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Área de estudio') }}</label>
        <div class="col-md-6">
        <select id="area" name="area" class="form-control" style="color: #000;">
            @foreach ($areas as $a)
            <option value="{{$a->idarea}}" @if($a->idarea==$areaquery) selected="selected" @endif>{{$a->nombre." (".$a->minimo."-".$a->capacidad.")"}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row"> 
        <label for="cantidad" class="col-md-3 col-form-label text-md-right" style="color: #000;">{{ __('Cantidad de ocupantes') }}</label>
        <div class="col-md-6">
        <input id="cantidad" type="text" oncopy="return false" onpaste="return false" placeholder="Cantidad de ocupantes" class="form-control" name="cantidad" onkeypress="return isNumberKey(event)" maxlength="3" value="{{$cantidad}}" style="color: #000;">
        </div>
    </div>

    @if($sms!='')
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
        </div>
    </div>
    @endif

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <button type="submit" class="my-button"><i class="fa fa-book"><b> Reservar</b></i></button>
        </div>
    </div>
    {{Form::close()}}
</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script type="text/javascript">
var jQuery_1_1_3 = $.noConflict(true);
jQuery_1_1_3("#id").select2({
    language: {
        noResults: function (params) {
            return "No se encontraron resultados";
            }
        }
    });
</script>

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

function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true; 
        }
</script>
@endsection
