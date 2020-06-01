@php
    $columns = array('File Name', 'Size', 'Created at', 'Upload By', 'Description' , '');
@endphp

@if (isset($pathToBack) && !empty($pathToBack))
    <script src="{{ asset('js/jquery.dataTables.js') }}" defer></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}" defer></script>
@endif

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
        <h6>
            @if (isset($currentPath) && !empty($currentPath))
                @php
                    $pathHeader = explode('/', $currentPath);
                    $folderName = $pathHeader[count($pathHeader) - 1];
                    unset($pathHeader[0]);
                    unset($pathHeader[1]);

                    $pathHeader = implode(' > ', $pathHeader);
                @endphp
                {{ ucwords($pathHeader) }}
            @else
                File Manager
            @endif
        </h6>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
        <h3 class="title mb-0">
            @if (isset($currentPath) && !empty($currentPath))
                {{ ucwords($folderName) }}
            @else
                File Manager
            @endif
        </h3>
        <div class="dropdown-menu-right">
            @if (isset($pathToBack) && !empty($pathToBack))
                <a href="javascript:void(0);" onclick="openFolder('{{ $pathToBack }}', '{{ $detailMatter['id'] }}')" class="no-underline font-weight-bold mr-3">{{ '< BACK' }}</a>
            @endif
            @can('upload files and create folders')
                <a href="javascript:void(0);" onclick="showCreateFolder()"><button type="button" id="btn-create-folder" class="btn btn-bg-white ml-2">CREATE FOLDER</button></a>
                <a href="javascript:void(0);" onclick="showUploadFiles()"><button type="button" id="btn-upload-file" class="btn btn-bg-white ml-2">UPLOAD FILE</button></a>
            @endcan
        </div>
    </div>
</div>

@if (isset($pathToBack) && !empty($pathToBack))
    <div class="row mb-4 pl-3 pr-3">
        <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between file_manager-description">
            @if (isset($currentPath) && !empty($currentPath))
                @php
                    $description = DB::table('file_manager')
                                ->where('case_id', $detailMatter['id'])
                                ->where('path', $currentPath)
                                ->pluck('description')->first();
                @endphp
            @endif
            <p id="content-description" class="content-description mb-0 p-2">
                {{ ucfirst($description) }}
            </p>
        </div>
    </div>
@endif

