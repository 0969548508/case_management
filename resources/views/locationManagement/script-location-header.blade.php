<script type="text/javascript">
    $("#form-edit-name-location").hide();
    $("#form-edit-abn-location").hide();

    $("#content-name-icon").click(function(){
        $("#form-edit-name-location").show();
        $("h1.h1-location-name").hide();
        $("#content-name-icon").hide();
        $(".custom-status-primary").hide();
        $(".custom-active").hide();
        $("#form-edit-abn-location").hide();
        $("#content-abn-icon").show();
        $("b.b-abn").show();
    });

    $("#content-abn-icon").click(function(){
        $("#form-edit-abn-location").show();
        $("b.b-abn").hide();
        $("#content-abn-icon").hide();
        $("#form-edit-name-location").hide();
        $(".custom-status-primary").show();
        $(".custom-active").show();
        $("h1.h1-location-name").show();
        $("#content-name-icon").show();
    });

    $('#cancel-edit-name-location').click(function() {
        $("#form-edit-name-location").hide();
        $("h1.h1-location-name").show();
        $(".custom-status-primary").show();
        $(".custom-active").show();
        $("#content-name-icon").show();
    });

    $('#cancel-abn-location').click(function() {
        $("#form-edit-abn-location").hide();
        $("b.b-abn").show();
        $("#content-abn-icon").show();
    });

    $('#edit-name-location').click(function() {
        $('#edit-name-location').attr("disabled", true);
        var url = "{{ route('updateTitleLocation', ['clientId'=>$clientDetail['id'], 'locationId'=>$locationDetail['id']]) }}";
        var data = {
            "name" : $('input#location-name').val(),
            "column" : "name"
        };
        var result = sendAjax(url, data);

        if (result == true) {
            $('h1.h1-location-name').text($('input#location-name').val());
            $('span.nav-title').text($('input#location-name').val());
            $("#form-edit-name-location").hide();
            $("h1.h1-location-name").show();
            $(".custom-status-primary").show();
            $(".custom-active").show();
            $("#content-name-icon").show();
        } else {
            $('#edit-name-location').attr("disabled", false);
        }
    });

    $('#edit-abn-location').click(function() {
        $('#edit-abn-location').attr("disabled", true);
        var url = "{{ route('updateTitleLocation', ['clientId'=>$clientDetail['id'], 'locationId'=>$locationDetail['id']]) }}";
        var data = {
            "abn" : $('input#abn-name').val(),
            "column" : "abn"
        };
        var result = sendAjax(url, data);

        if (result == true) {
            $('b.b-abn').text('ABN: '+$('input#abn-name').val());
            $('#form-edit-abn-location').hide();
            $("b.b-abn").show();
            $("#content-abn-icon").show();
        } else {
            $('#edit-abn-location').attr("disabled", false);
        }
    });

    function sendAjax(url, data) {
        var result;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: url,
            data: data,
            method: 'get',
            async: false,
            success: function(response){
                if (typeof (response.errors) !== 'undefined') {
                    result = false;
                    toastr.error(response.errors);
                } else {
                    result = true;
                    toastr.success(response.success);
                }
            }
        });

        return result;
    }

    // Change status location
    $('.deactivate-location').click(function() {
        var url = "{{ route('updateTitleLocation', ['clientId'=>$clientDetail['id'], 'locationId'=>$locationDetail['id']]) }}";
        var data = {
            'status': $('input#status-location').val(),
            "column" : "status"
        };
        sendAjaxStatus(url, data);
    });

    function sendAjaxStatus(url, data) {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: url,
            data: data,
            method: 'get',
            async: false,
            success: function(response){
                if (typeof (response.errors) !== 'undefined') {
                    toastr.error(response.errors);
                } else {
                    var status = $('input#status-location').val();
                    toastr.success(response.success);
                    if (status == 1) {
                        $("a.deactivate-location").text("Activate Location");
                        $("a.deactivate-location").css("color",'#23282c');
                        $("div.div-status-location").text("Inactive");
                        $("div.div-status-location").addClass('custom-inactive');
                        $("div.div-status-location").removeClass('custom-active');
                        $('input#status-location').val(0);
                    } else {
                        $("a.deactivate-location").text("Deactivate Location");
                        $("a.deactivate-location").css("color",'#FF0000');
                        $("div.div-status-location").text("Active");
                        $("div.div-status-location").addClass('custom-active');
                        $("div.div-status-location").removeClass('custom-inactive');
                        $('input#status-location').val(1);
                    }
                }
            }
        });
    }
    // End change status location

    // Move location to trash
    $('.move-location-to-trash').click(function() {
        $('#modal-move-location-to-trash').modal('show');
    });
</script>

<script type="text/javascript">
    $('#edit-location-info').click(function () {
        $('#modal-edit-location').modal('show');
    });

    $('#edit-company-info').click(function () {
        $('#modal-edit-company-info').modal('show');
    });
</script>