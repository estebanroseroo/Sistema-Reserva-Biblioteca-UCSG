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
<h2>Facultades	<a href="facultades/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.facultades.search')
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

				@foreach($facultades as $f)
				<tr>
					<td>{{$f->idfacultad}}</td>
					<td>{{$f->nombre}}</td>
					<td><a href="{{URL::action('FacultadController@edit', $f->idfacultad)}}"><button class="my-button"><i class="fa fa-pencil"> <b>Editar</b></i></button></a>
						<a href="" data-target="#modal-delete-{{$f->idfacultad}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.facultades.modal')
				@endforeach
			</table>
		</div>
		{{$facultades->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
