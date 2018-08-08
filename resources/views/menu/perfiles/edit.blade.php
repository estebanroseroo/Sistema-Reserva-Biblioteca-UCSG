@extends ('layouts.usu')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-user"></i>
<h3 class="box-title"><b>Perfil</b></h3>
</div>
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<h2>Editar perfil</h2>

		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::model($usuario,['method'=>'PATCH','route'=>['perfiles.update', Auth::user()->id]])!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Nombre') }}</label>
        <div class="col-md-8">
        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" oncopy="return false" onpaste="return false" onkeypress="return lettersOnly(event)" placeholder="Nombre" value="{{ $usunombre }}">
        </div>
     </div>

    <div class="form-group row">
        <label for="apellido" class="col-md-3 col-form-label text-md-right">{{ __('Apellido') }}</label>
        <div class="col-md-8">
        <input id="apellido" type="text" class="form-control" name="apellido" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" oncopy="return false" onpaste="return false" onkeypress="return lettersOnly(event)" placeholder="Apellido" value="{{ $usuapellido }}">
        </div>
    </div>

      <div class="form-group row">
        <label for="telefono" class="col-md-3 col-form-label text-md-right">{{ __('Teléfono') }}</label>
        <div class="col-md-8">
        <input id="telefono" type="text" placeholder="Teléfono" oncopy="return false" onpaste="return false" class="form-control" name="telefono" onkeypress="return isNumberKey(event)" maxlength="10" value="{{ $usuario->telefono }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Facultad</label>
        <div class="col-md-8">
        <select id="idfacultadedit" name="idfacultadedit" class="form-control">
                @foreach ($facultades as $fac)
                @if($fac->idfacultad==$usuario->idfacultad)
                <option value="{{$fac->idfacultad}}" selected>{{$fac->nombre}}</option>
                @else
                <option value="{{$fac->idfacultad}}">{{$fac->nombre}}</option>
                @endif
                @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Carrera</label>
        <div class="col-md-8">
        <select id="idcarreraedit" name="idcarreraedit" class="form-control">
                @foreach ($carreras as $car)
                @if($car->idcarrera==$usuario->idcarrera)
                <option value="{{$car->idcarrera}}" selected>{{$car->nombre}}</option>
                @else
                <option value="{{$car->idcarrera}}">{{$car->nombre}}</option>
                @endif
                @endforeach
        </select>
        </div>
        <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
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
<script>
     function lettersOnly(){
        var charCode = event.keyCode;
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8)
            return true;
        else
            return false;
        }
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true; 
        }
</script>
@endsection