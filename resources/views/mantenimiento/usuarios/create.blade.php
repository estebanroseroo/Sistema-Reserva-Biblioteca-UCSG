@extends ('layouts.admin')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-wrench" style="color: #000;"></i>
<h3 class="box-title"><b>Mantenimiento</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
    
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<h2><b>Nuevo usuario</b></h2>
        <br>
	{!!Form::open(array('url'=>'mantenimiento/usuarios', 'method'=>'POST', 'autocomplete'=>'off'))!!}
	{{Form::token()}}
    {{ csrf_field() }}
	<div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Nombre') }}</label>
         <div class="col-md-6">
         <input id="name" type="text" placeholder="Nombre" 
        onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control" name="name" oncopy="return false" onpaste="return false" onkeypress="return lettersOnly(event)" value="{{ old('name') }}" style="color: #000;" maxlength="15">
        </div>
     </div>

    <div class="form-group row">
        <label for="apellido" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Apellido') }}</label>
         <div class="col-md-6">
         <input id="apellido" type="text" placeholder="Apellido" 
        onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" class="form-control" name="apellido" oncopy="return false" onpaste="return false" onkeypress="return lettersOnly(event)" value="{{ old('apellido') }}" style="color: #000;" maxlength="15">
        </div>
     </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Correo') }}</label>
        <div class="col-md-6">
        <input id="email" type="email" placeholder="Correo" class="form-control" name="email" oncopy="return false" onpaste="return false" value="{{ old('email')}}" style="color: #000;">
        </div>
    </div>

     <div class="form-group row">
        <label for="telefono" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Teléfono') }}</label>
        <div class="col-md-6">
        <input id="telefono" type="text" oncopy="return false" onpaste="return false" placeholder="Teléfono" class="form-control" name="telefono" onkeypress="return isNumberKey(event)" maxlength="10" value="{{ old('telefono') }}" style="color: #000;">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right" style="color: #000;">Rol</label>
        <div class="col-md-6">
        <select id="idtipousuariousu" name="idtipousuariousu" class="form-control" style="color: #000;">
            @foreach ($roles as $r)
            <option value="{{$r->idtipousuario}}">{{$r->nombre}}</option>
            @endforeach
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right" style="color: #000;">Facultad</label>
        <div class="col-md-6">
        <select id="idfacultadusu" name="idfacultadusu" class="form-control" style="color: #000;">
            @foreach ($facultades as $fac=>$value)
            <option value="{{$fac}}">{{$value}}</option>
            @endforeach
        </select>
        </div>
    </div>

     <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right" style="color: #000;">Carrera</label>
        <span id="loader"><i class="fa fa-spinner fa-2x fa-spin"></i></span>
        <div class="col-md-6">
        <select id="idcarrerausu" name="idcarrerausu" class="form-control" style="color: #000;">
        </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Contraseña') }}</label>
        <div class="col-md-6">
        <input id="password" type="password" placeholder="Contraseña" class="form-control" name="password" oncopy="return false" onpaste="return false" style="color: #000;">
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Confirmar Contraseña') }}</label>
        <div class="col-md-6">
        <input id="password-confirm" type="password" placeholder="Confirmar contraseña" class="form-control" name="password_confirmation" oncopy="return false" onpaste="return false" style="color: #000;">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right"></label>
        <div class="col-md-6">
        <label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;"></label>
        </div>
    </div>

	<div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right"></label>
        <div class="col-md-6">
		<button class="my-button" type="submit" onclick="return validateForm()"><i class="fa fa-save"><b> Guardar</b></i></button>
        </div>
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
    if(document.getElementById('password').value==""){
        document.getElementById('mensaje').innerHTML = 'El campo contraseña es obligatorio';
        return false;
        }
    if(document.getElementById('password').value.length<6){
        document.getElementById('mensaje').innerHTML = 'El campo contraseña debe tener mínimo 6 caracteres';
        return false;
        }
    if(document.getElementById('password').value!=document.getElementById('password-confirm').value){
        document.getElementById('mensaje').innerHTML = 'El campo contraseña no es igual a confirmar contraseña';
        return false;
        }
    if(!document.getElementById('email').value.endsWith('@cu.ucsg.edu.ec')){
        document.getElementById('mensaje').innerHTML = 'El campo correo debe terminar en @cu.ucsg.edu.ec';
        return false;
        }
    }
    if(document.getElementById('name').value!="" &&
    document.getElementById('apellido').value!="" &&
    document.getElementById('password').value==document.getElementById('password-confirm').value &&
    document.getElementById('email').value.endsWith('@cu.ucsg.edu.ec') &&
    validateForm()==false){
        document.getElementById('mensaje').innerHTML = 'El campo correo ya existe';
    }
</script>
@endsection

