<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usu->name }}!</h3>

<p>Este es el código QR de su reserva</p>

			<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->generate($qrcod)) !!} ">
			<br>
			<b>Código:</b> {{$qrcod}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha inicio:</b> {{$fechaini}}
			<br>
			<b>Fecha final:</b> {{$fechafin}}
			<br>
			<b>Horario:</b> {{$horaini}}-{{$horafin}}
			<br>
			<p>En caso de no poder asistir a la reserva antes de las {{$tiempoespera}} por favor cancelar la reserva a través del sistema.</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>