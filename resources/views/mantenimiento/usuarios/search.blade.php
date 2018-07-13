{!! Form::open(array('url'=>'mantenimiento/usuarios','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
	<div class="input-group">
		<input type="text" class="form-control" name="searchText" placeholder="Búsqueda por nombre, facultad o carrera" value="{{$searchText}}">
		<span class="input-group-btn">
			<button type="submit" class="my-button"><i class="fa fa-search"> <b>Buscar</b></i></button>
		</span>
	</div>
</div>
{{Form::close()}}