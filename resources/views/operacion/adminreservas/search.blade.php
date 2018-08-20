{!! Form::open(array('url'=>'operacion/adminreservas','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
	<div class="input-group">
		<input type="text" class="form-control" name="searchText" placeholder="Búsqueda por usuario, área o fecha" value="{{$searchText}}" id="idbusqueda" onkeypress="return alpha(event);" oncopy="return false" onpaste="return false" maxlength="20">
		<span class="input-group-btn">
			<button type="submit" class="my-button"><i class="fa fa-search"> <b>Buscar</b></i></button>
		</span>
	</div>
</div>
<script>
	function alpha(e) {
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
}
</script>
{{Form::close()}}