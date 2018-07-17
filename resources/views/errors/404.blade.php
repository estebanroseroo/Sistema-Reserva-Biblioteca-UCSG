<html>
	<head>
		<link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
		<style>
			body {
				margin:0;
				width:100%;
				height:100%;
				color:#3d4244;
				display:table;
			}
			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}
			.content {
				text-align: center;
				display: inline-block;
			}
			a, u {
    			text-decoration: none;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<i class="fa fa-file big-icon" style="font-size:150px"></i>
				<p style="font-size:40px">Lo sentimos, la página que está buscando no existe.</p>
			</div>
		</div>
		<div class="container">
			<div class="content">
				<p style="font-size:30px"><b>Enlace de ayuda</b></p>
				<a href="{{url('/logout')}}"><i class="fa fa-home" style="font-size:30px; color:#3d4244"></i>
					<span style="font-size:20px; color:#3d4244">Inicio de sesión</span></a>
			</div>
		</div>
	</body>
</html>