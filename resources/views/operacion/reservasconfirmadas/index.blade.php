@extends(Auth::user()->idtipousuario==1 ? 'layouts.admin' : 'layouts.gestor')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-university" style="color: #000;"></i>
<h3 class="box-title"><b>Reserva</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<h2><b>Reservas confirmadas </b><a href="{{url('operacion/adminreservas/create')}}"><button class="my-button"><i class="fa fa-plus"><b>Agregar</b></i></button></a></h2>
<br>
@include('operacion.reservasconfirmadas.search')
</div>
<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
	<h2><label value="."></label></h2>
	<br>
	<div class="form-group">
	<div class="input-group">
		<span class="input-group-btn">
		<a href="{{url('operacion/adminreservas/create')}}"><button class="my-button"><i class="fa fa-university"><b>Nueva reserva</b></i></button></a>
		</span>
	</div>
	</div>
</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="my-thead">
					<th>Usuario</th>
					<th>Área</th>
					<th>Fecha</th>
					<th>Hora Inicio</th>
					<th>Hora Fin</th>
					<th>Hora LLegada</th>
					<th>Ocupantes</th>
					<th>Código</th>
					<th>Opciones</th>
				</thead>

				@foreach($reservas as $r)
				<tr class="my-td">
					<td>{{$r->nombreusuario}}</td>
					<td>{{$r->nombrearea}}</td>
					<td>{{$r->fecha}}</td>
					<td>{{$r->horainicio}}</td>
					<td>{{$r->horafinal}}</td>
					<td>{{$r->horallegada}}</td>
					<td>{{$r->cantidad}}</td>
					<td><a href="" data-target="#modal-qr-{{$cod=$r->idreserva}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-qrcode"> <b>QR</b></i></button></td>
					<td><a href="" data-target="#modal-delete-{{$r->idreserva}}" data-toggle="modal">
					<button class="my-button"><i class="fa fa-trash"> <b>Eliminar</b></i></button></a></td>
				</tr>
				@include('operacion.reservasconfirmadas.modal')
				@include('operacion.reservasconfirmadas.qrmodal')
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
