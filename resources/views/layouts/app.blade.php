<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Universidad Católica de Santiago de Guayaquil:Entrar al sitio</title>
<<<<<<< HEAD
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/my-select-register.js') }}"></script>
=======
<<<<<<< HEAD
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/my-select-register.js') }}"></script>
=======
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
>>>>>>> 6f1b6b1aac6c00ef1c47b5b3af997e166c257e80
>>>>>>> 54433a6b974e1b2ca95c2daa453f89fd9e663aab
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href=" https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>#loader{visibility:hidden;}
   </style>
</head>
<body class="my-bg">
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
