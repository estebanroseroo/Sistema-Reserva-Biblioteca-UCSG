<!DOCTYPE html>
<html>
<head>
    <title>Prospector theater</title>

</head>

<body>
<h3>Hola {{ $usu->name }}!</h3>

<p>Este es el código QR de tu reserva</p>
<br />
@foreach($reservas as $r)
			@if($r->codigoqr==$qrcod)
			<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->generate($r->codigoqr)) !!} ">
			@else
			@endif
  			@endforeach
<br />
<p>Saludos cordiales,</p>
<p>Administrador.</p>
</body>
</html>