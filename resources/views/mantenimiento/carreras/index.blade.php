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
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Carreras	<a href="carreras/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.carreras.search')
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Carrera</th>
					<th>Facultad</th>
					<th colspan="2"></th>
				</thead>

				@foreach($carreras as $c)
				<tr>
					<td>{{$c->nombre}}</td>
					<td>{{$c->facultad}}</td>
					<td><a href="{{URL::action('CarreraController@edit', $c->idcarrera)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					<td><a href="" data-target="#modal-delete-{{$c->idcarrera}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.carreras.modal')
				@endforeach
			</table>
		</div>
		{{$carreras->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
