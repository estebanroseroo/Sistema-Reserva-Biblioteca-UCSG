<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usu->name }}!</h3>

<p>Este es el código QR de su reserva</p>

@foreach($reservas as $r)
			@if($r->codigoqr==$qrcod)
			<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->generate($r->codigoqr)) !!} ">
			<br>
			<b>Código:</b> {{$r->codigoqr}}
			<br>
			<b>Área:</b> {{$area->nombre}}
			<br>
			<b>Fecha:</b> {{$r->fecha}}
			<br>
			<b>Horario:</b> {{$r->horainicio}}-{{$r->horafinal}}
			<br>
			<p>En caso de no poder asistir a la reserva antes de las {{$r->tiempoespera}} por favor cancelar la reserva a través del sistema.</p>
			@else
			@endif
@endforeach

<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>