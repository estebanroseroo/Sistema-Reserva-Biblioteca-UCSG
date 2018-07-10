@extends ('layouts.admin')
@section('contenido')
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Áreas de estudio	<a href="areas/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.areas.search')
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th>Disponibilidad</th>
					<th></th>
				</thead>

				@foreach($areas as $a)
				<tr>
					<td>{{$a->idarea}}</td>
					<td>{{$a->nombre}}</td>
					<td>{{$a->disponibilidad}}</td>
					<td><a href="{{URL::action('AreaController@edit', $a->idarea)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a>
						<a href="" data-target="#modal-delete-{{$a->idarea}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.areas.modal')
				@endforeach
			</table>
		</div>
		{{$areas->render()}}
	</div>
</div>
@endsection