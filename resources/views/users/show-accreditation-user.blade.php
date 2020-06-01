@php
    $lastAccreditationId = '';
@endphp

@foreach($accreditationInfo as $info)
    <div class="row">
        <?php
            $dateAcquired = date('d/m/Y', strtotime($info['date_acquired']));
            $lastAccreditationId = $info['id'];
        ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-left">
            <div class="card card-address">
                <div class="card-body">
                    <div class="font-bold font-size-14">
                        {{ $info['qualification'] }}
                        <span class="cursor-pointer position-absolute-pen" data-toggle="collapse" data-target="#update-accreditations-{{ $info['id'] }}" onclick="showHideFile('{{ $info['id'] }}')">
                            <img src="/images/btn_pen.png">
                        </span>&nbsp;&nbsp;
                        <span class="cursor-pointer position-absolute-delete" onclick="deleteAccreditation('{{ route('deleteUserAccreditation', $info["id"]) }}')">
                            <img src="/images/img-delete.png" id="delete-accredit">
                        </span><br/>
                        Date acquired: {{ $dateAcquired }}
                    </div>

                    <div class="pt-1 show-hide-file-{{$info['id']}}">
                        @if (!empty($info['file']))
                            @php
                                $id = $info['id'];
                                $arrFileName = explode(",", $info['file']);
                                foreach ($arrFileName as $file) {
                                    if ($file != "") {
                                        $url = App\Repositories\Accreditations\AccreditationRepository::getLinkFile($file, $detailUser["id"], $info["id"]);
                                        echo "<a href='" . $url . "' style='text-decoration: underline;'>";
                                        echo ($file);
                                        echo "</a>";
                                        echo "<br>";
                                    }
                                }
                            @endphp
                        @endif
                    </div>
                </div>
                <div class="row collapse bg-collapse ml-2 mr-2 mb-3" id="update-accreditations-{{ $info['id'] }}">
                    <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="qualification" class="col-form-label text-md-left">{{ __('Qualification') }}</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <input id="qualification-{{ $info["id"] }}" type="text" class="form-control" name="qualification" required autocomplete="qualification" placeholder="Input qualification" value="{{ $info['qualification'] }}">
                                <input id="qualification-hide-{{ $info["id"] }}" hidden value="{{ $info['qualification'] }}">
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="date-acquired" class="col-form-label text-md-left">{{ __('Date Acquired') }}</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 inner-addon right-addon">
                                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                <input id="date-acquired-{{ $info['id'] }}" type="text" class="form-control date-picker" name="date-acquired" required autocomplete="off" placeholder="DD/MM/YYYY" value="{{ $dateAcquired }}">
                                <input id="date-acquired-hide-{{ $info['id'] }}" hidden value="{{ $dateAcquired }}">
                            </div>
                        </div>

                        <div class="pt-3 pb-2">
                            @if (!empty($info['file']))
                                @php
                                    $id = $info['id'];
                                    $arrFileName = explode(",", $info['file']);
                                    foreach ($arrFileName as $file) {
                                        if ($file != "") {
                                            $url = App\Repositories\Accreditations\AccreditationRepository::getLinkFile($file, $detailUser["id"], $info["id"]);
                                            echo "<a href='" . $url . "' style='text-decoration: underline;'>";
                                            echo ($file);
                                            echo "</a>";
                                            echo "&nbsp;&nbsp;<img style='cursor: pointer;' class='img-remove-accreditations' data-id='$id' data-name='$file' src='/images/img_remove.png'>";
                                            echo "<br>";
                                        }
                                    }
                                @endphp
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="needsclick dr1-upload-files upload-files-form files-container" id="edit-upload-files-accreditations-form-{{ $info['id'] }}" enctype="multipart/form-data">
                                    <div class="dz-message needsclick pt-0 mt-0">
                                        <span>Upload files</span><br>
                                        <span class="note needsclick"></span>
                                    </div>

                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                </div>

                                <div class="preview-container dz-preview uploaded-files">
                                    <div id="previews1-{{ $info['id'] }}" class="previews1">
                                        <div id="onyx1-dropzone-template-{{ $info['id'] }}">
                                            <div class="onyx-dropzone-info">
                                                <div class="details">
                                                    <div>
                                                        <span data-dz-name class="dr-custom-file-name"></span>
                                                    </div>
                                                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                                    <div class="actions">
                                                        <a href="#!" data-dz-remove><img src="/images/img_remove.png"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Warnings -->
                                <div id="warnings"></div>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditAccred('{{ $info["id"] }}')">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editAccred('{{$info['id']}}')">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@can('edit users')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-left">
            <a href="" data-toggle="collapse" data-target="#add-accreditations">
                <img src="/images/btn_plus.png">
                <i class="custom-font i-add-accreditations">Add Accreditations</i>
            </a>
        </div>
    </div>
