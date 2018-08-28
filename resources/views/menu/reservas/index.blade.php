@extends ('layouts.usu')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-graduation-cap" style="color: #000;"></i>
<h3 class="box-title"><b>Menú</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<h2><b>Mis reservas </b><a href="{{url('menu/reservas/create')}}"><button class="my-button"><i class="fa fa-plus"><b> Nueva reserva</b></i></button></a></h2>
<br>
@include('menu.reservas.search')
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="my-thead">
					<th>Área</th>
					<th>Fecha</th>
					<th>Hora Inicio</th>
					<th>Hora Fin</th>
					<th>Ocupantes</th>
					<th>Código</th>
					<th>Opciones</th>
				</thead>

				@foreach($reservas as $r)
				<tr class="my-td">
					<td>{{$r->nombrearea}}</td>
					<td>{{$r->fecha}}</td>
					<td>{{$r->horainicio}}</td>
					<td>{{$r->horafinal}}</td>
					<td>{{$r->cantidad}}</td>
					<td><a href="" data-target="#modal-qr-{{$cod=$r->idreserva}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-qrcode"> <b>QR</b></i></button>
					</td>
					@if($r->estado=='A')
					<td><a href="" data-target="#modal-delete-{{$r->idreserva}}" data-toggle="modal">
						<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
					@else
					<td></td>
					@endif
				</tr>
				@include('menu.reservas.modal')
				@include('menu.reservas.qrmodal')
				@endforeach
			</table>
		</div>
		{{$reservas->render()}}
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
