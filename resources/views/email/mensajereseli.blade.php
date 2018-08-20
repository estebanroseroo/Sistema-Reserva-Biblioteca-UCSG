<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usu->name }}!</h3>

<p>Gracias por usar el Sistema de gestión de reservas de áreas de estudio, se eliminó exitosamente la siguiente reserva:</p>
			<b>Código:</b> {{$reserva->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$reserva->fecha}}
			<br>
			<b>Horario:</b> {{$reserva->horainicio}}-{{$reserva->horafinal}}

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>