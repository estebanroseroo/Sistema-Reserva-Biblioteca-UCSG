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
    <link href=" https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>#loader{visibility:hidden;}</style>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-standalone.css')}}">
    <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <nav class="navbar1" role="navigation">
          <img src="{{asset('img/logo.png')}}" class="my-logo" alt="logo"/>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="user-menu">
                <a href="#" class="dropdown-toggle my-div" data-toggle="dropdown">
                  <span><b>{{ Auth::user()->name }}</b></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="treeview-menu">
                    <a href="{{URL::action('ContrasenaController@edit', Auth::user()->id)}}"><i class="fa fa-user"></i><span>Cambiar contraseña</span></a>
                    <a href="{{url('/logout')}}"><i class="fa fa-power-off"></i><span>Cerrar Sesión</span></a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>
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
                <li><a href="{{url('mantenimiento/roles')}}"><i class="fa fa-circle-o"></i>Roles</a></li>
                <li><a href="{{url('mantenimiento/horarios')}}"><i class="fa fa-circle-o"></i>Horarios</a></li>
              </ul>
            </li>
                        
            <li class="treeview">
              <a href="#">
                <i class="fa fa-university"></i>
                <span>Reserva</span>
                 <i class="fa fa-angle-down pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{url('operacion/adminreservas')}}"><i class="fa fa-circle-o"></i>Reservas pendientes</a></li>
                <li><a href="{{url('operacion/reservasconfirmadas')}}"><i class="fa fa-circle-o"></i>Reservas confirmadas</a></li>
                <li><a href="{{url('operacion/adminreservas/create')}}"><i class="fa fa-circle-o"></i>Nueva reserva</a></li>
                <li><a href="{{url('operacion/consultas')}}"><i class="fa fa-circle-o"></i>Consulta QR</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>Perfil</span>
                 <i class="fa fa-angle-down pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{URL::action('ContrasenaController@edit', Auth::user()->id)}}"><i class="fa fa-circle-o"></i>Cambiar contraseña</a></li>
              </ul>
            </li>
                                                     
          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
        <nav class="my-navbar">
          <span>Sistema de gestión de reservas de áreas de estudio</span>
        </nav>
        <section class="content">
          <div class="row">
            <div class="col-md-12">

                              <!--Contenido-->
                              @yield('contenido')
                              <!--Fin Contenido-->
                         
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
    <script src="{{asset('js/my-select.js')}}"></script>
    <script src="{{asset('js/my-select-usuario.js')}}"></script>

  </body>
</html>
