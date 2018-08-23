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
<h2><b>Usuarios</b></h2>
<br>
@include('mantenimiento.usuarios.search')
</div>
<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
<h2><label value="."></label></h2>
<br>
<div class="form-group">
	<div class="input-group">
	<a href="usuarios/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a>
	</div>
</div>
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="my-thead">
					<th>Usuario</th>
					<th>Correo</th>
					<th>Teléfono</th>
					<th>Facultad</th>
					<th>Carrera</th>
					<th>Rol</th>
					<th colspan="2">Opciones</th>
				</thead>

				@foreach($usuarios as $u)
				<tr class="my-td">
					<td>{{$u->name}}</td>
					<td>{{$u->email}}</td>
					<td>{{$u->telefono}}</td>
					<td>{{$u->facultad}}</td>
					<td>{{$u->carrera}}</td>
					<td>{{$u->rol}}</td>
					@if(Auth::user()->email=='roseroesteban@gmail.com')
					<td><a href="{{URL::action('UsuarioController@edit', $u->id)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					@else
					@if(Auth::user()->email==$u->email || $u->idtipousuario>'1')
					<td><a href="{{URL::action('UsuarioController@edit', $u->id)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					@else
					<td></td>
					@endif
					@endif

					@if(Auth::user()->email=='roseroesteban@gmail.com' && $u->email!='roseroesteban@gmail.com' )
					<td><a href="" data-target="#modal-delete-{{$u->id}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
					@else
					@if($u->idtipousuario>'1')
					<td><a href="" data-target="#modal-delete-{{$u->id}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
					@else
					<td></td>
					@endif
					@endif
				</tr>
				@include('mantenimiento.usuarios.modal')
				@endforeach
			</table>
		</div>
		{{$usuarios->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
