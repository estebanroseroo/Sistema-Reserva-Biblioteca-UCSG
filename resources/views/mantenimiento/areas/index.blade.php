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
<h2>Áreas de estudio	<a href="areas/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.areas.search')
</div>
</div>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Área de estudio</th>
					<th>Capacidad</th>
					<th>Disponibilidad</th>
					<th colspan="2"></th>
				</thead>

				@foreach($areas as $a)
				<tr>
					<td>{{$a->nombre}}</td>
					<td>{{$a->capacidad}}</td>
					<td>{{$a->disponibilidad}}</td>
					@if($a->disponibilidad=='Disponible')
					<td><a href="{{URL::action('AreaController@edit', $a->idarea)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					@else
					<td><a href="{{URL::action('AreaController@edit', $a->idarea)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a></td>
					<td><a href="" data-target="#modal-delete-{{$a->idarea}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
					@endif
				</tr>
				@include('mantenimiento.areas.modal')
				@endforeach
			</table>
		</div>
		{{$areas->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
