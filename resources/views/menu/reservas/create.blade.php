@extends ('layouts.usu')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-university"></i>
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
            <input type="text" class="form-control datepicker" placeholder="Fecha" name="fecha">
        </div>
        </div>
        <span class="input-group-btn">
            <button type="submit" class="my-button"><i class="fa fa-search"> <b>Buscar</b></i></button>
        </span>
    </div>
    {{Form::close()}}

</div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Horarios</th>
                    @foreach($areas as $a)
                    <th>Área</th>
                    @endforeach
                </thead>

                <tr>
                    <td></td>
                    @foreach($areas as $a)
                    <td>
                    {{$a->nombre}}
                    </td>
                    @endforeach
                </tr>

                <tr>
                    <td>08:00-09:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>09:00-10:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>10:00-11:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>11:00-12:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>12:00-13:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>13:00-14:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>14:00-15:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                 <tr>
                    <td>15:00-16:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>16:00-17:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>17:00-18:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>18:00-19:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
                <tr>
                    <td>19:00-20:00</td>
                    @foreach($areas as $a)
                    <td>{{$a->disponibilidad}}
                        <a href="{{URL::action('UsureservaController@create', $a->idarea)}}"><button class="my-button"><b>Reservar</b></button></a></td>
                    @endforeach
                </tr>
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
