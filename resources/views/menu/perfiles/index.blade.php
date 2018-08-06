@extends ('layouts.usu')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-user"></i>
<h3 class="box-title"><b>Perfil</b></h3>
</div>
<div class="box-body">
<div class="row">
<div class="col-md-12">

<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Mi perfil</h2>
</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>NOMBRE</th>
					<th>CORREO</th>
					<th>TELÉFONO</th>
					<th>FACULTAD</th>
					<th>CARRERA</th>
				</thead>
				@foreach($usuarios as $u)
				<tr>
					<td>{{$u->name}}</td>
					<td>{{$u->email}}</td>
					<td>{{$u->telefono}}</td>
					<td>{{$u->facultad}}</td>
					<td>{{$u->carrera}}</td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
@endsection
