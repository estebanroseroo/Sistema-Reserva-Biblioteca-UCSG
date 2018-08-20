@extends ('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-wrench"></i>
<h3 class="box-title"><b>Mantenimiento</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
    
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2>Editar usuario</h2>

	{!!Form::model($usuario,['method'=>'PATCH','route'=>['usuarios.update', $usuario->id]])!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>
         <div class="col-md-6">
         <input id="name" type="text" placeholder="Nombre" 
         onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control" name="name" oncopy="return false" onpaste="return false" onkeypress="return lettersOnly(event)" value="{{ $usunombre }}">
        </div>
     </div>

     <div class="form-group row">
        <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellido') }}</label>
         <div class="col-md-6">
         <input id="apellido" type="text" placeholder="Apellido" 
        onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control" name="apellido" oncopy="return false" onpaste="return false" onkeypress="return lettersOnly(event)" value="{{ $usuapellido }}">
        </div>
     </div>

      <div class="form-group row">
        <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>
        <div class="col-md-6">
        <input id="telefono" type="text" placeholder="Teléfono" class="form-control" name="telefono" value="{{ $usuario->telefono }}" onkeypress="return isNumberKey(event)" maxlength="10" oncopy="return false" onpaste="return false">
        </div>
    </div>

     <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">Rol</label>
        <div class="col-md-6">
        <select id="idtipousuariousu" name="idtipousuariousu" class="form-control">
            @foreach ($roles as $rol)
                @if($rol->idtipousuario==$usuario->idtipousuario)
                <option value="{{$rol->idtipousuario}}" selected>{{$rol->nombre}}</option>
                @else
                <option value="{{$rol->idtipousuario}}">{{$rol->nombre}}</option>
                @endif
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">Facultad</label>
        <div class="col-md-6">
        <select id="idfacultadusu" name="idfacultadusu" class="form-control">
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
        <label class="col-md-4 col-form-label text-md-right">Carrera</label>
        <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
        <div class="col-md-6">
        <select id="idcarrerausu" name="idcarrerausu" class="form-control">
                @foreach ($carreras as $car)
                @if($car->idcarrera==$usuario->idcarrera)
                <option value="{{$car->idcarrera}}" selected>{{$car->nombre}}</option>
                @else
                <option value="{{$car->idcarrera}}">{{$car->nombre}}</option>
                @endif
                @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;"></label>
        </div>
    </div>

	<div class="form-group">
		<button class="my-button" type="submit" onclick="return validateForm()"><i class="fa fa-save"><b> Guardar</b></i></button>
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
        function validateForm() {
    if(document.getElementById('name').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo nombre es obligatorio';
        return false;
        }
    if(document.getElementById('apellido').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo apellido es obligatorio';
        return false;
        }
    }
</script>
@endsection