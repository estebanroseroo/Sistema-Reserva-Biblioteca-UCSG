@extends ('layouts.admin')
@section('contenido')
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Roles	<a href="roles/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.roles.search')
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th></th>
				</thead>

				@foreach($roles as $r)
				<tr>
					<td>{{$r->idtipousuario}}</td>
					<td>{{$r->nombre}}</td>
					<td><a href="{{URL::action('RolController@edit', $r->idtipousuario)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a>
						<a href="" data-target="#modal-delete-{{$r->idtipousuario}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.roles.modal')
				@endforeach
			</table>
		</div>
		{{$roles->render()}}
	</div>
</div>
@endsection
