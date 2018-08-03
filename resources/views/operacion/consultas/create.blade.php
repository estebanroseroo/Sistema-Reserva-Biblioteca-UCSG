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
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Consulta QR</h2>

<div class="form-group row">
    <div class="col-md-6"> 
    </div>
</div>

    <div class="form-group row">
        <label for="usuario" class="col-md-3 col-form-label text-md-right">{{ __('Usuario') }}</label>
        <div class="col-md-6">  
        <input type="text" id="usuario" readonly="readonly" class="form-control" name="usuario" value="{{$codnombre}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="fecha" class="col-md-3 col-form-label text-md-right">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <input type="text" id="fecha" readonly="readonly" class="form-control" name="fecha" value="{{$codfecha}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="horario" class="col-md-3 col-form-label text-md-right">{{ __('Horario') }}</label>
        <div class="col-md-6">  
        <input type="text" id="horario" readonly="readonly" class="form-control" name="horario" value="{{$codhorario}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="area" class="col-md-3 col-form-label text-md-right">{{ __('Área') }}</label>
        <div class="col-md-6">  
        <input type="text" id="area" readonly="readonly" class="form-control" name="area" value="{{$codarea}}">
        <input type="hidden" name="idarea">
        </div>
    </div>

    <div class="form-group row"> 
        <label for="cantidad" class="col-md-3 col-form-label text-md-right">{{ __('Cantidad de ocupantes') }}</label>
         <div class="col-md-6">
         <input id="cantidad" type="number" readonly="readonly" name="cantidad" class="form-control" value="{{$codcantidad}}">
        </div>
    </div>

</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
