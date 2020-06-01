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
        <div class="card mb-3" id="show-deleted-notations">
            <div class="card-body tab-content recent-cases">
                <div class="row mb-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Deleted Notations</h3>
                        <div class="dropdown-menu-right">
                            <a href="{{ route('getListNotations', $detailMatter['id']) }}" class="mr-3" id="btn-show-list-notations"><i><u>View List Notations</u></i></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div style="display: inline-block; vertical-align: top;">
                            <form role="search" class="user-search">
                                <input id="deleted-notations-search" class="form-control search-box-list border-brown" type="text" placeholder="Search" aria-label="Search">
                                <a href="#" class="active">
                                    <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
                                </a>
                            </form>
                        </div>
                        <div style="display: inline-block;" class="container-filter-columns pb-2">
                            <div id="filter" class="filter border-brown pt-2" data-toggle="collapse" data-target="#filter-deleted-notation" aria-expanded="false">
                                <i class="fa fa-filter" aria-hidden="true"></i> Filters
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 collapse" id="filter-deleted-notation">
                        <div class="card card-body show-filter">
                            <div class="card-text">
                                <div class="div-group">
                                    <span class="text-filter">Created date</span>
                                    <span style="display: inline-block;">
                                        <i class="far fa-calendar-alt" aria-hidden="true" style="position: absolute; margin-left: 125px; margin-top: 10px;"></i>
                                        <input id="created-date-filter-trash" type="text" class="btn-filter-status px-2 date-picker pl-3" autocomplete="off" placeholder="Select duration" value="" style="min-width: 150px;">
                                    </span>
                                </div>
                                <div class="div-group">
                                    <span class="text-filter">Category</span>
                                    <select class="custom-fillter selectpicker px-2" id="category-filter-trash" data-live-search="true" title="All">
                                        <option value="all">All</option>
                                        @foreach ($listCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="x-close" id="close-dropdown-filter">&times;</div>
                                <br/>
                                <div class="float-right">
                                    <button type="button" class="btn btn-cancel-filter btn-cancel-filter-trash pt-0">Cancel</button>
                                    <button type="button" class="btn btn-apply-filter btn-filter-trash-notation pt-0">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12" id="deleted-notations-table">
                        <div class="table-responsive">
                            <table width="100%" id="deleted_notations_table" class="table">
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
                                @foreach ($listTrashNotations as $notation)
                                    <?php
                                        $fileName = $notation['file'];
                                        $path = "matters/" . $detailMatter['id'] . "/notations/" . $fileName;
                                    ?>
                                    @if (Storage::disk(env('DISK_STORAGE'))->exists($path))
                                    <tr>
                                        <td>
                                            <?php
                                                $url = App\Repositories\Matter\MatterRepository::loadFileForNotation($detailMatter['id'], $notation['file']);
                                            ?>
                                            <a href="{{ isset($url) ? $url : "#" }}" rel="noopener noreferrer" target="__blank">{{ $notation['file'] }}</a>
                                        </td>
                                        <td class="color-content">{{ isset($notation['created_at']) ? date('H:i:s d/m/Y', strtotime($notation['created_at'])) : $notation['created_at'] }}</td>
                                        <td class="color-content">{{ App\Repositories\Notations\NotationsRepository::getCategoryName($notation['category_id']) }}</td>
                                        <td class="color-content">{{ $notation['note'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-restore mb-1" id="btn-restore" onclick="showModalRestore('{{ route('restoreNotation', [$notation['id'], $detailMatter['id']]) }}');">
                                                Restore
                                            </button>
                                            @can('delete notations')
                                                <button type="button" class="btn btn-delete-permanently mb-1" id="btn-delete-permanently" onclick="showModalDelete('{{ route('deletePermanentlyNotation', [$notation['id'], $detailMatter['id']]) }}');">
                                                    Delete Permanently
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                    @else
                                        @php
                                            App\Repositories\Notations\NotationsRepository::deletePermanentlyNotation($notation['id'], $detailMatter['id']);
                                        @endphp
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        @include('matter.matter-detail-information.matter-detail-information-assignees')
        @include('matter.matter-detail-information.matter-detail-information-milestone')
    </div>
</div>

<div class="modal fade" id="modalRestore" tabindex="-1" role="dialog" aria-labelledby="restoreTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="restoreTitle">{{ __('Restore Deleted File') }}</h5>
                <button type="button" class="close btn-x-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to restore the file?') }}
            </div>
            <div class="modal-footer edit-add-info-footer">
                <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <form action="" method="POST" id=restore-form>
                    @csrf
                    <button type="submit" id="btn-ok-modal" class="btn btn-add-modal">{{ __('RESTORE') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title delete-info" id="deleteTitle">Delete File Permanently</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body content-delete">Are you sure you want to delete the file permanently?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <form action="" method="POST" id=delete-form>
                    @csrf
                    <button type="submit" class="btn btn-delete-modal" style="display: inline-block; width: auto;">{{ __('DELETE PERMANENTLY') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@endcan

@section('javascript')
    <script type="text/javascript">
        @if (isset($result))
            @if ($result['alert-type'] == 'error')
                toastr.error("{{ $result['message'] }}");
            @else
                toastr.success("{{ $result['message'] }}");
            @endif
        @endif

        $.fn.selectpicker.Constructor.BootstrapVersion = '4';

        $('.selectpicker').selectpicker({
            style: 'border-btn-select',
            liveSearchPlaceholder: 'Search',
            tickIcon: 'checkbox-select checkmark-select',
            size: 5
        });

        var deleted_notations_table;
        $(document).ready(function() {
            $('#deleted_notations_table').DataTable({
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

            deletedNotationsTable = $('#deleted_notations_table').DataTable();

            // Search input
            $('#deleted-notations-search').on('keyup', function() {
                deletedNotationsTable.search( this.value ).draw();
            });

            $("input.date-picker").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            });

            var createdDate2, valCategory2;

            $('.btn-filter-trash-notation').on('click', function() {
                valCategory2 = '';

                deletedNotationsTable.search('').draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // date
                createdDate2 = $('#created-date-filter-trash').val();
                let indexDate2 = data[1].trim().indexOf(createdDate2);
                // category
                valCategory2 = $('#category-filter-trash').val();
                let indexCategory2 = data[2].trim().indexOf(valCategory2);

                if((createdDate2 == '' || indexDate2 > -1) && (valCategory2 == 'all' || indexCategory2 > -1)) {
                    return true;
                }
                return false;
            });

            $('.btn-cancel-filter').click(function() {
                $('.filter-option-inner-inner').text('All');
                $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'All');
                $('#created-date-filter-trash').val('');
                $('#category-filter-trash').val('all');

                $('.btn-apply-filter').trigger('click');
            });
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#deleted_notations_table").on('click', 'th', function() {
            $("#deleted_notations_table thead th").each(function(i, th) {
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
        $("#deleted_notations_table th").first().click().click();
        /* End */

        // Change color when button is clicked
        $(function() {
            $('.filter').on('click', function(event) {
                $(this).toggleClass('filter-clicked');
            });

            $('.x-close').click(function() {
                $('.filter').removeClass('filter-clicked');
                $('#created-date-filter-trash').val('');
                $('#category-filter-trash').val('all');
                $('div#filter-deleted-notation').removeClass('show');

                $('.filter-option-inner-inner').text('All');
                $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'All');

                $('.btn-apply-filter').trigger('click');
            });
        });

        function showModalRestore(url) {
            $("#restore-form").attr('action', url);
            $('#modalRestore').modal('show');
        }

        function showModalDelete(url) {
            $("#delete-form").attr('action', url);
            $('#modalDelete').modal('show');
        }
    </script>
@stop