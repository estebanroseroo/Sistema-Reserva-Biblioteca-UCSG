<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Estimado usuario,</h3>

<p>Lamentamos informarte que el área <b>{{$area->nombre}}</b> no estará disponible, por lo que se ha procedido a cancelar la siguiente reserva:</p>
			<b>Código:</b> {{$r->codigoqr}}
			<br>
			<b>Fecha:</b> {{$r->fecha}}
			<br>
			<b>Horario:</b> {{$r->horainicio}}-{{$r->horafinal}}
			<br>
			<p>Disculpe las molestias, quedamos a su disposición para cualquier aclaración.</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>