@endcan

<div class="row collapse bg-collapse" id="add-accreditations">
    <div class="col-md-12 col-sm-12 col-form-label text-md-left">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <label for="qualification" class="col-form-label text-md-left">{{ __('Qualification') }}</label>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <input id="qualification" type="text" class="form-control" name="qualification" required autocomplete="qualification" placeholder="Input qualification">
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <label for="date-acquired" class="col-form-label text-md-left">{{ __('Date Acquired') }}</label>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 inner-addon right-addon">
                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                <input id="date-acquired" type="text" class="form-control date-picker" name="date-acquired" required autocomplete="off" placeholder="DD/MM/YYYY" value="">
            </div>
        </div>

        <div class="row pt-3">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="needsclick dr-upload-files upload-files-form files-container" id="upload-files-accreditations-form" enctype="multipart/form-data">
                    <div class="dz-message needsclick pt-0 mt-0">
                        <span>Upload files</span><br>
                        <span class="note needsclick"></span>
                    </div>

                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>
                </div>

                <div class="preview-container dz-preview uploaded-files">
                    <div id="previews">
                        <div id="onyx-dropzone-template">
                            <div class="onyx-dropzone-info">
                                <div class="details">
                                    <div>
                                        <span data-dz-name class="dr-custom-file-name"></span>
                                    </div>
                                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                    <div class="actions">
                                        <a href="#!" data-dz-remove><img src="/images/img_remove.png"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Warnings -->
                <div id="warnings"></div>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-accreditation">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-accreditation">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    @if (isset($response['alert-type']))
        @if ($response['alert-type'] == 'errors')
            toastr.error("{{ $response['message'] }}");
        @else
            $("#btn-cancel-save-accreditation").trigger('click');
            toastr.success("{{ $response['message'] }}");
        @endif
    @endif

    var accreditationsIdToEdit = "{{ $lastAccreditationId }}";

    // for dropzone upload file
    var onyxDropzone;
    var Onyx;
    !function ($) {
        "use strict";
        Onyx = Onyx || {};
        Onyx = {
            init: function() {
                var self = this,
                    obj;

                for (obj in self) {
                    if ( self.hasOwnProperty(obj)) {
                        var _method =  self[obj];
                        if ( _method.selector !== undefined && _method.init !== undefined ) {
                            if ( $(_method.selector).length > 0 ) {
                                _method.init();
                            }
                        }
                    }
                }
            },

            userFilesDropzone: {
                selector: 'div.dr-upload-files',
                init: function() {
                    var base = this,
                        container = $(base.selector);
                    base.initFileUploader(base, 'div.dr-upload-files');
                },
                initFileUploader: function(base, target) {
                    var previewNode = document.querySelector("#onyx-dropzone-template");
                    previewNode.id = "";
                    var previewTemplate = previewNode.parentNode.innerHTML;
                    previewNode.parentNode.removeChild(previewNode);
                    $("#upload-files-accreditations-form").addClass("dropzone");
                    onyxDropzone = new Dropzone(target, {
                        url: "{{ route('storeAccreditation', $detailUser['id']) }}",
                        method: "POST",
                        maxFiles: 10,
                        maxFilesize: 20,
                        parallelUploads: 10,
                        acceptedFiles: "application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf",
                        previewTemplate: previewTemplate,
                        previewsContainer: "#previews",
                        createImageThumbnails: true,
                        dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                        dictInvalidFileType: "You can't upload files of this type.",
                        dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},
                        autoProcessQueue:false,
                        uploadMultiple: true,
                        cache: false,
                    });

                    Dropzone.autoDiscover = false;
                    onyxDropzone.on("addedfile", function(file) {
                        $('.preview-container').css('visibility', 'visible');
                        file.previewElement.classList.add('type-' + base.fileType(file.name));
                        if (typeof file.previewElement !== "undefined") {
                            this.removeFile(file.previewElement);
                        }
                    });

                    onyxDropzone.on('sendingmultiple', function (file, xhr, formData) {
                        formData.append("_token", "{{ csrf_token() }}");
                        formData.append("qualification", $("input#qualification").val());
                        formData.append("date-acquired", $("input#date-acquired").val());
                        var data = $('#upload-files-accreditations-form').serializeArray();
                        $.each(data, function(key, el) {
                            formData.append(el.name, el.value);
                        });
                    });

                    onyxDropzone.on('successmultiple', function (file, response) {
                        $("div#show-accreditations").html(response.html);
                    });

                    onyxDropzone.on('error', function (file, response) {
                        this.removeFile(file);
                        toastr.error("Fail to create accreditation");
                    });
                },
                fileType: function(fileName) {
                    var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
                    return fileType[0];
                }
            }
        }

        $(document).ready(function() {
            Onyx.init();
        });
    }(jQuery);
    // end for dropzone upload files

    var onyxDropzone1;
    function showHideFile(accreditationsId) {
        accreditationsIdToEdit = accreditationsId;
        $(".show-hide-file-"+accreditationsId).attr('hidden', 'hidden');

        // dropzone for edit
        $(document).ready(function() {
            var previewNode = document.querySelector("#onyx1-dropzone-template-"+accreditationsIdToEdit);
            previewNode.id = "";
            var previewTemplate = previewNode.parentNode.innerHTML;
            previewNode.parentNode.removeChild(previewNode);
            $("#edit-upload-files-accreditations-form-"+accreditationsIdToEdit).addClass("dropzone");
            Dropzone.options.onyxDropzone1 = false;
            onyxDropzone1 = new Dropzone('#edit-upload-files-accreditations-form-'+accreditationsIdToEdit, {
                url: "{{ route('updateAccreditation', $detailUser['id']) }}",
                method: "POST",
                maxFiles: 10,
                maxFilesize: 20,
                parallelUploads: 10,
                acceptedFiles: "application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf",
                previewTemplate: previewTemplate,
                previewsContainer: "#previews1-"+accreditationsIdToEdit,
                createImageThumbnails: true,
                dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                dictInvalidFileType: "You can't upload files of this type.",
                dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},
                autoProcessQueue:false,
                uploadMultiple: true,
                cache: false,
                init: function() {
                    this.on("addedfile", function(file, dataUrl) {
                        $('.preview-container').css('visibility', 'visible');
                        if (typeof file.previewElement !== "undefined") {
                            this.removeFile(file.previewElement);
                        }
                    }),
                    this.on("sendingmultiple", function(file, xhr, formData) {
                        formData.append("_token", "{{ csrf_token() }}");
                        formData.append("accreditationsId", accreditationsIdToEdit);
                        formData.append("qualification", $("input#qualification-"+accreditationsIdToEdit).val());
                        formData.append("date-acquired", $("input#date-acquired-"+accreditationsIdToEdit).val());
                        var data = $('#edit-upload-files-accreditations-form-'+accreditationsIdToEdit).serializeArray();
                        $.each(data, function(key, el) {
                            formData.append(el.name, el.value);
                        });
                    }),
                    this.on("successmultiple", function(file, response) {
                        $("div#show-accreditations").html(response.html);
                    }),
                    this.on("error", function(file, message, xhr) {
                        this.removeFile(file);
                        toastr.error("Fail to update accreditation");
                    });
                }
            });
        });
        // end dropzone for edit
    }

    var oldQualification, oldDateAcquired;

    $('#btn-save-accreditation').click(function(e) {
        e.preventDefault();
        var qualification = $('input#qualification').val(),
            dateAcquired = $('input#date-acquired').val();

        if (qualification == '' || qualification == undefined) {$('input[id="qualification"]')[0].focus(); return toastr.error("Please fill out this field!");}
        if (dateAcquired == '' || dateAcquired == undefined) {$('input[id="date-acquired"]')[0].focus(); return toastr.error("Please fill out this field!");}

        if (onyxDropzone.files.length > 0) {
            onyxDropzone.processQueue();
        } else {
            return toastr.error("Please choose at least one file!");
        }
    });

    $("#btn-cancel-save-accreditation").click(function() {
        $('input#qualification').val('');
        $('input#date-acquired').val('');
        $("div#add-accreditations").removeClass('show');
        onyxDropzone.removeAllFiles();
        onyxDropzone1.removeAllFiles();
    });

    function editAccred(accredId) {
        var qualification = $('input#qualification-'+accredId).val(),
            dateAcquired = $('input#date-acquired-'+accredId).val();
        if (qualification == '' || qualification == undefined) {$('input[id=qualification-'+accredId+']')[0].focus(); return toastr.error("Please fill out this field!");}
        if (dateAcquired == '' || dateAcquired == undefined) {$('input[id=date-acquired-'+accredId+']')[0].focus(); return toastr.error("Please fill out this field!");}

        if (onyxDropzone1.files.length > 0) {
            onyxDropzone1.processQueue();
        } else {
            var url = "{{ route('updateAccreditation', $detailUser['id']) }}";
            var data = {
                "accreditationsId": accreditationsIdToEdit,
                "qualification": $("input#qualification-"+accreditationsIdToEdit).val(),
                "date-acquired": $("input#date-acquired-"+accreditationsIdToEdit).val(),
                "file": '',
            }

            sendAjaxAccreditation(url, data);
        }
    }

    function cancelEditAccred(formId) {
        oldQualification = $('input#qualification-hide-' + formId).val();
        $('input#qualification-' + formId).val(oldQualification);

        oldDateAcquired = $('input#date-acquired-hide-' + formId).val();
        $('input#date-acquired-' + formId).val(oldDateAcquired);

        $("div#update-accreditations-" + formId).removeClass('show');

        onyxDropzone.removeAllFiles();
        onyxDropzone1.removeAllFiles();

        $(".show-hide-file-"+formId).removeAttr('hidden');
    }

    $(function() {
        $("input.date-picker").datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom"
        });

        @foreach($accreditationInfo as $info)
            $("input#date-acquired-{{ $info['id'] }}").datepicker({
                startDate: "{{ $info['date_acquired'] }}",
            });
        @endforeach
    });

    function sendAjaxAccreditation(url, data) {
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
                $("div#show-accreditations").html(response.html);
                $('#modalDelete').modal('hide');
        }});
    }

    $('.img-remove-accreditations').click(function () {
        let accreditationsId = $(this).attr('data-id');
        let imgAccreditationsName = $(this).attr('data-name');
        let url = "{{ route('deleteImageAccreditation', $detailUser['id']) }}";
        let data = {
            'accreditationsId': accreditationsId,
            'imgAccreditationsName': imgAccreditationsName,
        };

        sendAjaxDeleteImageOfAccreditation(url, data);
    });

    function sendAjaxDeleteImageOfAccreditation(url, data) {
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
                $("div#show-accreditations").html(response);
        }});
    }

    $('#i-add-accreditations').click (function () {
        accreditationsIdToEdit = '';
    });
</script>