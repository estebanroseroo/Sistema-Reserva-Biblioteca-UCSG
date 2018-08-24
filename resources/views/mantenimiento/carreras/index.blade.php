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
<h2><b>Carreras </b><a href="carreras/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
<br>	
@include('mantenimiento.carreras.search')
</div>
<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
<h2><label value="."></label></h2>
<br>
<div class="form-group">
	<div class="input-group">
	<a href="carreras/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a>
	</div>
</div>
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="my-thead">
					<th>Carrera</th>
					<th>Facultad</th>
					<th colspan="2">Opciones</th>
				</thead>

				@foreach($carreras as $c)
				<tr class="my-td">
					<td>{{$c->nombre}}</td>
					<td>{{$c->facultad}}</td>
					<td><a href="{{URL::action('CarreraController@edit', $c->idcarrera)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					@if($c->temporal=='vacio')
					<td><a href="" data-target="#modal-delete-{{$c->idcarrera}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
					@else
					<td></td>
					@endif
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
