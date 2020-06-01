@extends('layouts.app')
<?php
    $titles = array('User Name', 'Role', 'Primary Phone', 'Primary Email', 'Last Status', 'Action');
    $columns = array('User Name', 'Role', 'Primary Phone', 'Primary Email', 'Last Status', 'Action');
?>

@section('content')
@include('navbar')
<div class="d-sm-flex align-items-center justify-content-between mb-20">
    <h1 class="title mb-0">Deleted Users</h1>
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
    <div id="filter" class="filter" data-toggle="collapse" data-target="#filter-user" aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i> Filters
    </div>
    <div class="show-column dropdown-toggle active" data-toggle="dropdown" id="dropdown-column">
        <i class="fa fa-bars" aria-hidden="true"></i> Columns
    </div>
    <div class="dropdown-menu dropdown-menu-right dropdown-column-user pt-2 pb-2" aria-labelledby="dropdown-column" id="column-setting">
        @foreach($columns as $key => $col)
            @if ($col != '')
                <label class="dropdown-item check-box">
                    <input type="checkbox" class="role-checkbox" name="chkColSet" id="{{ $key }}" value="{{ $key }}" data-column="{{ $key }}" checked="checked">
                    <span class="checkmark check-mark-user"></span>{{ $col }}
                </label>
            @endif
        @endforeach
    </div>
</div>
<div class="collapse" id="filter-user">
    <div class="card card-body show-filter">
        <div class="card-text">
            <span class="text-filter">Role</span>
            <select id="role-filter" class="btn-filter-role pl-2 pr-2">
                <option>All</option>
                @foreach($listRole as $role)
                <option>{{ ucwords($role->name) }}</option>
                @endforeach
            </select>
            <span class="text-filter">Status</span>
            <select id="status-filter" class="btn-filter-status pl-2 pr-2">
                <option>All</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Pending</option>
            </select>
            <div class="float-lg-right float-md-right">
                <button type="button" class="btn btn-cancel-filter pt-0">Cancel</button>
                <button type="button" class="btn btn-apply-filter pt-0 mr-40">Apply</button>
                <div class="x-close" id="close-dropdown-filter">&times;</div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive view-trash">
    <table width="100%" id="user_table" class="table">
        <thead>
            <tr class="column-name">
                @foreach ($titles as $title)
                    <th>{{ ucfirst($title) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="column-content bg-white" id="content-user">
            @foreach ($listUser as $key => $user)
                @if ($user['in_trash'])
                    <tr class="row-user">
                        <td class="data-{{$user['id']}} has-image">
                            @if (!empty($user->image))
                                <img src="{{ $user->image }}" alt="avata {{ $user->name }}" class="avata-client float-left">
                            @else
                                <span class="member float-left">
                                    <span class="avata-client member-initials">{{ substr($user->name, 0, 1) . '' . substr($user->family_name, 0, 1) }}</span>
                                </span>
                            @endif
                            <span class="mt-2 text-below-image">
                                {{ ucwords($user['name']) . ' ' . ucwords($user->family_name) }}
                            </span>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <span class="mt-2">
                                {{ ucwords(App\Http\Controllers\UserController::getRoleNameByUserId($user['id'])) }}
                            </span>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <span class="mt-2">
                                {{ App\Http\Controllers\UserController::getPrimaryPhoneByUserId($user['id']) }}
                            </span>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <span class="mt-2">
                                {{ $user['email'] }}
                            </span>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <span class="no-line-height status-user 
                                @switch($user['status'])
                                    @case('inactive') inactive-user bg-inactive-user @break
                                    @case('active') active-user bg-active-user @break
                                    @default pending-user
                                @endswitch">
                                @switch($user['status'])
                                    @case('inactive') Inactive @break
                                    @case('active') Active @break
                                    @default Pending
                                @endswitch
                            </span>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <button type="button" class="btn btn-restore" id="btn-restore" onclick="showModalRestore('{{ route('restoreUser', $user['id']) }}')">
                                Restore
                            </button>
                            @can('delete users')
                                @if (auth()->user()->id != $user['id'] && DB::table('cases')->where('user_id', $user['id'])->count() == 0 && DB::table('cases_users')->where('user_id', $user['id'])->count() == 0)
                                    <button type="button" class="btn btn-delete-permanently" id="btn-delete-permanently" onclick="showModalDelete('{{ route('deletePermanentlyUser', $user['id']) }}')">
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
                <h5 class="modal-title" id="deleteTitle">{{ __('Delete User Permanently') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('This action can not be undone. Are you sure you want to delete the user permanently?') }}
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
                <h5 class="modal-title" id="restoreTitle">{{ __('Restore Deleted User') }}</h5>
                <button type="button" class="close btn-x-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to restore the user?') }}
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
            $('#user_table').DataTable({
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
            });
            var table = $('#user_table').DataTable();
            // Search input
            $('#user-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );

            var statusFilter, roleFilter;

            $('.btn-cancel-filter').click(function() {
                $("select#role-filter").val("All");
                $("select#status-filter").val("All");
                $('.btn-apply-filter').trigger('click');
            });

            $('.btn-apply-filter').on('click', function() {
                roleFilter = '';
                table.search('').draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                roleFilter = $('#role-filter').val().trim();
                let indexRole = data[1].trim().indexOf(roleFilter);
                statusFilter = $('#status-filter').val().trim().toLowerCase();
                if((roleFilter == 'All' || indexRole > -1) && (statusFilter == 'all' || statusFilter == data[4].trim().toLowerCase())) {
                    return true;
                }
                return false;
            });

            // Handle show/hide column
            $('[data-column]').on('click', function (e) {
                // Get the column API object
                var column = table.column($(this).data('column'));
                // Toggle the visibility
                column.visible(!column.visible());
                return true
            });
        });
        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#user_table").on('click', 'th', function() {
            $("#user_table thead th").each(function(i, th) {
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

        $("#user_table th").first().click().click();
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
                $("select#role-filter").val("All");
                $("select#status-filter").val("All");
                $('div#filter-user').removeClass('show');
                $('.btn-apply-filter').trigger('click');
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