<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usu->name }}!</h3>

<p>Gracias por usar el Sistema de gestión de reservas de áreas de estudio, se registró exitosamente su asistencia a la siguiente reserva:</p>
			<b>Código:</b> {{$valida->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$valida->fecha}}
			<br>
			<b>Horario:</b> {{$valida->horainicio}}-{{$valida->horafinal}}
			<br>
			<p>Disfrute su reserva!</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>