<div class="table-responsive">
    <table width="100%" id="file_manager_table" class="table">
        <thead>
            <tr class="column-name">
                @foreach ($columns as $title)
                    <th class="pl-2">{{ $title }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="column-content bg-white">
        @foreach ($fileManagers as $file)
            <tr>
                <td class="pl-2">
                    <img src="@if ($file['type'] === 'folder') /images/folder.png @else /images/file.png @endif" alt="avata" class="img-file-manager float-left">
                    <div class="mt-5 text-below-image">
                        @if ($file['type'] === 'folder')
                            <a href="javascript:void(0);" class="no-underline" onclick="openFolder('{{ $file['path'] }}', '{{ $detailMatter['id'] }}')">
                                {{ ucfirst($file['name']) }}
                            </a>
                        @else
                            <a target="_blank" rel="noopener noreferrer" href="{{ $file['path'] }}" class="no-underline">
                                {{ ucfirst($file['name']) }}
                            </a>
                        @endif
                    </div>
                </td>
                <td class="pl-2">
                    <div class="mt-5">
                        @if ($file['type'] !== 'folder')
                            {{ $file['size'] }}
                        @endif
                    </div>
                </td>
                <td class="pl-2">
                    <div class="mt-5">
                        {{ date('d/m/Y h:i A', strtotime($file['info']->created_at)) }}
                    </div>
                </td>
                <td class="pl-2">
                    <div class="mt-5">
                        @php
                            $userDetail = App\Repositories\Users\UserRepository::getDetailUserById($file['info']->user_id);
                        @endphp
                        {{ ucwords($userDetail->name . ' ' . $userDetail->family_name) }}
                    </div>
                </td>
                <td class="pl-2">
                    <div class="mt-5" style="max-width: 100%; max-height: 15px; overflow: hidden;">
                        {{ ucfirst($file['info']->description) }}
                    </div>
                </td>
                <td class="pl-2">
                    <div class="mt-5">
                        @php
                            if (isset($currentPath) && !empty($currentPath)) {
                                $pathDelete = $currentPath . '/' . $file['name'];
                            } else {
                                $pathDelete = 'matters/' . $detailMatter['id'] . '/file manager/' . $file['name'];
                            }
                        @endphp
                        @if(auth()->user()->hasAnyPermission('edit files and folders', 'delete files and folders'))
                            <span class="cursor-pointer">
                                <img class="office-img" style="width: auto !important" src="/images/doc_black.png" data-toggle="dropdown">
                                <div class="dropdown-menu dropdown-menu-right">
                                    @can('edit files and folders')
                                        <a class="dropdown-item cursor-pointer change-status" onclick="@if ($file['type'] === 'folder') showModalEditFolder('{{ $file['info']->id }}', '{{ $file['path'] }}', '{{ $file['name'] }}', '{{ $file['info']->description }}') @else showModalEditFile('{{ $file['info']->id }}', '{{ $pathDelete }}', '{{ $file['name'] }}', '{{ $file['info']->description }}') @endif">Edit</a>
                                    @endcan
                                    @can('delete files and folders')
                                        <a href="javascript:void(0);" class="dropdown-item text-danger font-weight-bold" onclick="@if ($file['type'] === 'folder') showModalDeleteFolder('{{ $file['path'] }}') @else showModalDeleteFile('{{ $pathDelete }}') @endif">Delete</a>
                                    @endcan
                                </div>
                            </span>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
    @if (isset($result))
        @if ($result['alert-type'] == 'error')
            toastr.error("{{ $result['message'] }}");
        @else
            toastr.success("{{ $result['message'] }}");
        @endif
    @endif
    var table;
    $(document).ready(function() {
        $('#file_manager_table').DataTable({
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
            "order": [],
            "columnDefs": [
                { "width": "20%", "orderable": false, "targets": 4 },
                { "width": "2%", "orderable": false, "targets": 5 }
            ]
        });
        table = $('#file_manager_table').DataTable();

        $("input.date-picker").datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom"
        });
        // Search input
        $('#notation-search-box').on('keyup', function() {
            table.search( this.value ).draw();
        });

        var createdDate, valCategory;

        $('.btn-apply-filter').on('click', function() {
            valCategory = '';

            table.search('').draw();
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

    $("#file_manager_table").on('click', 'th', function() {
        $("#file_manager_table thead th").each(function(i, th) {
            $(th).find('.arrow-hack').remove();
            var html = $(th).html(),
            cls = $(th).attr('class');
            var arrayName = ['File Name', 'Size', 'Created at', 'Upload By'];
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
    $("#file_manager_table th").first().click().click();
    /* End */

    function openFolder(path, matterId)
    {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: "{{ route('openFolder') }}",
            data: {
                'matterId' : matterId,
                'path'     : path
            },
            method: 'get',
            async: false,
            success: function(response){
                $('div#file-manager-table').html(response.html);
            }
        });
    }

    function showCreateFolder()
    {
        $('#modal-add-folder input#name').val('');
        $('#modal-add-folder #description').val('');
        $('#modal-add-folder').modal('show');
    }

    function showUploadFiles()
    {
        myDropzone.removeAllFiles();
        $('#modal-upload-files').modal('show');
    }

    function createFolder(matterId)
    {
        let path = @if (isset($currentPath) && !empty($currentPath)) '{{ $currentPath }}' @else 'matters/{{ $detailMatter['id'] }}/file manager' @endif;
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: "{{ route('createFolder') }}",
            data: {
                'matterId'    : matterId,
                'path'        : path,
                'folderName'  : $('#modal-add-folder input#name').val(),
                'description' : $('#modal-add-folder #description').val()
            },
            method: 'get',
            async: false,
            success: function(response){
                if (typeof (response.html) !== 'undefined') {
                    $("#modal-add-folder").modal('hide');
                    $('div#file-manager-table').html(response.html);
                }

                if (typeof(response['alert-type']) !== 'undefined' && response['alert-type'] == 'error') {
                    toastr.error(response['message']);
                }
            }
        });
    }

    var myDropzone;
    var currentPath = @if (isset($currentPath) && !empty($currentPath)) '{{ $currentPath }}' @else 'matters/{{ $detailMatter['id'] }}/file manager' @endif;

    $(document).ready(function () {
        $("#upload-files-for-matter").addClass("dropzone");
        Dropzone.options.myDropzone = false;
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone('#upload-files-for-matter', {
            url: "{{ route('uploadFiles') }}",
            method: "POST",
            previewTemplate: document.querySelector('#preview-template').innerHTML,
            parallelUploads: 10,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 100,
            maxFiles: 10,
            addRemoveLinks: true,
            dictRemoveFile: 'Remove file',
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
                    formData.append("matterId", "{{ $detailMatter['id'] }}");
                    formData.append("path", currentPath);
                    var data = $('#upload-files-for-matter').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                }),
                this.on("successmultiple", function(file, response) {
                    if (typeof (response.html) !== 'undefined') {
                        $("#modal-upload-files").modal('hide');
                        $('div#file-manager-table').html(response.html);
                    }

                    if (typeof(response['alert-type']) !== 'undefined' && response['alert-type'] == 'error') {
                        toastr.error(response['message']);
                    }
                }),
                this.on("errormultiple", function(file, message, xhr) {
                    toastr.error("Fail to upload file " + file[0].name);
                    this.removeFile(file);
                });
            }
        });
    });

    function uploadFiles()
    {
        if (myDropzone.files.length > 0) {
            myDropzone.processQueue();
        } else {
            return toastr.error("Please choose at leat one file!");
        }
    }

    var pathDelete;

    function showModalDeleteFolder(path)
    {
        pathDelete = path;
        $("#modal-delete-folder").modal('show');
    }

    function showModalDeleteFile(path)
    {
        pathDelete = path;
        $("#modal-delete-file").modal('show');
    }

    function deleteFolder(matterId)
    {
        let url = "{{ route('deleteFolder') }}";
        sendAjaxDelete(url, pathDelete, matterId)
    }

    function deleteFile(matterId)
    {
        let url = "{{ route('deleteFile') }}";
        sendAjaxDelete(url, pathDelete, matterId)
    }

    function sendAjaxDelete(url, path, matterId)
    {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: url,
            data: {
                'matterId'    : matterId,
                'path'        : path,
                'currentPath' : currentPath
            },
            method: 'get',
            async: false,
            success: function(response){
                $("#modal-delete-folder").modal('hide');
                $("#modal-delete-file").modal('hide');
                if (typeof (response.html) !== 'undefined') {
                    $('div#file-manager-table').html(response.html);
                }

                if (typeof(response['alert-type']) !== 'undefined' && response['alert-type'] == 'error') {
                    toastr.error(response['message']);
                }
            }
        });
    }

    function showModalEditFolder(folderId, path, folderName, description)
    {
        $("#modal-edit-folder input#id-edit").val(folderId);
        $("#modal-edit-folder input#path-edit").val(path);
        $("#modal-edit-folder input#name").val(folderName);
        $("#modal-edit-folder #description").val(description);
        $("#modal-edit-folder").modal('show');
    }

    function showModalEditFile(fileId, path, fileName, description)
    {
        $("#modal-edit-file input#id-edit").val(fileId);
        $("#modal-edit-file input#path-edit").val(path);
        $("#modal-edit-file input#name").val(fileName);
        $("#modal-edit-file #description").val(description);
        $("#modal-edit-file").modal('show');
    }

    function editFolder(matterId)
    {
        let url = "{{ route('editFolder') }}";
        let data = {
            'id'          : $("#modal-edit-folder input#id-edit").val(),
            'matterId'    : matterId,
            'path'        : $("#modal-edit-folder input#path-edit").val(),
            'name'        : $("#modal-edit-folder input#name").val(),
            'description' : $("#modal-edit-folder #description").val(),
            'currentPath' : currentPath
        };

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
                if (typeof (response.html) !== 'undefined') {
                    $("#modal-edit-folder").modal('hide');
                    $('div#file-manager-table').html(response.html);
                }

                if (typeof(response['alert-type']) !== 'undefined' && response['alert-type'] == 'error') {
                    toastr.error(response['message']);
                }
            }
        });
    }

    function editFile(matterId)
    {
        let url = "{{ route('editFile') }}";
        let data = {
            'id'          : $("#modal-edit-file input#id-edit").val(),
            'matterId'    : matterId,
            'path'        : $("#modal-edit-file input#path-edit").val(),
            'name'        : $("#modal-edit-file input#name").val(),
            'description' : $("#modal-edit-file #description").val(),
            'currentPath' : currentPath
        };

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
                if (typeof (response.html) !== 'undefined') {
                    $("#modal-edit-file").modal('hide');
                    $('div#file-manager-table').html(response.html);
                }

                if (typeof(response['alert-type']) !== 'undefined' && response['alert-type'] == 'error') {
                    toastr.error(response['message']);
                }
            }
        });
    }
</script>
