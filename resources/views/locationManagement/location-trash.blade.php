@extends('layouts.app')
<?php
    $columns = ['Locations', 'Contacts', 'Active Cases', 'Last Case', 'Status'];
?>
@section('content')
@include('navbar')
    <div class="d-sm-flex align-items-center justify-content-between mb-20">
        <h1 class="title mb-0">Deleted Locations</h1>
    </div>
    <div style="display: inline-block; vertical-align: top;">
        <form role="search" class="user-search">
            <input id="user-search-box" class="form-control search-box-list" type="text" placeholder="Search" aria-label="Search">
            <a href="#" class="active">
                <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
            </a>
        </form>
    </div>
    <div style="display: inline-block;" class="container-filter-columns pb-2">
        <div class="show-column dropdown-toggle active" data-toggle="dropdown" id="dropdown-column">
            <i class="fa fa-bars" aria-hidden="true"></i> Columns
        </div>
        <div class="dropdown-menu dropdown-menu-right dropdown-column-user pt-2 pb-2" aria-labelledby="dropdown-column" id="column-setting">
            @foreach($columns as $key => $col)
                @if ($col != '')
                    <label class="dropdown-item check-box">
                        <input type="checkbox" class="role-checkbox" name="chkColSet" id="{{ $key }}" value="{{ $key }}" checked="checked">
                        <span class="checkmark check-mark-user"></span>{{ $col }}
                    </label>
                @endif
            @endforeach
        </div>
    </div>
    <div class="table-responsive view-trash">
        <table width="100%" id="location_table" class="table">
            <thead>
                <tr class="column-name">
                    @foreach ($columns as $col)
                        <th>{{ ucfirst($col) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="column-content bg-white">
                @foreach ($listLocations as $location)
                    @if ($location->trash)
                        <tr>
                            <td class="data-{{$location->id}}">
                                <div class="mt-5">
                                    {{ ucfirst($location->name) }}
                                </div>
                            </td>
                            <td class="data-{{$location->id}}">
                                <div class="mt-5 ml-4">
                                    {{ DB::table('contacts_list')->where('client_id', $location->id)->count() }}
                                </div>
                            </td>
                            <td class="data-{{$location->id}}">
                                <div class="mt-5 ml-4">
                                    {{ DB::table('cases')->where('location_id', $location->id)->count() }}
                                </div>
                            </td>
                            <td class="data-{{$location->id}}">
                                <div class="mt-5">
                                    @php
                                        $day = DB::table('cases')->select('created_at')->where('location_id', $location->id)->orderBy('created_at', 'DESC')->first();
                                    @endphp
                                    @if (!empty($day))
                                        {{ date_format(date_create($day->created_at), 'd/m/Y') }}
                                    @endif
                                </div>
                            </td>
                            <td class="data-{{$location->id}}">
                                <button type="button" class="btn btn-restore" id="btn-restore" onclick="showModalRestore('{{ route('restoreLocation', ['clientId'=>$clientDetail['id'], 'locationId'=>$location->id]) }}')">
                                    Restore
                                </button>
                                @can('delete clients and client locations')
                                    @if (DB::table('cases')->where('location_id', $location->id)->count() == 0)
                                        <button type="button" class="btn btn-delete-permanently" id="btn-delete-permanently" onclick="showModalDelete('{{ route('deletePermanentlyLocation', ['clientId'=>$clientDetail['id'], 'locationId'=>$location->id]) }}')">
                                        Delete Permanently
                                    </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- custom modal -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTitle">{{ __('Delete Location Permanently') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('This action can not be undone. Are you sure you want to delete the location permanently?') }}
                </div>
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

    <div class="modal fade" id="modalRestore" tabindex="-1" role="dialog" aria-labelledby="restoreTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="restoreTitle">{{ __('Restore Deleted Location') }}</h5>
                    <button type="button" class="close btn-x-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('Are you sure you want to restore the location?') }}
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
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#location_table').DataTable({
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
                "order": [[ 0, "asc" ]],
            });
            var table = $('#location_table').DataTable();
            // Search input
            $('#user-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );
        });
        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#location_table").on('click', 'th', function() {
            $("#location_table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                    cls = $(th).attr('class');
                switch (cls) {
                    case 'sorting_asc' :
                        $(th).html(html+spanAsc); break;
                    case 'sorting_desc' :
                        $(th).html(html+spanDesc); break;
                    default :
                        $(th).html(html+spanSorting); break;
                }
            });
        });

        $("#location_table th").first().click().click();
        /* End */

        $(document).on('click', '.dropdown-item', function(event){
            event.stopPropagation();
            $('.container-filter-columns').addClass('show');
            $("#dropdown-column").attr("aria-expanded","true");
            $('#column-setting').addClass('show');
        });

        // Change color when button is clicked
        $(function() {
            $('.filter').on('click', function(event) {
                $(this).toggleClass('filter-clicked');
            });

            $('.x-close').click(function() {
                $('.filter').removeClass('filter-clicked');
            });

            $('.show-column').on('click', function(event) {
                $(this).toggleClass('show-column-clicked');
            });

            $('.dropdown-column-user').on('click', function(event) {
                event.stopPropagation();
            });

            $(document).click(function(){
                $('.show-column').removeClass('show-column-clicked');
            });
        });

        // Handle column setting
        $(document).ready(function() {
            $(".dropdown-item > input[name='chkColSet']").click(function() {
                var chkIndex = parseInt($(this).val());
                var colIndex = chkIndex + 1;
                if($(this).is(":checked")) {
                    $('td:nth-child(' + colIndex + '),th:nth-child(' + colIndex + ')').show();
                    var isAllChecked = 0;
                    $(".dropdown-item > input[name='chkColSet']").each(function(){
                        if(!this.checked) isAllChecked = 1;
                    })
                    if(isAllChecked == 0) {
                        $('td, th').show();
                    }
                } else {
                    $('td:nth-child(' + colIndex + '),th:nth-child(' + colIndex + ')').hide();
                }
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