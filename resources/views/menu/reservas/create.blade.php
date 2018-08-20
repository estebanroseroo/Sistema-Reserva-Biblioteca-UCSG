@extends ('layouts.usu')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-graduation-cap"></i>
<h3 class="box-title"><b>Menú</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
{!! Form::open(array('url'=>'menu/reservas/create','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<h2>Nueva reserva   <button type="submit" class="my-button"><i class="fa fa-search"><b> Buscar</b></i></button></h2>
   
    <div class="form-group row">
        <label for="fecha" class="col-md-2 col-form-label text-md-right">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
            </div>  
            <input type="text" class="form-control datepicker" id="fecha" placeholder="Fecha" name="fecha" value="{{$fecha}}">
        </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="horainicio" class="col-md-2 col-form-label text-md-right">{{ __('Hora inicio') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
            </div>
            <select id="horainicio" name="horainicio" class="form-control">
            @foreach ($horarios as $hor)
            <option value="{{$hor->hora}}" 
            @if($hor->hora==$inicio) selected="selected" @endif>
            {{$horainicio=substr($hor->hora,0,5)}}
            </option>
             @endforeach
            </select>
            </div> 
        </div>
    </div>

    <div class="form-group row">
        <label for="horafinal" class="col-md-2 col-form-label text-md-right">{{ __('Hora final') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
            </div>
            <select id="horafinal" name="horafinal" class="form-control">
            @foreach ($horariosf as $hor)
            <option value="{{$hor->hora}}" 
            @if($hor->hora==$final) selected="selected" @endif>
            {{$horafinal=substr($hor->hora,0,5)}}
            </option>
             @endforeach
            </select>
            </div> 
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
        </div>
    </div>
    {{Form::close()}} 

</div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                @if($inicio!=="" && $fecha!=="" && $sms=="")
                <thead>
                    <th>Área</th>
                    <th>Capacidad</th>
                    <th>Disponibilidad</th>
                </thead>
                @foreach($diferentes as $d)
        {!! Form::open(array('url'=>'menu/reservas/edit','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
                <tr>
                    <td>{{$d->nombre}}<input name="enombre" value="{{$d->nombre}}" type="hidden"></td>
                    <td>{{$d->capacidad}}<input name="ecapacidad" value="{{$d->capacidad}}" type="hidden"></td>

                    @if($d->fecha==$fecha)
                    <td><label style="font-size: 14px; color:red; font-weight:bold;">Ocupado</label></td>
                    @else
                    <td>
                        <label style="font-size: 14px; color:green; font-weight:bold;">Disponible</label>
                        <input name="efecha" value="{{$fecha}}" type="hidden">
                        <input name="ehoraid" value="{{$horaid->idhora}}" type="hidden">
                        <input name="ehorainicio" value="{{$inicio}}" type="hidden">
                        <input name="ehorafinal" value="{{$final}}" type="hidden">
                        <button type="submit" class="my-button"><b>Reservar</b></button>
                    </td>
                    @endif
                </tr>
                {{Form::close()}}
                @endforeach
                @else
                @endif
            </table>
        </div>
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
        todayHighlight: true,
        setDate: null,
        minDate: null,
        maxDate: null
    }); 
</script>
@endsection
