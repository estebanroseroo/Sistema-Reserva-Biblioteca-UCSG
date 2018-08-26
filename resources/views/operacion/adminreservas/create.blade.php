@extends(Auth::user()->idtipousuario==1 ? 'layouts.admin' : 'layouts.gestor')
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
{!! Form::open(array('url'=>'operacion/adminreservas/create','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<h2><b>Nueva reserva</b></h2>
<br>
     <div class="form-group row">
        <label class="col-md-2 col-form-label text-md-right" style="color: #000;">{{ __('Usuario') }}</label>
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
        <label for="fecha" class="col-md-2 col-form-label text-md-right" style="color: #000;">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-calendar" style="color: #000;"></i>
            </div>  
            <input type="text" class="form-control datepicker" placeholder="Fecha" name="fecha" value="{{$fecha}}" style="color: #000;">
        </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="horainicio" class="col-md-2 col-form-label text-md-right" style="color: #000;">{{ __('Hora inicio') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o" style="color: #000;"></i>
            </div>
            <select id="horainicio" name="horainicio" class="form-control" style="color: #000;">
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
        <label for="horafinal" class="col-md-2 col-form-label text-md-right" style="color: #000;">{{ __('Hora final') }}</label>
        <div class="col-md-6">  
            <div class="input-group">
            <div class="input-group-addon">
            <i class="fa fa-clock-o" style="color: #000;"></i>
            </div>
            <select id="horafinal" name="horafinal" class="form-control" style="color: #000;">
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

     @if($sms!='')
    <div class="form-group row">
        <label class="col-md-2 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
        </div>
    </div>
    @endif

    <div class="form-group row">
        <label class="col-md-2 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <button type="submit" class="my-button"><i class="fa fa-search"><b> Buscar</b></i></button>
        </div>
    </div>
    {{Form::close()}}

</div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                @if($inicio!=="" && $fecha!==""  && $sms=="")
                <thead class="my-thead">
                    <th>Área</th>
                    <th>Capacidad</th>
                    <th>Disponibilidad</th>
                </thead>
                @foreach($diferentes as $d)
        {!! Form::open(array('url'=>'operacion/adminreservas/edit','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
                <tr class="my-td">
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
                        <input name="eid" value="{{$idquery}}" type="hidden">
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
</script>
@endsection
