<?php
    $typeLicense = array('home', 'office', 'others');
?>
<div class="row">
    @foreach($licenseInfo as $license)
        <?php
            $license['expiration'] = date('d/m/Y', strtotime($license['expiration']));
        ?>
        <div class="col-md-6 col-sm-12 col-form-label text-md-left">
            <div class="card card-address">
                <div class="card-body pb-46">
                    <div class="font-bold">
                        <b>License Type: </b>{{ $license['type'] }}
                        <span class="cursor-pointer position-absolute-pen span-collapse-edit-license" data-toggle="collapse" data-target="#edit-license-{{ $license['id'] }}" id="span-edit-license" onclick="showEditFormLicense('{{ $license['id'] }}', '{{ $license['state'] }}')">
                            <img src="/images/btn_pen.png" data-id="{{ $license['id'] }}">
                        </span>&nbsp;&nbsp;
                        <span class="cursor-pointer position-absolute-delete" id="span-delete-license">
                            <img src="/images/img-delete.png" id="{{ $license['id'] }}">
                        </span>
                    </div>
                    <div class="pt-2">
                        <b>Country: </b>{{ $license['country'] }} <br>
                        <b>State: </b>{{ $license['state'] }} <br>
                        <b>License number: </b>{{ $license['number'] }} <br>
                        <b>Expiration: </b>{{ $license['expiration'] }}
                    </div>

                    <div class="row m-0">
                        @foreach($license['image_info'] as $image)
                            <div class="pt-2 pr-2" id="load-iamge">
                                <img width="60" height="40" src="{{ $image['image'] }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- edit license -->
                    <div class="row collapse collapse-edit-license mt-3" id="edit-license-{{ $license['id'] }}" data-id="">
                        <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                            <div><b><i style="text-decoration:underline;">Edit form</i></b></div>
                            <div class="row bg-collapse mt-2">
                                <div class="col-md-12 col-sm-12 col-form-label text-md-left pt-3">
                                    <form action="#" method="POST" id="edit-license-form">
                                    @csrf
                                        <div class="form-group row">
                                            <label for="edit-type-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Type') }}</label>

                                            <div class="col-md-12 col-lg-9">
                                                <select class="form-control" name="edit-type-license" id="edit-type-license-{{ $license['id'] }}" onclick="checkTypeLicense('{{ $license['id'] }}')">
                                                @foreach($typeLicense as $key => $type)
                                                    @if (!in_array(strtolower($license['type']), $typeLicense) && $key == 0)
                                                        <option selected value="{{ $license['type'] }}">{{ ucwords($license['type']) }}</option>
                                                    @endif
                                                    @if($license['type'] == $type)
                                                        <option selected value="{{ $type }}">{{ ucwords($type) }}</option>
                                                    @else
                                                        <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                                    @endif
                                                @endforeach
                                                </select>
                                                <input type="text" id="input-type-license-update-{{ $license['id'] }}" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input license type">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="edit-country-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Country') }}</label>
                                            <div class="col-md-12 col-lg-9">
                                                <select class="form-control" name="edit-country-license" id="edit-country-license-{{ $license['id'] }}" onchange="checkCountryLicense('{{ $license['id'] }}')">
                                                    @foreach($listCountries as $country)
                                                        @if ($country->name == $license['country'])
                                                            <option selected value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @else
                                                            <option value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="edit-state-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('State') }}</label>
                                            <div class="col-md-12 col-lg-9">
                                                <select class="form-control" name="edit-state-license" id="edit-state-license-{{ $license['id'] }}">
                                                    <option value="{{ $license['state'] }}">{{ $license['state'] }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="edit-number-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Number') }}</label>
                                            <div class="col-md-12 col-lg-9">
                                                <input id="edit-number-license-{{ $license['id'] }}" type="text" class="form-control" name="edit-number-license" required autocomplete="edit-number-license" placeholder="Input license number" value="{{ $license['number'] }}" oninput="this.value = this.value.replace(/[^0-9-.,]/g, '').split(/\-/).slice(0, 2).join('-')">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="edit-expiration" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Expiration') }}</label>

                                            <div class="col-md-12 col-lg-9 inner-addon right-addon">
                                                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                                <input id="edit-expiration-{{ $license['id'] }}" type="text" class="form-control edit-expiration" name="edit-expiration" required readonly style="background-color: #fff;" autocomplete="edit-expiration" placeholder="DD/MM/YY" value="{{ $license['expiration'] }}">
                                            </div>
                                        </div>

                                        <div class="form-group row show-hide-edit-image-{{ $license['id'] }} ml-0">
                                            @foreach($license['image_info'] as $image)
                                                <div class="pt-2 pr-3">
                                                    <img width="60" height="40" src="{{ $image['image'] }}">
                                                    <img src="/images/img_remove.png" data-id="{{ $license['id'] }}" class="custom-img-remove-for-dropzone img-remove-license" data-img-id="{{ $image['id'] }}" data-name="{{ $image['image'] }}">
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xl-12 p-0">
                                                            <div class="needsclick p-0" id="edit-upload-image-for-license-{{ $license['id'] }}" enctype="multipart/form-data">
                                                                <div class="dz-message needsclick pt-4"><h3>Drop files here or click to upload.<h3><br><span class="note needsclick"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="preview-template-{{ $license['id'] }}" style="display: none;">
                                                            <div class="dz-preview dz-file-preview">
                                                                <div class="dz-image"><img data-dz-thumbnail=""></div>
                                                                <div class="dz-details">
                                                                    <div class="dz-size"><span data-dz-size=""></span></div>
                                                                    <div class="dz-filename"><span data-dz-name=""></span></div>
                                                                </div>
                                                                <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                                                <div><img data-dz-remove /></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-edit-license" onclick="cancelEditLicense('{{ $license['id'] }}')">
                                                    {{ __('Cancel') }}
                                                </button>
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-edit-license" btn-edit-id="{{ $license['id'] }}" onclick="editLicense('{{ $license['id'] }}')">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@can('edit users')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <a href="" data-toggle="collapse" data-target="#add-license" id="collapse-add-license">
                <img src="/images/btn_plus.png">
                <i class="custom-font">Add licences</i>
            </a>
        </div>
    </div>
@endcan

<div class="row collapse" id="add-license">
    <div class="col-md-6 col-sm-12 col-form-label text-md-left">
        <div class="row bg-collapse">
            <div class="col-md-12 col-sm-12 col-form-label text-md-left pt-3">
                <form action="{{ route('createUserLicense', $detailUser['id']) }}" method="POST" id="add-license-form">
                @csrf
                    <div class="form-group row">
                        <label for="type-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Type') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select class="form-control" name="type-license" id="type-license">
                                @foreach($typeLicense as $type)
                                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                @endforeach
                            </select>
                            <input type="text" id="input-type-license" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input license type">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="country-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Country') }}</label>
                        <div class="col-md-12 col-lg-9">
                            <select class="form-control" name="country-license" id="country-license">
                                <option value="">Choose a country</option>
                                @foreach($listCountries as $country)
                                    <option value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="state-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('State') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select class="form-control" name="state-license" id="state-license">
                                <option value="">Choose a state</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="number-license" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Number') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="number-license" type="text" class="form-control" name="number-license" required autocomplete="number-license" placeholder="Input license number" oninput="this.value = this.value.replace(/[^0-9-.,]/g, '').split(/\-/).slice(0, 2).join('-')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="expiration" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Expiration') }}</label>

                        <div class="col-md-12 col-lg-9 inner-addon right-addon">
                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                            <input id="expiration" type="text" class="form-control" name="expiration" required readonly style="background-color: #fff;" autocomplete="expiration" placeholder="DD/MM/YY">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 col-lg-12">
                            <div class="container">
                                <div class="row">
                                    <div id="dropzone" class="col-xl-12 p-0">
                                        <div class="needsclick p-0" id="upload-image-for-license" enctype="multipart/form-data">
                                            <div class="dz-message needsclick pt-4"><h3>Drop files here or click to upload.<h3><br><span class="note needsclick"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="preview-template" style="display: none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-image"><img data-dz-thumbnail=""></div>
                                            <div class="dz-details">
                                                <div class="dz-size"><span data-dz-size=""></span></div>
                                                <div class="dz-filename"><span data-dz-name=""></span></div>
                                            </div>
                                            <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                            <div><img data-dz-remove /></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                            <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-license">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-license">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    @if (isset($response['alert-type']))
        @if ($response['alert-type'] == 'errors')
            toastr.error("{{ $response['message'] }}");
        @else
            toastr.success("{{ $response['message'] }}");
        @endif
    @endif

    $("#collapse-add-license").click(function () {
        $(".collapse-edit-license").collapse('hide');
    });

    $(".span-collapse-edit-license").click(function () {
        $("#add-license").collapse('hide');
    });

    $('select#type-license').change(function(){
        if ($(this).val().toLowerCase() == 'others') {
            $('input#input-type-license').removeClass('d-none');
        } else {
            $('input#input-type-license').addClass('d-none');
        }
    });

    $(function () {
        $("#expiration").datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom"
        });

        $(".edit-expiration").datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom"
        });
    });

    var myDropzone;
    var licenseId;
    var count = 0;
    var oldTypeLicense,
        oldCountryLicense,
        oldStateLicense,
        oldNumberLicense,
        oldExpiration;
    $(document).ready(function() {
        $("#upload-image-for-license").addClass("dropzone");
        Dropzone.options.myDropzone = false;
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone('#upload-image-for-license', {
            url: "{{ route('createUserLicense', $detailUser['id']) }}",
            method: "POST",
            previewTemplate: document.querySelector('#preview-template').innerHTML,
            parallelUploads: 10,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 3,
            maxFiles: 10,
            addRemoveLinks: true,
            dictRemoveFile: 'Remove image',
            autoProcessQueue:false,
            uploadMultiple: true,
            cache: false,
            init: function() {
                this.on("addedfile", function(file, dataUrl) {
                    if (typeof file.previewElement !== "undefined") {
                        this.removeFile(file.previewElement);
                    }
                }),
                this.on("thumbnail", function (file, dataUrl) {
                    if (file.previewElement) {
                        file.previewElement.classList.remove("dz-file-preview");
                        var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                        for (var i = 0; i < images.length; i++) {
                            var thumbnailElement = images[i];
                            thumbnailElement.alt = file.name;
                            thumbnailElement.src = dataUrl;
                        }
                        setTimeout(function() { file.previewElement.classList.add("dz-image-preview"); }, 1);
                    }
                }),
                this.on("sendingmultiple", function(file, xhr, formData) {
                    formData.append("_token", "{{ csrf_token() }}");
                    formData.append("type-license", ($('select#type-license').val().toLowerCase() == 'others') ? $('input#input-type-license').val() : $('select#type-license').val());
                    formData.append("state-license", $("#state-license").val());
                    formData.append("country-license", $("#country-license").val());
                    formData.append("number-license", $("#number-license").val());
                    formData.append("expiration", $("#expiration").val());
                    var data = $('#upload-image-for-license').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                }),
                this.on("successmultiple", function(file, response) {
                    $("#btn-save-license").removeClass("disabled");
                    $("div#show-license").html(response.html);
                }),
                this.on("errormultiple", function(file, message, xhr) {
                    this.removeFile(file);
                    toastr.error("Fail to create license");
                });
            }
        });

        $('#btn-save-license').click ( function (e) {
            e.preventDefault();
            var typeLicense = ($('select#type-license').val().toLowerCase() == 'others') ? $('input#input-type-license').val() : $('select#type-license').val(),
                countryLicense = $("#country-license").val(),
                numberLicense = $("#number-license").val(),
                expirationLicense = $("#expiration").val();

            if (!typeLicense.trim()) {
                return toastr.error('Input type license.');}
            if (typeLicense == '' || typeLicense == undefined) {$('select[name="type-license"]')[0].focus(); return toastr.error("Please fill out this field!");}
            if (countryLicense == '' || typeLicense == undefined) {$('select[name="country-license"]')[0].focus(); return toastr.error("Please fill out this field!");}
            if (numberLicense == '') {$('input[id="number-license"]')[0].focus(); return toastr.error("Please fill out this field!");}
            if (expirationLicense == '') {$('input[id="expiration"]')[0].focus(); return toastr.error("Please fill out this field!");}

            if (myDropzone.files.length > 0) {
                myDropzone.processQueue();
            } else {
                return toastr.error("Please choose at leat one picture!");
            }
        });
    });

    // edit license
    function checkTypeLicense(licenseId) {
        var changeTypeLicense = $("#edit-type-license-"+licenseId).val();
        $("#edit-type-license-"+licenseId+" option[value="+changeTypeLicense+"]").prop("selected", true);
        if ($("#edit-type-license-" + licenseId).val().toLowerCase() == 'others') {
            $('input#input-type-license-update-' + licenseId).removeClass('d-none');
        } else {
            $('input#input-type-license-update-' + licenseId).addClass('d-none');
        }
    }

    function checkCountryLicense(licenseId) {
        var countryId = $("#edit-country-license-"+licenseId).find(':selected').attr('data-value');

        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#edit-state-license-"+licenseId).empty();
                        $("#edit-state-license-"+licenseId).append('<option value="">Choose a state</option>');
                        $.each(response, function(key, value) {
                            $("#edit-state-license-"+licenseId).append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    }

    function editLicense(licenseId) {
        url = "{{ route('editLicense', $detailUser['id']) }}";
        let data = {
            'id': licenseId,
            'type': ($('select#edit-type-license-' + licenseId).val().toLowerCase() == 'others') ? $('input#input-type-license-update-' + licenseId).val() : $('select#edit-type-license-' + licenseId).val(),
            'country': $("#edit-country-license-"+licenseId).val(),
            'state': $("#edit-state-license-"+licenseId).val(),
            'number': $("#edit-number-license-"+licenseId).val(),
            'expiration': $("#edit-expiration-"+licenseId).val(),
            'file': '',
        }
        if (!data['type'].trim()) {
            return toastr.error('Input type license.');
        } else {
            data['type'] = data['type'].toLowerCase();
        }

        if (editDropzone.files.length > 0) {
            editDropzone.processQueue();
        } else {
            sendAjaxEditLicense(url, data);
        }
    }

    function sendAjaxEditLicense(url, data) {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: url,
            data: data,
            method: 'post',
            async: false,
            success: function(response){
                $("div#show-license").html(response.html);
                $('#modal-delete-license').modal('hide');
                $('.modal-backdrop.fade.show').hide();
        }});
    }

    // for add
    $("select#country-license").change(function() {
        var countryId = $("#country-license").find(':selected').attr('data-value');
        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#state-license").empty();
                        $("#state-license").append('<option value="">Choose a state</option>');
                        $.each(response, function(key, value) {
                            $("#state-license").append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });

    var editDropzone;
    function showEditFormLicense(licenseId, stateName) {
        // show hide collapse edit license
        if ($("div.collapse-edit-license").hasClass("show")) {
            $(".collapse-edit-license.show").removeClass("show");
        }
        // show hide collapse edit license

        // data for cancel edit license
        oldTypeLicense = $("#edit-type-license-"+licenseId).val();
        oldCountryLicense = $("#edit-country-license-"+licenseId).val();
        oldStateLicense = $("#edit-state-license-"+licenseId).val();
        oldNumberLicense = $("#edit-number-license-"+licenseId).val();
        oldExpiration = $("#edit-expiration-"+licenseId).val();
        // end data for cancel edit license

        var countryId = $("#edit-country-license-"+licenseId).find(":selected").attr("data-value");
        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);

        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response) {
                    if (response) {
                        $("#edit-state-license-"+licenseId).empty();
                        $("#edit-state-license-"+licenseId).append('<option value="">Choose a state</option>');
                        $.each(response, function(key, value) {
                            if (value['name'] == stateName) {
                                $("#edit-state-license-"+licenseId).append('<option value="'+value['name']+'" selected data-value="'+value['id']+'">'+value['name']+'</option>');
                            } else {
                                $("#edit-state-license-"+licenseId).append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                            }
                        });
                    }
                }
            });
        }

        // dropzone for edit
        $(document).ready(function() {
            $("#edit-upload-image-for-license-"+licenseId).addClass("dropzone");
            Dropzone.autoDiscover = false;
            editDropzone = new Dropzone('#edit-upload-image-for-license-'+licenseId, {
                url: "{{ route('editLicense', $detailUser['id']) }}",
                method: "POST",
                previewTemplate: document.querySelector('#preview-template-'+licenseId).innerHTML,
                parallelUploads: 10,
                thumbnailHeight: 120,
                thumbnailWidth: 120,
                maxFilesize: 3,
                maxFiles: 10,
                addRemoveLinks: true,
                dictRemoveFile: 'Remove image',
                autoProcessQueue:false,
                uploadMultiple: true,
                cache: false,
                init: function() {
                    this.on("addedfile", function(file, dataUrl) {
                        if (typeof file.previewElement !== "undefined") {
                            this.removeFile(file.previewElement);
                        }
                    }),
                    this.on("thumbnail", function (file, dataUrl) {
                        if (file.previewElement) {
                            file.previewElement.classList.remove("dz-file-preview");
                            var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                            for (var i = 0; i < images.length; i++) {
                                var thumbnailElement = images[i];
                                thumbnailElement.alt = file.name;
                                thumbnailElement.src = dataUrl;
                            }
                            setTimeout(function() { file.previewElement.classList.add("dz-image-preview"); }, 1);
                        }
                    }),
                    this.on("sendingmultiple", function(file, xhr, formData) {
                        formData.append("_token", "{{ csrf_token() }}");
                        formData.append("id", licenseId);
                        formData.append("type", ($('select#edit-type-license-' + licenseId).val().toLowerCase() == 'others') ? $('input#input-type-license-update-' + licenseId).val() : $('select#edit-type-license-' + licenseId).val());
                        formData.append("country", $("#edit-country-license-"+licenseId).val());
                        formData.append("state", $("#edit-state-license-"+licenseId).val());
                        formData.append("number", $("#edit-number-license-"+licenseId).val());
                        formData.append("expiration", $("#edit-expiration-"+licenseId).val());
                        var data = $('#edit-upload-image-for-license-'+licenseId).serializeArray();
                        $.each(data, function(key, el) {
                            formData.append(el.name, el.value);
                        });
                    }),
                    this.on("successmultiple", function(file, response) {
                        $("div#show-license").html(response.html);
                    }),
                    this.on("errormultiple", function(file, message, xhr) {
                        this.removeFile(file);
                        toastr.error("Fail to create license");
                    });
                }
            });
        });
    }

    // for cancel button
    function cancelEditLicense(licenseId) {
        $("#edit-license-"+licenseId).collapse('hide');
        $("#edit-type-license-"+licenseId).val(oldTypeLicense);
        $("#edit-country-license-"+licenseId).val(oldCountryLicense);
        $("#edit-state-license-"+licenseId).val(oldStateLicense);
        $("#edit-number-license-"+licenseId).val(oldNumberLicense);
        $("#edit-expiration-"+licenseId).val(oldExpiration);
        editDropzone.removeAllFiles(true);
        myDropzone.removeAllFiles(true);
    }

    $("#btn-cancel-save-license").click(function () {
        $("#add-license").collapse('hide');
        $("#type-license").val('');
        $("#country-license").val('');
        $("#state-license").val('');
        $("#number-license").val('');
        $("#expiration").val('');
        myDropzone.removeAllFiles(true);
        editDropzone.removeAllFiles(true);
    });

    $('.img-remove-license').click(function (e) {
        e.preventDefault();
        let licenseId = $(this).attr('data-id');
        let imgId = $(this).attr('data-img-id');
        let imgLicenseName = $(this).attr('data-name');
        let url = "{{ route('deleteImageLicense', $detailUser['id']) }}";
        let data = {
            "licenseId": licenseId,
            'imgId': imgId,
            'imgLicenseName': imgLicenseName,
        };

        sendAjaxDeleteImageOfLicense(url, data);
    });

    function sendAjaxDeleteImageOfLicense(url, data) {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: url,
            data: data,
            method: 'post',
            async: false,
            success: function(response){
                $("div#show-license").html(response.html);
                $('#modal-delete-license').modal('hide');
                $('.modal-backdrop.fade.show').hide();
        }});
    }
</script>