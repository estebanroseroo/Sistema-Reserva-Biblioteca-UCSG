<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>UCSG en Línea | Servicios en Línea de la Universidad Católica de Santiago de Guayaquil</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <link rel="iconoucsg" href="{{asset('img/iconoucsg.png')}}">
    <link rel="icon" href="{{asset('img/icono.ico')}}">
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <nav class="navbar1 navbar-static-top" role="navigation">
          <img src="{{asset('img/logo.png')}}" class="my-logo" alt="logo"/>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span>Administrador</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
                    <span>www.incanatoit.com</span>
                  </li>
                  <li class="user-footer">
                    <div class="pull-right">
                    <a href="{{url('/logout')}}" class="btn btn-default btn-flat">Cerrar</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>

        <nav class="navbar my-navbar">
          <span>Sistema de gestión de reservas de áreas de estudio en la Biblioteca General de la UCSG</span>
        </nav>
      </header>

      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
            <li class="treeview">
               <a href="#"></a>
               <a href="#"></a>
            </li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-wrench"></i>
                <span>Mantenimiento</span>
                <i class="fa fa-angle-down pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{url('mantenimiento/areas')}}"><i class="fa fa-circle-o"></i>Áreas de estudio</a></li>
                <li><a href="{{url('mantenimiento/facultades')}}"><i class="fa fa-circle-o"></i>Facultades</a></li>
                <li><a href="{{url('mantenimiento/carreras')}}"><i class="fa fa-circle-o"></i>Carreras</a></li>
                <li><a href="{{url('mantenimiento/usuarios')}}"><i class="fa fa-circle-o"></i>Usuarios</a></li>
              </ul>
            </li>
                        
            <li class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Reservas</span>
                 <i class="fa fa-angle-down pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="compras/ingreso"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                <li><a href="compras/proveedor"><i class="fa fa-circle-o"></i> Proveedores</a></li>
              </ul>
            </li>
                                                     
          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">

                <div class="box-header with-border">
                  <i class="fa fa-wrench"></i>
                  <h3 class="box-title"><b>Mantenimiento</b></h3>
                </div>
                <!-- /.box-header -->
                    <div class="box-body">
                  	   <div class="row">
	                  	    <div class="col-md-12">
		                          <!--Contenido-->
                              @yield('contenido')
		                          <!--Fin Contenido-->
                          </div>
                       </div>
                  	</div>

              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
      
    <!-- jQuery 2.1.4 -->
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/app.min.js')}}"></script>
    
  </body>
</html>