<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Estimado usuario,</h3>

<p>Lamentamos informarte que su cuenta ha sido eliminada, por lo que se ha procedido a cancelar la siguiente reserva:</p>
			<b>Código:</b> {{$r->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$r->fecha}}
			<br>
			<b>Horario:</b> {{$r->horainicio}}-{{$r->horafinal}}
			<br>
			<p>Quedamos a su disposición para cualquier aclaración.</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>