 $(document).ready(function() {
    $('select[name="idfacultad"]').on('change', function(){
        var ID = $(this).val();
        $('#idcarrera').removeAttr('disabled');
        if(ID) {
            $.ajax({
                url: '/states/get/'+ID,
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
        } else {
            $('select[name="idcarrera"]').empty();
        }
    });
});