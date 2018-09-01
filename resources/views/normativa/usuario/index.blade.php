@extends ('layouts.usu')
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
					<td>El usuario que se registre en el sistema con un rol, facultad o carrera incorrecto será eliminado.</td>
				</tr>
				<tr class="my-td">
					<td>3)</td>
					<td>Si NO asiste a la reserva después de 15 minutos de haber iniciado será automáticamente cancelada.</td>
				</tr>
				<tr class="my-td">
					<td>4)</td>
					<td>Si su reserva es cancelada será notificado a través de su correo institucional.</td>
				</tr>
				<tr class="my-td">
					<td>5)</td>
					<td>Por cada reserva realizada se generará un código QR único que será enviado a su correo institucional.</td>
				</tr>
				<tr class="my-td">
					<td>6)</td>
					<td>Para validar su reserva debe antes de ingresar al área de estudio presentar su código QR al Gestor de la Biblioteca General. </td>
				</tr>
				<tr class="my-td">
					<td>7)</td>
					<td>NO se puede reservar dos o más áreas de estudio en el mismo horario.</td>
				</tr>
				<tr class="my-td">
					<td>8)</td>
					<td>El límite de tiempo en cada reserva es de un máximo de cinco horas diarias.</td>
				</tr>
				<tr class="my-td">
					<td>9)</td>
					<td>El usuario puede disponer de un máximo de cinco reservas.</td>
				</tr>
				<tr class="my-td">
					<td>10)</td>
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
