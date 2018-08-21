<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Estimado usuario,</h3>

<p>Lamentamos informarle que su código QR expiró, por lo que se ha procedido a cancelar la siguiente reserva:</p>
			<b>Código:</b> {{$reservas->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$reservas->fecha}}
			<br>
			<b>Horario:</b> {{$reservas->horainicio}}-{{$reservas->horafinal}}
			<br>
			<p>Quedamos a su disposición para cualquier aclaración.</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>