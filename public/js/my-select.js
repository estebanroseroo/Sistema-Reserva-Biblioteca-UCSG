$(document).ready(function() {

        var ID = $('select[name="idfacultad"] option:selected').val();
        if(ID) {
            $.ajax({
                url: '/get/'+ID ,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                    },
                success:function(data) {
                    $('select[name="idcarrera"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="idcarrera"]').append('<option value="'+ key +'">' + value + '</option>');
                        });
                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
            $('select[name="idcarrera"]').empty(); 
            }

        $('select[name="idfacultad"]').on('change', function(){
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
                    $('select[name="idcarrera"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="idcarrera"]').append('<option value="'+ key +'">' + value + '</option>');
                        });
                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
            $('select[name="idcarrera"]').empty(); 
            }
        });
    
        $('select[name="idfacultadedit"]').on('change', function(){
        var ID = $(this).val();
        $('#idcarreraedit').removeAttr('disabled');
        if(ID) {
            $.ajax({
                url: '/get/'+ID ,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                    },
                success:function(data) {
                    $('select[name="idcarreraedit"]').empty();
                    $.each(data, function(key, value){
                        $('select[name="idcarreraedit"]').append('<option value="'+ key +'">' + value + '</option>');
                        });
                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
            $('select[name="idcarreraedit"]').empty(); 
            }
        });
});