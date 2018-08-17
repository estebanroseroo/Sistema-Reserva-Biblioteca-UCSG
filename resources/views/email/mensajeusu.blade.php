<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usuario->name }}!</h3>

<p>Gracias por registrarte en el Sistema de gestión de reservas de áreas de estudio, ya puedes iniciar sesión.</p>
<p>Estos son sus datos:</p>
			<b>Teléfono:</b> {{$usuario->telefono}}
			<br>
			<b>Facultad:</b> {{$facultad->nombre}}
			<br>
			<b>Carrera:</b> {{$carrera->nombre}}

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>
