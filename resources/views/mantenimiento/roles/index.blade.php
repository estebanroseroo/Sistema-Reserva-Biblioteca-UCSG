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
<h2>Roles	<a href="roles/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.roles.search')
</div>
</div>

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Rol</th>
					<th colspan="2"></th>
				</thead>

				@foreach($roles as $r)
				<tr>
					<td>{{$r->nombre}}</td>
					<td><a href="{{URL::action('RolController@edit', $r->idtipousuario)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					@if($r->idtipousuario>2 && $r->temporal=='vacio')
					<td><a href="" data-target="#modal-delete-{{$r->idtipousuario}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
					@else
					<td></td>
					@endif
				</tr>
				@include('mantenimiento.roles.modal')
				@endforeach
			</table>
		</div>
		{{$roles->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
