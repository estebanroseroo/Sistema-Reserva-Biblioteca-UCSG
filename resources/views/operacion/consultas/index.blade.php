@extends ('layouts.admin')
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
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
<h2>Consulta QR</h2>
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
      @else
        <h1>Hallo!</h1>
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
                          if (data!=0) {
                            var variable=data;
                            var url = '{{ URL::action("QrLoginController@create", "cod=variable") }}';
                            url = url.replace('variable', variable);
                            $(location).attr('href',url);  
                          }
                          else{
                          return $('#message').html('<span class="text-danger camera_problem"><b>EL CÓDIGO QR YA EXPIRÓ</b></span>');   
                          }
                      }
                  })
        }else{return confirm('Por favor muestre el código QR a la cámara');}
    },
    function(error){
       $('#message').html('<b>Esperando código QR...</b>'  );
    }, function(videoError){
       $('#message').html('<span class="text-danger camera_problem"><b>Hubo un problema con su cámara</b></span>');
    }
);
</script>
@endsection
