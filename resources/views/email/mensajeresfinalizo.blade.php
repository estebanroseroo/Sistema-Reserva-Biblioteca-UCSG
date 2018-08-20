<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usu->name }}!</h3>

<p>Gracias por usar el Sistema de gestión de reservas de áreas de estudio, se finalizó exitosamente la siguiente reserva:</p>
			<b>Código:</b> {{$reservas->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$reservas->fecha}}
			<br>
			<b>Horario:</b> {{$reservas->horainicio}}-{{$reservas->horafinal}}
			<br>
			<p>Lo esperamos de nuevo!</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>

