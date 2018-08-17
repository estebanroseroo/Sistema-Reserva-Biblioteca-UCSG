<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-qr-{{$cod}}">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="my-modal-header">
			<h2 class="modal-title"><b>Este es el código QR de tu reserva</b></h2>
		</div>
		<div class="modal-body" style="text-align:center">
			@foreach($reservas as $r)
			@if($r->idreserva==$cod)
			<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->generate($r->codigoqr)) !!} ">
			<br>
			<b>Código:</b> {{$r->codigoqr}}</b>
			@else
			@endif
  			@endforeach
		</div>
		<div class="modal-footer">
			<button type="button" class="my-button" data-dismiss="modal"><i class="fa fa-home"><b> Regresar</b></i></button>
		</div>
		</div>
	</div>
	
</div>