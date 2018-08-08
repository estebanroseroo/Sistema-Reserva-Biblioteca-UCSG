@extends ('layouts.admin')
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

@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

 <div class="form-group row">
    <div class="col-md-6"> 
    </div>
</div>
		
	{!!Form::open(array('url'=>'operacion/adminreservas', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
  
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>
        <div class="col-md-6">
        <select id="id" name="id" class="form-control">
            @foreach ($usuarios as $u)
            <option value="{{$u->id}}">{{$u->name}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="fecha" class="col-md-4 col-form-label text-md-right">{{ __('Fecha') }}</label>
        <div class="col-md-6">  
        <input type="text" id="fecha" readonly="readonly" class="form-control" name="fecha" value="{{$efecha}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="horario" class="col-md-4 col-form-label text-md-right">{{ __('Horario') }}</label>
        <div class="col-md-6">  
        <input type="text" id="horario" readonly="readonly" class="form-control" name="horario" value="{{$ehorainicio}}">
        <input type="hidden" name="horainicio" value="{{$horarioinicio->horainicio}}">
        <input type="hidden" name="horafinal" value="{{$horarioinicio->horafinal}}">
        <input type="hidden" name="horaid" value="{{$ehoraid}}">
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
        <label for="capacidad" class="col-md-4 col-form-label text-md-right">{{ __('Capacidad') }}</label>
        <div class="col-md-6">  
        <input type="text" id="capacidad" readonly="readonly" class="form-control" name="capacidad" value="{{$ecapacidad}}">
        </div>
    </div>

    <div class="form-group row"> 
        <label for="cantidad" class="col-md-4 col-form-label text-md-right">{{ __('Cantidad de ocupantes') }}</label>
         <div class="col-md-6">
         <input id="cantidad" type="number" min="3" placeholder="Cantidad de ocupantes" name="cantidad" class="form-control">
        </div>
    </div>

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

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
    $("#id").select2({
    language: {
        noResults: function (params) {
            return "No se encontraron resultados";
            }
        }
    });
</script>
@endsection

