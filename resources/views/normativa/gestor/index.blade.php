@extends ('layouts.gestor')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-info-circle" style="color: #000;"></i>
<h3 class="box-title"><b>Normativa</b></h3>
</div>
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2><b>Normativa de uso del Sistema</b></h2>
<br>
</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="my-thead">
					<th>No.</th>
					<th>Detalle</th>
				</thead>
				<tr class="my-td">
					<td>1)</td>
					<td>Se puede reservar las áreas de estudio en el horario de atención de la Biblioteca General. (Lunes a Viernes de 08:00 AM a 20:00 PM)</td>
				</tr>
				<tr class="my-td">
					<td>2)</td>
					<td>El usuario que se registre en el sistema con un rol incorrecto será eliminado.</td>
				</tr>
				<tr class="my-td">
					<td>3)</td>
					<td>Si el usuario NO asiste a la reserva después de 15 minutos de haber iniciado será automáticamente cancelada.</td>
				</tr>
				<tr class="my-td">
					<td>4)</td>
					<td>Para validar una reserva el usuario debe presentar su código QR.</td>
				</tr>
				<tr class="my-td">
					<td>5)</td>
					<td>Si el usuario llega después de los 15 minutos de gracia sólo se validará la reserva si nadie más ha ocupado el área de estudio en el horario seleccionado.</td>
				</tr>
				<tr class="my-td">
					<td>6)</td>
					<td>Las reservas validadas desaparecerán automáticamente después de haber finalizado la reserva.</td>
				</tr>
				<tr class="my-td">
					<td>7)</td>
					<td>Sólo se debe eliminar una reserva si existe un motivo válido.</td>
				</tr>
				<tr class="my-td">
					<td>8)</td>
					<td>NO se puede reservar dos o más áreas de estudio en el mismo horario.</td>
				</tr>
				<tr class="my-td">
					<td>9)</td>
					<td>El límite de tiempo en cada reserva es de máximo cinco horas diarias.</td>
				</tr>
				<tr class="my-td">
					<td>10)</td>
					<td>El usuario puede disponer de un máximo de cinco reservas.</td>
				</tr>
				<tr class="my-td">
					<td>11)</td>
					<td>Se puede reservar máximo con un mes de anticipación.</td>
				</tr>
			</table>
		</div>
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
