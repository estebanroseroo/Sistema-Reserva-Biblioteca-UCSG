$(document).ready(function() {
    $('select[name="carrechart"]').append('<option value="0">N/A</option>');
    var ID = $('select[name="facuchart"] option:selected').val();
        if(ID) {
            $.ajax({
                url: '/carre/reserva/get/'+ID ,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                    },
                success:function(data) {
                    $('select[name="carrechart"]').empty();
                    $('select[name="carrechart"]').append('<option value="0">N/A</option>');
                    var idcar=$('select[name="carrechart"] option:selected').val();
                    if(ID!=0 && ID!=999){
                    $('select[name="carrechart"]').append('<option value="999">Todas</option>');
                    }
                    $.each(data, function(key, value){
                        if(idcar==key){
                        $('select[name="carrechart"]').append('<option value="'+ key +'" selected>' + value + '</option>');
                        }
                        else{
                        $('select[name="carrechart"]').append('<option value="'+ key +'">' + value + '</option>'); 
                        }
                        });
                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
            $('select[name="carrechart"]').empty();
        }
    

 	$('select[name="carrechart"]').append('<option value="0">N/A</option>');
    $('select[name="facuchart"]').on('change', function(){
    var ID = $(this).val();
        if(ID) {
            $.ajax({
                url: '/carre/get/'+ID ,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                    },
                success:function(data) {
                    $('select[name="carrechart"]').empty();
                    $('select[name="carrechart"]').append('<option value="0">N/A</option>');
                    var idcar=$('select[name="carrechart"] option:selected').val();
        			if(ID!=0 && ID!=999){
        			$('select[name="carrechart"]').append('<option value="999">Todas</option>');
        			}
                    $.each(data, function(key, value){
                        if(idcar==key){
                        $('select[name="carrechart"]').append('<option value="'+ key +'" selected>' + value + '</option>');
                        }
                        else{
                        $('select[name="carrechart"]').append('<option value="'+ key +'">' + value + '</option>'); 
                        }
                        });
                    },
                complete: function(){ 
                    $('#loader').css("visibility", "hidden"); 
                    }
                    });
                } 
        else { 
        	$('select[name="carrechart"]').empty();
        }
    });      
});