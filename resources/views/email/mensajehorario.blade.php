<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Estimado usuario,</h3>

<p>Lamentamos informarte que el horario <b>{{$reli->horainicio}}-{{$reli->horafinal}}</b> no estará disponible, por lo que se ha procedido a cancelar la siguiente reserva:</p>
			<b>Código:</b> {{$reli->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$reli->fecha}}
			<br>
			<p>Disculpe las molestias, quedamos a su disposición para cualquier aclaración.</p>

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>