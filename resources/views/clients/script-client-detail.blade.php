<script>
    $("#form-edit-name-client").hide();
    $("#form-edit-abn-client").hide();

    @if (!empty($clientDetail['image']))
        $(".imgUp").find('.member-client-detail').css("background-image", "url({{ $clientDetail['image'] }})");
        $('label.btn-upload-avata-client').css({'margin-top' : '92px'});
        $('span.member-initials-client-detail').hide();
    @endif

    // upload images
    $(function() {
        $(document).on("change", ".uploadFile", function() {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return;

            if (/^image/.test( files[0].type)) {
                var reader = new FileReader();
                reader.readAsDataURL(files[0]);

                reader.onloadend = function(){
                    uploadFile.closest(".imgUp").find('.member-client-detail').css("background-image", "url(" + this.result + ")");
                }

                $('label.btn-upload-avata-client').css({'margin-top' : '92px'});
                $('span.member-initials-client-detail').hide();

                $('form#upload-image-form').trigger('submit');
            }
        });

        $('form#upload-image-form').submit(function(e) {
            e.preventDefault();
            var url = "{{ route('updateImageClient', $clientDetail['id']) }}";
            var formData = new FormData(this);
            formData.append("column", "image");

            sendAjaxEditImage(url, formData);
        });
    });

    $("#content-name-icon").click(function(){
        $('#edit-name-client').attr("disabled", false);
        $("#form-edit-name-client").show();
        $("h1.content-name-client").hide();
        $('#cancel-edit-abn-client').trigger('click');
    });

    $("#content-abn-icon").click(function(){
        $('#edit-abn-client').attr("disabled", false);
        $("#form-edit-abn-client").show();
        $("h1.content-abn-client").hide();
        $('#cancel-edit-name-client').trigger('click');
    });

    $('#cancel-edit-name-client').click(function() {
        $('input#client-name').val($('span#content-name-client').text());
        $('#form-edit-name-client').hide();
        $("h1.content-name-client").show();
    });

    $('#cancel-edit-abn-client').click(function() {
        $('input#client-abn').val($('span#content-abn-client').text());
        $('#form-edit-abn-client').hide();
        $("h1.content-abn-client").show();
    });

    $('#edit-name-client').click(function() {
        $('#edit-name-client').attr("disabled", true);
        var url = "{{ route('updateContentClient', $clientDetail['id']) }}";
        var data = {
            "name" : $('input#client-name').val(),
            "column" : "name"
        };
        var result = sendAjax(url, data);

        if (result == true) {
            $('span#content-name-client').text($('input#client-name').val());
            $('span.nav-title').text($('input#client-name').val());
            $('span.member-initials-client-detail').text($('input#client-name').val()[0]);
            $('#form-edit-name-client').hide();
            $("h1.content-name-client").show();
        } else {
            $('#edit-name-client').attr("disabled", false);
        }
    });

    $('#edit-abn-client').click(function() {
        $('#edit-abn-client').attr("disabled", true);
        var url = "{{ route('updateContentClient', $clientDetail['id']) }}";
        var data = {
            "abn" : $('input#client-abn').val(),
            "column" : "abn"
        };
        var result = sendAjax(url, data);

        if (result == true) {
            $('span#content-abn-client').text($('input#client-abn').val());
            $('#form-edit-abn-client').hide();
            $("h1.content-abn-client").show();
        } else {
            $('#edit-abn-client').attr("disabled", false);
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
                if (typeof (response.error) !== 'undefined') {
                    result = false;
                    toastr.error(response.error);
                } else {
                    result = true;
                    toastr.success(response.success);
                }
            }
        });

        return result;
    }

    function sendAjaxEditImage(url, data) {
        var result;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: url,
            data: data,
            method: 'post',
            async: false,
            cache: false,
            contentType: false,
            processData: false,
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

    function deleteClient() {
        $('#modalDeleteClient').modal('show');
    }

    // Change status client
    $('.change-status').click(function() {
        var url = "{{ route('changeStatusClient', $clientDetail['id']) }}";
        var data = {
            'status': $('input#status').val(),
        };
        sendAjaxStatus(url, data);
    });

    function sendAjaxStatus(url, data) {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
        });
        jQuery.ajax({
            url: url,
            data: data,
            method: 'get',
            async: false,
            success: function(response){
                if (response['alert-type'] == 'errors') {
                    toastr.error(response['message']);
                } else {
                    var status = Number(response['status']);
                    toastr.success(response['message']);
                    if (status == 1) {
                        $("a.change-status").text("Activate Client");
                        $("div.status-client").text("Inactive");
                        $("div.status-client").addClass('inactive-client');
                        $("div.status-client").removeClass('active-client');
                        $('input#status').val(0);
                    } else {
                        $("a.change-status").text("Deactivate Client");
                        $("div.status-client").text("Active");
                        $("div.status-client").addClass('active-client');
                        $("div.status-client").removeClass('inactive-client');
                        $('input#status').val(1);
                    }
                }
            }
        });
    }
</script>