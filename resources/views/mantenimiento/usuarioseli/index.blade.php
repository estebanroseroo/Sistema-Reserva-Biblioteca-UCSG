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
<h2><b>Usuarios eliminados </b></h2>
<br>
@include('mantenimiento.usuarioseli.search')
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
					<td><a href="" data-target="#modal-delete-{{$u->id}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-check"> <b>Activar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.usuarioseli.modal')
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
