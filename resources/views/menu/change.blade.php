@extends($layout)
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-user" style="color: #000;"></i>
<h3 class="box-title"><b>Perfil</b></h3>
</div>
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<h2><b>Cambiar contraseña</b></h2>
<br>

		@if (count($errors)>0)
		<div class="my-alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
				@endforeach
			</ul>
		</div>
		@endif

	{!!Form::model($usuario,['method'=>'PATCH','route'=>['change.update', Auth::user()->id]])!!}
	{{Form::token()}}
    {{ csrf_field() }}
     <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Contraseña') }}</label>
        <div class="col-md-6">
        <input id="password" type="password" placeholder="Contraseña" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" style="color: #000;" maxlength="20">
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right" style="color: #000;">{{ __('Confirmar Contraseña') }}</label>
        <div class="col-md-6">
        <input id="password-confirm" type="password" placeholder="Confirmar contraseña" class="form-control" name="password_confirmation" style="color: #000;" maxlength="20">
        </div>
    </div>
   
	<div class="form-group row">
		<label class="col-md-4 col-form-label text-md-right"></label>
		<div class="col-md-6">
		<button class="my-button" type="submit"><i class="fa fa-save"><b> Guardar</b></i></button>
		</div>
	</div>

	{!!Form::close()!!}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
