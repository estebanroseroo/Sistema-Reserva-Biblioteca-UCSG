{!! Form::open(array('url'=>'mantenimiento/carreras','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
	<div class="input-group">
		<input type="text" class="form-control" name="searchText" id="searchText" placeholder="Búsqueda por carrera o facultad" value="{{$searchText}}" onkeyup="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());" oncopy="return false" onpaste="return false" maxlength="20" style="color: #000;">
		<span class="input-group-btn">
			<button type="submit" class="my-button"><i class="fa fa-search"> <b>Buscar</b></i></button>
		</span>
	</div>
</div>
<script>
$(document).on('keypress', '#searchText', function (event) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});
</script>
{{Form::close()}}