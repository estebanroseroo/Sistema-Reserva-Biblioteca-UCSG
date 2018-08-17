 $(document).ready(function() {

        var idtipousu=$('select[name="idtipousuariousu"] option:selected').val();
        if(idtipousu=='1'){
        $('select[name="idfacultadusu"]').empty();
        $('select[name="idfacultadusu"]').append('<option value="N/A">N/A</option>');
        $('select[name="idcarrerausu"]').empty();
        $('select[name="idcarrerausu"]').append('<option value="N/A">N/A</option>');
        }

        $('select[name="idtipousuariousu"]').on('change', function(){
        var idtipousu = $(this).val();
        if(idtipousu>'2') {
            $.ajax({
                url: '/facu/get/'+idtipousu ,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                    },
                success:function(data) {
                    $('select[name="idfacultadusu"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="idfacultadusu"]').append('<option value="'+ key +'">' + value + '</option>');
                        });
                    
                    var ID = $('select[name="idfacultadusu"] option:selected').val();
                    if(ID) {
                        $.ajax({
                        url: '/get/'+ID ,
                        type:"GET",
                        dataType:"json",
                        beforeSend: function(){
                        $('#loader').css("visibility", "visible");
                        },
                        success:function(data) {
                        $('select[name="idcarrerausu"]').empty();
                        $.each(data, function(key, value){
                        $('select[name="idcarrerausu"]').append('<option value="'+ key +'">' + value + '</option>');
                        });
                        },
                        complete: function(){ 
                        $('#loader').css("visibility", "hidden"); 
                        }
                        });
                    } 
                    else { 
                    $('select[name="idcarrerausu"]').empty(); 
                    }

                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
            $('select[name="idfacultadusu"]').empty();
            $('select[name="idfacultadusu"]').append('<option value="N/A">N/A</option>');
            $('select[name="idcarrerausu"]').empty();
            $('select[name="idcarrerausu"]').append('<option value="N/A">N/A</option>');
            }
        });

        $('select[name="idfacultadusu"]').on('change', function(){
        var ID = $(this).val();
        if(ID) {
            $.ajax({
                url: '/get/'+ID ,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                    },
                success:function(data) {
                    $('select[name="idcarrerausu"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="idcarrerausu"]').append('<option value="'+ key +'">' + value + '</option>');
                        });
                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
            $('select[name="idcarrerausu"]').empty(); 
            }
        });

        
});