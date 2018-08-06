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
<h2>Nueva reserva</h2>

 <div class="form-group row">
    <div class="col-md-6"> 
    </div>
</div>

    {!! Form::open(array('url'=>'menu/reservas/create','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
    <div class="form-group row">
        <label for="fecha" class="col-md-2 col-form-label text-md-right">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
            </div>  
            <input type="text" class="form-control datepicker" placeholder="Fecha" name="fecha" value="{{$fecha}}">
        </div>
        </div>
        <span class="input-group-btn">
            <button type="submit" class="my-button"><i class="fa fa-search"> <b>Buscar</b></i></button>
        </span>
    </div>

    <div class="form-group row">
        <label for="horarios" class="col-md-2 col-form-label text-md-right">{{ __('Horario') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o"></i>
            </div>

            <select id="horarios" name="horarios" class="form-control">
            @foreach ($horarios as $hor)
            <option value="{{$hor->horainicio}}-{{$hor->horafinal}}" 
            @if($hor->horainicio."-".$hor->horafinal==$inicio) selected="selected" @endif>
            {{$horainicio=substr($hor->horainicio,0,5)}}-{{$horafinal=substr($hor->horafinal,0,5)}}
            </option>
             @endforeach
            </select>
            </div> 
        </div>
    </div>
    {{Form::close()}}

</div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                @if($inicio!="" && $fecha!="")
                <thead>
                    <th>Área</th>
                    <th>Capacidad</th>
                    <th>Disponibilidad</th>
                    <th>Hora Inicio</th>
                    <th>Hora Final</th>
                </thead>
                @foreach($diferentes as $d)
        {!! Form::open(array('url'=>'menu/reservas/edit','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
                <tr>
                    <td>{{$d->nombre}}<input name="enombre" value="{{$d->nombre}}" type="hidden"></td>
                    <td>{{$d->capacidad}}<input name="ecapacidad" value="{{$d->capacidad}}" type="hidden"></td>

                    @if($d->fecha==$fecha)
                    <td>Ocupado</td>
                    <td>{{$d->horainicio}}</td>
                    <td>{{$d->horafinal}}</td>
                    @else
                    <td>
                        Disponible<input name="efecha" value="{{$fecha}}" type="hidden">
                        <button type="submit" class="my-button"><b>Reservar</b></button>
                    </td>
                    <td>-<input name="ehorainicio" value="{{$inicio}}" type="hidden"></td>
                    <td>-</td>
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
