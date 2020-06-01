@extends('layouts.app')
<?php
    $columns = array('User Name', 'Role', 'Primary Phone', 'Primary Email', 'Offices', 'Matter Expertise', 'Status');
?>

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-20">
    <h1 class="title mb-0">Users Management</h1>
    <div class="dropdown-menu-right">
        <a href="{{ route('showListTrashUser') }}" class="in-trash-user">View Deleted Users</a>
        @can('create users')
            <a href="{{ route('showCreateUser') }}"><button type="button" class="d-none d-sm-inline-block btn btn-create-style">CREATE USER</button></a>
        @endcan
    </div>
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
            <div class="div-group">
                <span class="text-filter">Role</span>
                <div class="selectWrapper">
                    <select id="role-filter" class="btn-filter-role-user px-2">
                        <option>All</option>
                        @foreach($listRole as $role)
                        <option>{{ ucwords($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="div-group">
                <span class="text-filter">Offices</span>
                <div id="office-filter" class="dropdown pr-3 display-inline-block">
                    <button class="dropdown-toggle btn-filter-office pl-15" data-toggle="dropdown"><span id="fillter-office">All</span></button>
                    <ul class="dropdown-menu dropdown-office-user py-2" id="dropdown-office">
                        <li class="user-search mx-1">
                            <input class="form-control office-search-box border-0" id="filter-search-office" type="text" placeholder="Search" aria-label="Search" onkeyup="searchOffice();">
                            <i class="fa fa-search left-icon-search" aria-hidden="true"></i>
                        </li>
                        @foreach($listOffices as $office)
                            <li class="px-2">
                                <label class="dropdown-item text-subtitle-filter-matter mb-0 py-2 pr-1">
                                    <input type="checkbox" class="role-checkbox" name="filter-office" value="{{ $office['name'] }}">
                                    <span class="checkmark check-mark-matter"></span>
                                    <div class="whitespace-pre-line display-inline-block">{{ $office['name'] }}</div>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="div-group">
                <span class="text-filter">Matter Expertise</span>
                <div id="matter-filter" class="dropdown pr-3 display-inline-block">
                    <button class="dropdown-toggle btn-filter-matter pl-15" data-toggle="dropdown"><span id="fillter-type">All</span></button>
                    <ul class="dropdown-menu dropdown-matter-user py-2" id="dropdown-specific-matter">
                        <li class="user-search mx-1">
                            <input class="form-control matter-search-box border-0" id="filter-search-type" type="text" placeholder="Search" aria-label="Search" onkeyup="searchMatter();">
                            <i class="fa fa-search left-icon-search" aria-hidden="true"></i>
                        </li>
                        @foreach ($listSpecificMatters as $type)
                            <li class="px-2">
                                @if (count($type->children) > 0)
                                    <span class="py-1 text-title-filter-matter">{{ $type->name }}</span>
                                    @foreach ($type->children as $child)
                                        <label class="dropdown-item text-subtitle-filter-matter mb-0 py-2 pr-1">
                                            <input type="checkbox" class="specific-matters-checkbox role-checkbox" name="fillter-specific-matters" value="{{ $type->name . '/' . $child->name }}">
                                            <span class="checkmark check-mark-matter"></span>
                                            <div class="whitespace-pre-line display-inline-block">{{ $type->name . '/' . $child->name }}</div>
                                        </label>
                                    @endforeach
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="div-group">
                <span class="text-filter">Status</span>
                <div class="selectWrapper">
                    <select id="status-filter" class="btn-filter-status-user px-2">
                        <option>All</option>
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Pending</option>
                    </select>
                </div>
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
<div class="table-responsive">
    <table width="100%" id="user_table" class="table">
        <thead>
            <tr class="column-name">
                @foreach ($columns as $title)
                    <th>{{ ucfirst($title) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="column-content bg-white" id="content-user">
            @foreach ($listUser as $key => $user)
                @if (!$user['in_trash'])
                    <tr class="row-user">
                        <td class="data-{{$user['id']}} has-image">
                            @if (!empty($user->image))
                                <img src="{{ $user->image }}" alt="avata {{ $user->name }}" class="avata-client float-left">
                            @else
                                <div class="member float-left">
                                    <span class="avata-client member-initials">{{ substr($user->name, 0, 1) . '' . substr($user->family_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="mt-5 text-below-image">
                                <a href="{{ route('showDetailUser', $user['id']) }}" class="no-underline">
                                    {{ ucwords($user['name']) . ' ' . ucwords($user->family_name) }}
                                </a>
                            </div>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <div class="mt-5">
                                {{ ucwords(App\Http\Controllers\UserController::getRoleNameByUserId($user['id'])) }}
                            </div>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <div class="mt-5">
                                {{ App\Http\Controllers\UserController::getPrimaryPhoneByUserId($user['id']) }}
                            </div>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <div class="mt-5">
                                {{ $user['email'] }}
                            </div>
                        </td>
                        <td class="data-{{$user['id']}}">
                            @php
                                $listOfficesByUser = App\Repositories\Offices\OfficeRepository::getListOfficesByUser($user['id'], true);
                            @endphp
                            <div class="mt-5">
                                @if (!$listOfficesByUser->isEmpty())
                                    {{ implode(', ', $listOfficesByUser->toArray()) }}
                                @endif
                            </div>
                        </td>
                        <td class="data-{{$user['id']}}" id="specific-matter-column">
                            @php
                                $listSpecificMattersTitle = array();
                                $listSpecificMattersString = array();
                                $check = false;
                                $listSpecificMattersByUser = App\Repositories\SpecificMatters\SpecificMattersRepository::getListTypesByUser($user['id']);
                                foreach ($listSpecificMattersByUser as $key => $type) {
                                    $check = false;
                                    $listSpecificMattersTitle[] = $type['parent'][0]['name'] . '/' .  $type['name'];

                                    if ($key < 2) {
                                        $check = true;
                                        $listSpecificMattersString[] = $type['parent'][0]['name'] . '/' .  $type['name'];
                                    }
                                }

                                $listSpecificMattersTitle = implode(', ', $listSpecificMattersTitle);
                                $listSpecificMattersString = implode(', ', $listSpecificMattersString) . ((!$check && count($listSpecificMattersByUser) > 0) ? ' ...' : '');
                            @endphp
                            <div class="mt-5" title="{{ $listSpecificMattersTitle }}">
                                {{ $listSpecificMattersString }}
                            </div>
                            <div hidden>
                                {{ $listSpecificMattersTitle }}
                            </div>
                        </td>
                        <td class="data-{{$user['id']}}">
                            <div class="mt-5">
                                <span class="status-user
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
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
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
            });

            var statusFilter, roleFilter;
            var matterChecked = [], officeChecked = [];
            var countType, countOffice;

            // Count selected types
            $("#dropdown-specific-matter input[type=checkbox]").click(function() {
                countType = 0;
                $("#dropdown-specific-matter input[type=checkbox]:checked").each(function () {
                    countType++;
                });

                $("#fillter-type").text(countType == 0 ? 'All' : countType + ' item selected');
            });

            // Count selected offices
            $("#dropdown-office input[type=checkbox]").click(function() {
                countOffice = 0;
                $("#dropdown-office input[type=checkbox]:checked").each(function () {
                    countOffice++;
                });

                $("#fillter-office").text(countOffice == 0 ? 'All' : countOffice + ' item selected');
            });

            $('.btn-cancel-filter').click(function() {
                $("select#role-filter").val("All");
                $("select#status-filter").val("All");
                $("#dropdown-office :checkbox").prop("checked", false);
                $("#dropdown-specific-matter :checkbox").prop("checked", false);

                $("#fillter-office").text("All");
                $("#fillter-type").text("All");

                $('.btn-apply-filter').trigger('click');
            });

            $('.btn-apply-filter').on('click', function() {
                matterChecked = [];
                officeChecked = [];
                roleFilter = '';

                $.each($("input[name='fillter-specific-matters']:checked"), function() {
                    matterChecked.push($(this).val());
                });

                // office
                $.each($("input[name='filter-office']:checked"), function() {
                    officeChecked.push($(this).val());
                });

                table.search('').draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                roleFilter = $('#role-filter').val();
                let indexRole = data[1].trim().indexOf(roleFilter);

                statusFilter = $('#status-filter').val().toLowerCase();

                let flagMatter = matterChecked.length === 0 ? true : matterChecked.some(str => data[5].trim().toLowerCase().search(str.trim().toLowerCase()) > -1);

                // office
                let flagOffice = officeChecked.length === 0 ? true : officeChecked.some(str => data[4].trim().toLowerCase().search(str.trim().toLowerCase()) > -1);

                if((roleFilter == 'All' || indexRole > -1) && (statusFilter.trim() == 'all' || statusFilter == data[6].toLowerCase().trim()) && flagMatter && flagOffice) {
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

                $("#dropdown-office :checkbox").prop("checked", false);
                $("#dropdown-specific-matter :checkbox").prop("checked", false);

                $("#fillter-office").text("All");
                $("#fillter-type").text("All");

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
                $('.btn-filter-matter').removeClass('filter-select-clicked');
                $('.btn-filter-office').removeClass('filter-select-clicked');
                $('.btn-filter-role-user').removeClass('filter-select-clicked');
            });

            $('.dropdown-matter-user').on('click', function(event) {
                event.stopPropagation();
            });

            $('.btn-filter-matter').on('click', function(event) {
                $(this).toggleClass('filter-select-clicked');
            });

            $('.btn-filter-role-user').on('click', function(event) {
                $(this).toggleClass('filter-select-clicked');
            });

            $('.dropdown-office-user').on('click', function(event) {
                event.stopPropagation();
            });

            $('.btn-filter-office').on('click', function(event) {
                $(this).toggleClass('filter-select-clicked');
            });
        });

        // Search matter
        function searchMatter() {
            var input, filter, a, i;
            input = document.getElementById("filter-search-type");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown-specific-matter");
            a = div.getElementsByTagName("label");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }

        // Search office
        function searchOffice() {
            var input, filter, a, i;
            input = document.getElementById("filter-search-office");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown-office");
            a = div.getElementsByTagName("label");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
    </script>
@stop