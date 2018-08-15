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
<h2>Horarios	<a href="horarios/create"><button class="my-button"><i class="fa fa-plus"><b> Agregar</b></i></button></a></h2>
@include('mantenimiento.horarios.search')
</div>
</div>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Hora Inicio</th>
					<th>Hora Final</th>
					<th></th>
				</thead>

				@foreach($horarios as $h)
				<tr>
					<td>{{$h->horainicio}}</td>
					<td>{{$h->horafinal}}</td>
					<td><a href="" data-target="#modal-delete-{{$h->idhora}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('mantenimiento.horarios.modal')
				@endforeach
			</table>
		</div>
		{{$horarios->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
