@extends(Auth::user()->idtipousuario==1 ? 'layouts.admin' : 'layouts.gestor')
@section('contenido')
<div class="box">
<div class="box-header with-border">
<i class="fa fa-university"></i>
<h3 class="box-title"><b>Reserva</b></h3>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="row">
<div class="col-md-12">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<h2>Consulta QR</h2>
{!! Form::open(array('url'=>'operacion/consultas/create','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="input-group">
<input type="text" class="form-control" name="cod" placeholder="Búsqueda por código QR" value="{{$cod}}" id="cod" onkeypress="return alpha(event);" oncopy="return false" onpaste="return false" maxlength="10">
<span class="input-group-btn">
<button type="submit" class="my-button" onclick="return validateForm();"><i class="fa fa-search"> <b>Buscar</b></i></button>
</span>
</div>
<div class="input-group">
<label id="mensaje" style="font-size: 14px; color:red; font-weight:bold;">{{$sms}}</label>
</div>
{{Form::close()}}
</div>
</div>

<div class="container-fluid header_se">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
      @if(Auth::user()->email)
          <div class="row">
            <div id="reader" class="center-block" style="width:300px;height:250px">
            </div>
          </div>
          <div class="row">
            <div id="message" class="text-center">
            </div>
          </div>
      @endif
       </div>
      <div class="col-md-2">
      </div>
</div>

</div>
</div>
</div>
</div><!-- /.box -->
<script type="text/javascript" src="{{ URL::asset('/qr_login/jsqrcode-combined.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/qr_login/html5-qrcode.min.js') }}"></script>

<script>
   $('#reader').html5_qrcode(function(data){
        $('#message').html('<span class="text-success send-true"><b>Escaneando...</b></span>');
        if (data!='') {
          $.ajax({
                    type: "POST",
                    cache: false,
                    url : "{{action('QrLoginController@check')}}",
                    data: {"_token": "{{ csrf_token() }}",data:data},
                        success: function(data) {
                            var variable=data;
                            var url = '{{ URL::action("QrLoginController@create", "cod=variable") }}';
                            url = url.replace('variable', variable);
                            $(location).attr('href',url);  
                          }
                })
        }
    },
    function(error){
       $('#message').html('<b>Esperando código QR existente</b>'  );
    }, function(videoError){
       $('#message').html('<span class="text-danger camera_problem"><b>Hubo un problema con su cámara</b></span>');
    }
);
    function alpha(e) {
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
    }
    function validateForm() {
    if(document.getElementById('cod').value==""){
        document.getElementById('mensaje').innerHTML = 'Ingrese un código QR';
        return false;
    }
  }
</script>
@endsection
