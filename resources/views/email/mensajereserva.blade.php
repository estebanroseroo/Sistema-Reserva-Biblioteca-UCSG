<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Estimado usuario,</h3>

<p>Lamentamos informarte que su código QR se eliminó, por lo que se ha procedido a cancelar la siguiente reserva:</p>
			<b>Código:</b> {{$reserva->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$reserva->fecha}}
			<br>
			<b>Horario:</b> {{$reserva->horainicio}}-{{$reserva->horafinal}}
			<br>
			<p>Quedamos a su disposición para cualquier aclaración.</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>