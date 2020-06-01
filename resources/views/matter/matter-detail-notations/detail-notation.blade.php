@can('view matters')
@can('view notations')
@extends('layouts.app')
<?php
    $columns = array('Notation', 'Date/Time', 'Category', 'Note', '');
?>
@section('content')
@include('navbar')
@include('matter.matter-detail-information.matter-detail-header')
<div class="row">
    <div class="col-xl-8" id="show-notations">
        <div class="card mb-3" id="show-list-notations">
            <div class="card-body tab-content recent-cases">
                <div class="row mb-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Notation List</h3>
                        <div class="dropdown-menu-right">
                            <a href="{{ route('getListTrashNotations', $detailMatter['id']) }}" class="mr-3" id="btn-show-deleted-notations"><i><u>Deleted Notations</u></i></a>
                            @can('upload notations')
                                <a href="javascript:void(0);"><button type="button" id="btn-upload-notation" class="btn btn-bg-white">UPLOAD NOTATIONS</button></a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div style="display: inline-block; vertical-align: top;">
                            <form role="search" class="user-search">
                                <input id="notation-search-box" class="form-control search-box-list border-brown" type="text" placeholder="Search" aria-label="Search">
                                <a href="#" class="active">
                                    <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
                                </a>
                            </form>
                        </div>
                        <div style="display: inline-block;" class="container-filter-columns pb-2">
                            <div id="filter" class="filter border-brown pt-2" data-toggle="collapse" data-target="#filter-notation" aria-expanded="false">
                                <i class="fa fa-filter" aria-hidden="true"></i> Filters
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 collapse" id="filter-notation">
                        <div class="card card-body show-filter">
                            <div class="card-text">
                                <div class="div-group">
                                    <span class="text-filter">Created date</span>
                                    <span style="display: inline-block;">
                                        <i class="far fa-calendar-alt" aria-hidden="true" style="position: absolute; margin-left: 125px; margin-top: 10px;"></i>
                                        <input id="created-date-filter" type="text" class="btn-filter-status px-2 date-picker pl-3" autocomplete="off" placeholder="Select duration" value="" style="min-width: 150px;">
                                    </span>
                                </div>
                                <div class="div-group">
                                    <span class="text-filter">Category</span>
                                    <select class="custom-fillter selectpicker px-2" id="category-filter" data-live-search="true" title="All">
                                        <option value="all">All</option>
                                        @foreach ($listCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="x-close" id="close-dropdown-filter">&times;</div>
                                <br/>
                                <div class="float-right">
                                    <button type="button" class="btn btn-cancel-filter pt-0">Cancel</button>
                                    <button type="button" class="btn btn-apply-filter pt-0">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12" id="table-notation-list">
                        <div class="table-responsive">
                            <form action="" method="POST">
                                @csrf
                                <table width="100%" id="notation_table" class="table">
                                    <thead>
                                        <tr class="column-name">
                                            @foreach ($columns as $title)
                                                @if($title == 'Notation')
                                                    <th>{{ ucfirst($title) }}</th>
                                                @else
                                                    <th>{{ ucfirst($title) }}</th>
                                                @endif
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="column-content bg-white">
                                    @foreach ($listNotations as $notation)
                                        <?php
                                            $fileName = $notation['file'];
                                            $path = "matters/" . $detailMatter['id'] . "/notations/" . $fileName;
                                        ?>
                                        @if (Storage::disk(env('DISK_STORAGE'))->exists($path))
                                        <tr>
                                            <td>
                                                <?php
                                                    $url = App\Repositories\Matter\MatterRepository::loadFileForNotation($detailMatter['id'], $notation['file']);
                                                    $categoryName = App\Repositories\Notations\NotationsRepository::getCategoryName($notation['category_id']);
                                                ?>
                                                <img src="/images/file.png" alt="avata" class="img-file-manager float-left mr-0" style="margin-left: -3px;">
                                                <div class="mt-5 text-below-image">
                                                    <a href="{{ isset($url) ? $url : "#" }}" rel="noopener noreferrer" target="__blank">{{ $notation['file'] }}</a>
                                                </div>
                                            </td>
                                            <td class="color-content">{{ isset($notation['created_at']) ? date('H:i:s d/m/Y', strtotime($notation['created_at'])) : $notation['created_at'] }}</td>
                                            <td class="color-content">{{ App\Repositories\Notations\NotationsRepository::getCategoryName($notation['category_id']) }}</td>
                                            <td class="color-content">{{ $notation['note'] }}</td>
                                            @if(auth()->user()->hasAnyPermission('edit notations', 'delete notations'))
                                                <td width="15%">
                                                    <div style="width: 50px;">
                                                        @can('edit notations')
                                                            <span class="cursor-pointer mr-3" id="edit-{{ $notation['id'] }}" onclick="showModalUpdate('{{ $notation['id'] }}', '{{ $categoryName }}', '{{ $notation['category_id'] }}', '{{ $notation['note'] }}', '{{ $notation['file'] }}')">
                                                                <img class="office-img" src="/images/btn_pen_black.png">
                                                            </span>
                                                        @endcan
                                                        @can('delete notations')
                                                            <span class="cursor-pointer" onclick="showModalDelete('{{ $notation['id'] }}');">
                                                                <img class="office-img" src="/images/img-delete-black.png">
                                                            </span>
                                                        @endcan
                                                    </div>
                                                </td>
                                            @else
                                                <td class="p-0 m-0"></td>
                                            @endif
                                        </tr>
                                        @else
                                            @php
                                                App\Repositories\Notations\NotationsRepository::deletePermanentlyNotation($notation['id'], $detailMatter['id']);
                                            @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($checkViewTrash)
            @include('matter.matter-detail-notations.trash-notation')
        @endif
    </div>

    <div class="col-xl-4">
        @include('matter.matter-detail-information.matter-detail-information-assignees')
        @include('matter.matter-detail-information.matter-detail-information-milestone')
    </div>
</div>

<div class="modal fade" id="modal-upload-notation" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="addTitle">{{ __('Upload Notation') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" id="x-close-notation">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row mt-2">
                    <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Category') }}</label>

                    <div class="col-md-12 col-lg-9">
                        <select class="form-control selectpicker" name="category_id" id="category_id" data-live-search="true" title="Select category" required>
                            @foreach ($listCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Note') }}</label>

                    <div class="col-md-12 col-lg-9" id="select-location-add">
                        <textarea class="form-control" name="note" id="note"></textarea>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-12">
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
                                                <a href="#!" data-dz-remove id="remove-img"><img src="/images/img_remove.png"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row my-0 py-0">
                    <div class="col-12 px-0">
                        <div class="needsclick dr-upload-files upload-files-form files-container mt-0 pl-3" id="upload-files-notation-form" enctype="multipart/form-data">
                            <div class="dz-message needsclick btn-upload-notation pt-0 mt-0">
                                <span>Upload attachment</span><br>
                                <span class="note needsclick"></span>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </div>
                        <!-- Warnings -->
                        <div id="warnings"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer edit-add-info-footer">
                <div class="form-group">
                    <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                    <button type="button" class="btn btn-add-modal" id="btn-upload-notation-modal">{{ __('UPLOAD') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteNotation" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title delete-info" id="deleteTitle">Delete File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body content-delete">Are you sure you want to delete the file?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" onclick="deleteNotation();">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update-notation" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="addTitle">{{ __('Edit Notation') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" id="x-close-notation">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row mt-2">
                    <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Category') }}</label>

                    <div class="col-md-12 col-lg-9">
                        <select class="form-control selectpicker" name="category_id" id="category_id_update" data-live-search="true" title="Select category" required>
                            @foreach ($listCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Note') }}</label>

                    <div class="col-md-12 col-lg-9" id="select-location-add">
                        <textarea class="form-control" name="note" id="note_update"></textarea>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('File *') }}</label>

                    <div class="col-md-12 col-lg-9">
                        <input class="form-control" id="file-name" value="" disabled>
                    </div>
                </div>
            </div>

            <div class="modal-footer edit-add-info-footer">
                <div class="form-group">
                    <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                    <button type="submit" class="btn btn-add-modal" id="btn-upload-notation-modal" onclick="updateNotation();">{{ __('SAVE') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endcan
@endcan

@section('javascript')
    <script type="text/javascript">
        $.fn.selectpicker.Constructor.BootstrapVersion = '4';

        $('.selectpicker').selectpicker({
            style: 'border-btn-select',
            liveSearchPlaceholder: 'Search',
            tickIcon: 'checkbox-select checkmark-select',
            size: 5
        });

        var notationTable;
        $(document).ready(function() {
            $('#notation_table').DataTable({
                "info": true,
                "pagingType": "full_numbers",
                "language": {
                    "paginate": {
                        "first": '&Iota;<i class="fa fa-angle-left"></i>',
                        "previous": '<i class="fa fa-angle-left"></i>',
                        "next": '<i class="fa fa-angle-right"></i>',
                        "last": '<i class="fa fa-angle-right"></i>&Iota;'
                    },
                    "lengthMenu": "Show <b>_MENU_ rows</b>",
                    "info": "Total _TOTAL_ entries",
                },
                "sDom": 'Rfrtlip',
                "columnDefs": [
                    { "orderable": false, "targets": 4 }
                ]
            });
            notationTable = $('#notation_table').DataTable();

            $("input.date-picker").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            });

            // Search input
            $('#notation-search-box').on('keyup', function() {
                notationTable.search( this.value ).draw();
            });

            var createdDate, valCategory;

            $('.btn-apply-filter').on('click', function() {
                valCategory = '';

                notationTable.search('').draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // date
                createdDate = $('#created-date-filter').val();
                let indexDate = data[1].trim().indexOf(createdDate);
                // category
                valCategory = $('#category-filter').val();
                let indexCategory = data[2].trim().indexOf(valCategory);

                if((createdDate == '' || indexDate > -1) && (valCategory == 'all' || indexCategory > -1)) {
                    return true;
                }
                return false;
            });

            $('.btn-cancel-filter').click(function() {
                // date
                $('#created-date-filter').val('');
                // category
                $('#category-filter').val('all');
                $('.filter-option-inner-inner').text('All');
                $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'All');

                $('.btn-apply-filter').trigger('click');
            });
        });
        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#notation_table").on('click', 'th', function() {
            $("#notation_table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                cls = $(th).attr('class');
                var arrayName = ['Notation', 'Date/Time', 'Category', 'Note'];
                switch (cls) {
                    case 'sorting_asc' : 
                        if (arrayName.indexOf(html) != -1) {
                            $(th).html(html+spanAsc);
                        }
                        break;
                    case 'sorting_desc' :
                        if (arrayName.indexOf(html) != -1) {
                            $(th).html(html+spanDesc);
                        }
                        break;
                    default : 
                        if (arrayName.indexOf(html) != -1) {
                            $(th).html(html+spanSorting);
                        }
                        break;
                }
            });
        });
        $("#notation_table th").first().click().click();
        /* End */

        // show modal
        $('#btn-upload-notation').click(function() {
            resetValueModal();
            $('#modal-upload-notation').modal('show');
        });

        var url, data, __notationId, __notationIdUpdate;

        function showModalDelete(notationId) {
            __notationId = notationId;
            $('#modalDeleteNotation').modal('show');
        }

        function deleteNotation() {
            url = "{{ route('deleteNotation', ['__notationId', $detailMatter['id']]) }}".replace('__notationId', __notationId);

            if(__notationId) {
                sendAjaxDeleteNotation(url);
            }
        }

        function sendAjaxDeleteNotation(url) {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            });
            jQuery.ajax({
                url: url,
                method: 'get',
                async: false,
                success: function(response){
                    $("div#table-notation-list").html(response);
                    $('#modalDeleteNotation').modal('hide');
                }
            });
        }

        // for dropzone upload file
        var onyxDropzonexxxx;
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
                        $("#upload-files-notation-form").addClass("dropzone");
                        onyxDropzonexxxx = new Dropzone(target, {
                            url: "{{ route('storeNotation', $detailMatter['id']) }}",
                            method: "POST",
                            maxFiles: 1,
                            maxFilesize: 20,
                            parallelUploads: 1,
                            previewTemplate: previewTemplate,
                            previewsContainer: "#previews",
                            createImageThumbnails: true,
                            dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                            dictInvalidFileType: "You can't upload files of this type.",
                            dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},
                            autoProcessQueue:false,
                            uploadMultiple: false,
                            cache: false,
                        });

                        Dropzone.autoDiscover = false;
                        onyxDropzonexxxx.on("addedfile", function(file) {
                            $('.preview-container').css('visibility', 'visible');
                            file.previewElement.classList.add('type-' + base.fileType(file.name));
                            if (typeof file.previewElement !== "undefined") {
                                this.removeFile(file.previewElement);
                            }
                        });

                        onyxDropzonexxxx.on('sending', function (file, xhr, formData) {
                            formData.append("_token", "{{ csrf_token() }}");
                            formData.append("category_id", $("select#category_id").val());
                            formData.append("note", $("textarea#note").val());
                            var data = $('#upload-files-notation-form').serializeArray();
                            $.each(data, function(key, el) {
                                formData.append(el.name, el.value);
                            });
                        });

                        onyxDropzonexxxx.on('success', function (file, response) {
                            $("div#table-notation-list").html(response.html);
                        });

                        onyxDropzonexxxx.on('error', function (file, response) {
                            this.removeFile(file);
                            toastr.error("Fail to upload file");
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

        $('#btn-upload-notation-modal').click(function(e) {
            e.preventDefault();

            var categoryId = $("select#category_id").val();
            if (categoryId == '' || categoryId == undefined) {
                return toastr.error("Please select category!");
            }

            if (onyxDropzonexxxx.files.length > 0) {
                onyxDropzonexxxx.processQueue();
                $("#modal-upload-notation").modal('hide');
            } else {
                return toastr.error("Please choose one file!");
            }
        });

        $(".btn-cancel-add-modal").click(function() {
            resetValueModal();
        });

        $("span#x-close-notation").click(function() {
            resetValueModal();
        });

        function resetValueModal() {
            $("select#category_id").val('');
            $("textarea#note").val('');
            $('.filter-option-inner-inner').text('Select category');
            $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'Select category');
            onyxDropzonexxxx.removeAllFiles();
        }
        // Change color when button is clicked
        $(function() {
            $('.filter').on('click', function(event) {
                $(this).toggleClass('filter-clicked');
            });

            $('.x-close').click(function() {
                $('.filter').removeClass('filter-clicked');
                // date
                $('#created-date-filter').val('');
                // category
                $('#category-filter').val('all');
                $('div#filter-notation').removeClass('show');

                $('.filter-option-inner-inner').text('All');
                $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'All');

                $('.btn-apply-filter').trigger('click');
            });
        });
        /* edit notation */
        function showModalUpdate(notationId, categoryName, categoryId, note, file) {
            __notationIdUpdate = notationId;
            $('#category_id_update').val(categoryId);
            $('.filter-option-inner-inner').text(categoryName);
            $('button.btn.dropdown-toggle.border-btn-select').attr('title', categoryName);
            $('#note_update').val(note);
            $('#file-name').val(file);

            $('#modal-update-notation').modal('show');
        }

        function updateNotation() {
            url = "{{ route('updateNotation', ['__notationIdUpdate', $detailMatter['id']]) }}".replace('__notationIdUpdate', __notationIdUpdate);
            data = {
                'id': __notationIdUpdate,
                'matter_id': '{{ $detailMatter['id'] }}',
                'category_id': $("select#category_id_update").val(),
                'note': $("textarea#note_update").val()
            };

            if(__notationIdUpdate) {
                sendAjaxUpdateNotation(url, data);
            }
        }

        function sendAjaxUpdateNotation(url, data) {
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
                    $("div#table-notation-list").html(response);
                    $('#modal-update-notation').modal('hide');
                }
            });
        }
    </script>

    @include('matter.matter-detail-information.script-matter-detail-information')
@stop