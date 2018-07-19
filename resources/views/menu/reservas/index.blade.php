@extends ('layouts.admin')
@section('contenido')
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Usuarios	<a href="usuarios/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.usuarios.search')
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Nombre</th>
					<th>Correo</th>
					<th>Teléfono</th>
					<th>Facultad</th>
					<th>Carrera</th>
					<th>Rol</th>
					<th></th>
				</thead>

				@foreach($usuarios as $u)
				<tr>
					<td>{{$u->name}}</td>
					<td>{{$u->email}}</td>
					<td>{{$u->telefono}}</td>
					<td>{{$u->facultad}}</td>
					<td>{{$u->carrera}}</td>
					<td>{{$u->rol}}</td>
					<td><a href="{{URL::action('UsuarioController@edit', $u->id)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a>
						<a href="" data-target="#modal-delete-{{$u->id}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.usuarios.modal')
				@endforeach
			</table>
		</div>
		{{$usuarios->render()}}
	</div>
</div>
@endsection
