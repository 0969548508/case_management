@can('view matters')
@extends('layouts.app')
<?php
    $columns = array('Case Number', 'Type', 'Status', 'Company Name', 'Location', 'Created Date', 'Due Date', 'Assignee');
?>

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-20">
    <h1 class="title mb-0">Matter Management</h1>
    <div class="dropdown-menu-right">
        @can('create matters')
            <a href="javascript:void(0);"><button type="button" id="btn-add-matter" class="d-none d-sm-inline-block btn btn-create-style">CREATE MATTER</button></a>
        @endcan
    </div>
</div>

<div class="row pl-3 d-sm-flex align-items-center table-style-matter">
    <div class="change-tab col-xl-2 col-md-3 card left-line matter-black mr-2 bg-white background-tab-black" data-color="background-tab-black" data-filter="all">
        <img class="img-tab img-tab-active img-tab-all" src="{{ asset('images/tab-all.png') }}" alt="">
        <span class="text-matter-act text-white">All</span>
        <div class="text-number text-black text-white">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus() }}</div>
        <span class="text-grey text-white">All matters are managing</span>
    </div>

    <div class="change-tab col-xl-2 col-md-3 card left-line matter-blue-1 mr-2 bg-white" data-color="background-tab-blue-1" data-filter="Not assigned">
        <img class="img-tab img-tab-not-assigned" src="{{ asset('images/tab-not-assigned.png') }}" alt="">
        <span class="text-matter-act">Not Assigned</span>
        <div class="text-number text-blue-1">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus('not-assigned') }}</div>
        <span class="text-grey">New matters need to assign</span>
    </div>

    <div class="change-tab col-xl-2 col-md-3 card left-line matter-purple mr-2 bg-white" data-color="background-tab-purple" data-filter="To-do">
        <img class="img-tab img-tab-to-do" src="{{ asset('images/tab-to-do.png') }}" alt="">
        <span class="text-matter-act">To-do</span>
        <div class="text-number text-purple">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus('to-do') }}</div>
        <span class="text-grey">New matters assigned to you</span>
    </div>

    <div class="col-xl-2 col-md-3 card mr-2 bg-white" id="tab-none-color">
        <img class="img-tab img-tab-in-progress" src="{{ asset('images/tab-in-progress.png') }}" alt="">
        <span id="in-progress" class="change-tab text-matter-act mb-0" data-filter="In-progress" data-color="background-tab-green-1" style="height: 33px !important;">In-progress
            <span style="font-size: 20px;" class="text-number text-green-1 position-absolute ml-2">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus('in-progress') }}</span>
        </span>
        <div class="w-100">
            <div id="need-review" class="change-tab col-6 p-0 float-left text-center" data-color="background-tab-red" data-filter="Need to review">
                <span class="matter-tab">Need review</span><br>
                <span class="badge badge-pill badge-danger matter-need-review">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus('need-review') }}</span>
            </div>
            <div id="billing" class="change-tab col-6 p-0 float-left text-center" data-color="background-tab-green" data-filter="Billing">
                <span class="matter-tab">Billing</span><br>
                <span class="badge badge-pill badge-danger matter-billing">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus('billing') }}</span>
            </div>
        </div>
    </div>

    <div class="change-tab col-xl-2 col-md-3 card left-line matter-blue mr-2 bg-white" data-color="background-tab-blue" data-filter="closed">
        <img class="img-tab img-tab-closed" src="{{ asset('images/tab-closed.png') }}" alt="">
        <span class="text-matter-act">Closed</span>
        <div class="text-number text-blue">{{ App\Repositories\Matter\MatterRepository::countMatterByStatus('closed') }}</div>
        <span class="text-grey">All matter was closed</span>
    </div>
</div>

<div style="display: inline-block; vertical-align: top;">
    <form role="search" class="user-search">
        <input id="matter-search-box" class="form-control search-box-list" type="text" placeholder="Search" aria-label="Search">
        <a href="#" class="active">
            <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
        </a>
    </form>
</div>
<div style="display: inline-block;" class="container-filter-columns pb-2">
    <div id="filter" class="filter" data-toggle="collapse" data-target="#filter-matter" aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i> Filters
    </div>
    <div class="show-column dropdown-toggle active" data-toggle="dropdown" id="dropdown-column">
        <i class="fa fa-bars" aria-hidden="true"></i> Columns
    </div>
    <div class="dropdown-menu dropdown-menu-right dropdown-column-user pt-2 pb-2" aria-labelledby="dropdown-column" id="column-setting">
        @foreach($columns as $key => $col)
            <label class="dropdown-item check-box">
                <input type="checkbox" class="role-checkbox" name="chkColSet" id="{{ $key }}" value="{{ $key }}" data-column="{{ $key }}" checked="checked">
                <span class="checkmark check-mark-user"></span>{{ $col }}
            </label>
        @endforeach
    </div>
</div>
<div class="collapse" id="filter-matter">
    <div class="card card-body show-filter">
        <div class="card-text">
            <div class="div-group">
                <span class="text-filter">Type</span>
                <div id="matter-filter" class="dropdown display-inline-block">
                    <button class="dropdown-toggle btn-filter-matter pl-2 pr-2" data-toggle="dropdown"><span id="fillter-type" class="pr-2 pl-2">All</span></button>
                    <ul class="dropdown-menu dropdown-matter-user py-2" id="dropdown-specific-matter">
                        <li class="user-search mx-1">
                            <input class="form-control matter-search-box border-0" id="filter-search-type" type="text" placeholder="Search" aria-label="Search" onkeyup="searchMatter();">
                            <i class="fa fa-search left-icon-search" aria-hidden="true"></i>
                        </li>
                        @foreach ($listTypes as $type)
                            @if (count($type->children) > 0)
                                <li class="px-2">
                                    @php
                                        $checkShowGroup = true;
                                    @endphp
                                    @foreach ($type->children as $subType)
                                        @if (in_array($subType->id, $listTypeIdByMatter))
                                            @if ($checkShowGroup)
                                                @php
                                                    $checkShowGroup = false;
                                                @endphp
                                                <span class="py-1 text-title-filter-matter">{{ $type->name }}</span>
                                            @endif
                                            <label class="dropdown-item text-subtitle-filter-matter mb-0 py-2 pr-1">
                                                <input type="checkbox" class="specific-matters-checkbox role-checkbox" name="fillter-specific-matters" value="{{ $type->name . '/' . $subType->name }}">
                                                <span class="checkmark check-mark-matter"></span>
                                                <div class="whitespace-pre-line display-inline-block">{{ $type->name . '/' . $subType->name }}</div>
                                            </label>
                                        @endif
                                    @endforeach
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="div-group">
                <span class="text-filter">Client - Location</span>
                <div class="dropdown display-inline-block">
                    <button class="dropdown-toggle btn-filter-client pl-2 pr-2" data-toggle="dropdown"><span id="fillter-client" class="pr-2 pl-2">All</span></button>
                    <ul class="dropdown-menu dropdown-client py-2" id="dropdown-client">
                        <li class="user-search mx-1">
                            <input class="form-control matter-search-box border-0" id="filter-search-client" type="text" placeholder="Search" aria-label="Search" onkeyup="searchClient();">
                            <i class="fa fa-search left-icon-search" aria-hidden="true"></i>
                        </li>
                        @foreach ($listLocationByMatter as $client)
                            @if (count($client->locations) > 0)
                                <li class="px-2">
                                    <span class="py-1 text-title-filter-matter">{{ $client->name }}</span>
                                    @foreach ($client->locations as $location)
                                        <label class="dropdown-item text-subtitle-filter-matter mb-0 py-2 pr-1">
                                            <input type="checkbox" class="specific-matters-checkbox role-checkbox" name="fillter-client" value="{{ $location->name }}" data-client = "{{ $client->name }}">
                                            <span class="checkmark check-mark-matter"></span>
                                            <div class="whitespace-pre-line display-inline-block">{{ $location->name }}</div>
                                        </label>
                                    @endforeach
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="div-group">
                <span class="text-filter">Created date</span>
                <span style="display: inline-block;">
                    <i class="far fa-calendar-alt" aria-hidden="true" style="position: absolute; margin-left: 125px; margin-top: 10px;"></i>
                    <input id="created-date-filter" type="text" class="btn-filter-status px-2 date-picker pl-3" autocomplete="off" placeholder="Select duration" value="" style="min-width: 150px;">
                </span>
            </div>
            <div class="div-group">
                <span class="text-filter">Due date</span>
                <span style="display: inline-block;">
                    <i class="far fa-calendar-alt" aria-hidden="true" style="position: absolute; margin-left: 125px; margin-top: 10px;"></i>
                    <input id="due-date-filter" type="text" class="btn-filter-status px-2 date-picker pl-3" autocomplete="off" placeholder="Select duration" value="" style="min-width: 150px;">
                </span>
            </div>
            <div class="div-group">
                <span class="text-filter">Assignee</span>
                <select class="custom-fillter selectpicker px-2" id="assignee-filter" data-live-search="true" title="All">
                    <option value="all">All</option>
                    @foreach ($listAssigneeByMatter as $user)
                        <option value="{{ $user->id }}">{{ ucwords($user->name . ' ' . $user->family_name) }}</option>
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
<div class="table-responsive">
    <table width="100%" id="matter_table" class="table">
        <thead>
            <tr class="column-name">
                @foreach ($columns as $title)
                    <th>{{ ucfirst($title) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="column-content bg-white" id="content-matter">
            @foreach ($listMatter as $matter)
                <tr class="row-user">
                    <td class="data-{{$matter->id}}">
                        <div class="mt-5">
                            <a href="{{ route('getMatterDetail', $matter->id) }}">
                                {{ $matter->case_number }}
                            </a>
                        </div>
                    </td>
                    <td class="data-{{$matter->id}} text-color-1D1F21">
                        <div class="mt-5">
                            @php
                                $typeName = App\Repositories\SpecificMatters\SpecificMattersRepository::getTypeBySubType($matter->type_id);
                            @endphp
                            @if (!empty($typeName))
                                <span class="font-weight-bold">{{ $typeName->parent[0]->name }}</span>/<span class="text-color-8B8B8B">{{ $typeName->name }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="data-{{$matter->id}}">
                        <div class="mt-5">
                        @switch($matter->last_state)
                            @case('not-assigned')
                                <span class="status-matter font-weight-bold background-status-not-assigned">
                                    Not assigned
                                </span>

                                @break
                            @case('to-do')
                                <span class="status-matter font-weight-bold background-status-to-do">
                                    To-do
                                </span>

                                @break
                            @case('in-progress')
                                <span class="status-matter font-weight-bold background-status-in-progress">
                                    In-progress
                                </span>

                                @break
                            @case('need-review')
                                <span class="status-matter font-weight-bold background-status-in-progress">
                                    In-progress
                                </span><br>
                                <span class="background-status-need-review">Need to review</span>

                                @break
                            @case('billing')
                                <span class="status-matter font-weight-bold background-status-in-progress">
                                    In-progress
                                </span><br>
                                <span class="background-status-billing">Billing</span>

                                @break
                            @case('cancelled')
                                <span class="status-matter font-weight-bold background-status-cancelled">
                                    Cancelled
                                </span>

                                @break
                            @case('completed')
                                <span class="status-matter font-weight-bold background-status-completed">
                                    Completed
                                </span>

                                @break
                            @case('on-hold')
                                <span class="status-matter font-weight-bold background-status-on-hold">
                                    On-hold
                                </span>

                                @break
                            @case('invalid')
                                <span class="status-matter font-weight-bold background-status-invalid">
                                    Invalid
                                </span>

                                @break
                            @case('withdrawn')
                                <span class="status-matter font-weight-bold background-status-withdrawn">
                                    Withdrawn
                                </span>

                                @break
                            @default
                                @break
                        @endswitch
                        </div>
                    </td>
                    <td class="data-{{$matter->id}}">
                        <div class="mt-5">
                            @if (!empty($matter->clients))
                                {{ $matter->clients->name }}
                            @endif
                        </div>
                    </td>
                    <td class="data-{{$matter->id}}">
                        <div class="mt-5">
                            @if (!empty($matter->locations))
                                {{ $matter->locations->name }}
                            @endif
                        </div>
                    </td>
                    <td class="data-{{$matter->id}} text-color-1D1F21">
                        <div class="mt-5">
                            {{ $matter->created_at->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="data-{{$matter->id}} text-color-1D1F21">
                        <div class="mt-5">
                        </div>
                    </td>
                    <td class="data-{{$matter->id}}">
                        <div class="mt-5">
                            @php
                                $countUsersAssigned = DB::table('cases_users')->where('case_id', $matter->id)->count();
                                $assignedUsers = DB::table('cases_users')->where('case_id', $matter->id)->get()->toArray();
                            @endphp
                            @if ($countUsersAssigned > 0)
                                {{ $countUsersAssigned }} User(s)
                            @endif
                            <div hidden>
                                @foreach($assignedUsers as $user)
                                    {{ $user->user_id }},
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- modal add matter -->
<div class="modal fade" id="modal-add-matter" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="add-matter-form" action="{{ route('createMatter') }}">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Create Matter') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Client *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select class="form-control selectpicker" name="client_id" id="client-add" data-live-search="true" title="Select Client" required>
                                @foreach ($listClient as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Location *') }}</label>

                        <div class="col-md-12 col-lg-9" id="select-location-add">
                            <select class="form-control selectpicker" name="location_id" id="location-add" required data-live-search="true" title="Select Location" disabled>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Type *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select class="form-control selectpicker" data-live-search="true" name="type_id" id="type-add" title="Select Type - Subtype" required>
                                @foreach ($listTypes as $type)
                                    @if (count($type->children) > 0)
                                        <optgroup label="{{ $type->name }}">
                                            @foreach ($type->children as $subType)
                                                <option value="{{ $subType->id }}">{{ $subType->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Office *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select class="form-control selectpicker" name="office_id" id="office-add" data-live-search="true" title="Select Office" required>
                                @foreach ($listOffices as $office)
                                    <option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-body border-top-05">
                    <h5 class="matter-assign-title">Assign matter to user</h5>
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('User name') }}</label>

                        <div class="col-md-12 col-lg-9" id="select-user-add">
                            <select class="form-control selectpicker" name="assign_users[]" id="assign-users" data-live-search="true" data-selected-text-format="count" title="Select User" multiple disabled>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="submit" class="btn btn-add-modal" id="btn-add-matter-modal">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
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

        $(document).ready(function() {
            var oldColor = 'background-tab-black';

            $('#matter_table').DataTable({
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
                    "infoFiltered": "",
                    "infoEmpty": "",
                },
                "sDom": 'Rfrtlip',
                "order": [],
            });

            var table = $('#matter_table').DataTable();

            $("input.date-picker").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            });

            // Search input
            $('#matter-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );

            $('.table-style-matter .change-tab').click(function() {
                var color = $(this).data('color');
                var valueFilter = $(this).data('filter');
                var checkClosedStatus = false;

                $('.table-style-matter').find('.change-tab').removeClass(oldColor);
                $('.change-tab').children().removeClass('text-white');
                $('.change-tab').find('img').removeClass('img-tab-active');
                $('.matter-need-review').removeClass('active-need-review');
                $('.matter-billing').removeClass('active-billing');

                if (color == 'background-tab-red') {
                    $('.matter-need-review').addClass('active-need-review');
                } else if (color == 'background-tab-green') {
                    $('.matter-billing').addClass('active-billing');
                }

                if (valueFilter == 'all') {
                    valueFilter = '';
                } else if (valueFilter == 'closed') {
                    checkClosedStatus = true;
                    let statusArray = ['cancelled', 'completed', 'on-hold', 'invalid', 'withdrawn'];
                    valueFilter = [];
                    statusArray.forEach(function(item, index) {
                        valueFilter.push('(?=.*' + item + ')');
                    });
                }

                if (table.column(2).search() !== valueFilter) {
                    if (checkClosedStatus == true) {
                        table.column(2)
                            .search(valueFilter.join('|'), true, false, true)
                            .draw();
                    } else {
                        table.column(2)
                            .search(valueFilter)
                            .draw();
                    }
                }

                $('#matter-search-box').trigger('keyup');

                $(this).addClass(color);
                $(this).children().addClass('text-white');
                $(this).find('img').addClass('img-tab-active');
                oldColor = color;
            });

            // show modal
            $('#btn-add-matter').click(function() {
                $('#modal-add-matter').modal('show');
            });

            // get list Locations
            $('#client-add').change(function() {
                var data = {
                    'clientId' : $(this).val()
                };

                getListLocationsByClientId(data, 'add');
            });

            function getListLocationsByClientId(data, type) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('ajaxGetListLocation') }}",
                    method: 'get',
                    data: data,
                    success: function(response) {
                        if (type == 'add') {
                            $("#select-location-add").html(response.html);
                        }
                    }
                });
            }

            // get list Users
            $('#type-add').change(function() {
                var data = {
                    'officeId' : $('#office-add').val(),
                    'typeId'   : $(this).val()
                };

                getListUsersByOfficeId(data, 'add');
            });

            $('#office-add').change(function() {
                var data = {
                    'officeId' : $(this).val(),
                    'typeId'   : $('#type-add').val()
                };

                getListUsersByOfficeId(data, 'add');
            });

            function getListUsersByOfficeId(data, type) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('getListUserByOfficeAndType') }}",
                    method: 'get',
                    data: data,
                    success: function(response) {
                        if (type == 'add') {
                            $("#select-user-add").html(response.html);
                        }
                    }
                });
            }

            // Filter
            var matterChecked = [], countType;
            var locationChecked = [], clientChecked = [], countClient;
            var createdDate, dueDate;
            var valAssignee, clientName;

            // Count selected types
            $("#dropdown-specific-matter input[type=checkbox]").click(function() {
                countType = 0;
                $("#dropdown-specific-matter input[type=checkbox]:checked").each(function () {
                    countType++;
                });

                $("#fillter-type").text(countType == 0 ? 'All' : countType + ' item selected');
            });
            // Count selected client - location
            $("#dropdown-client input[type=checkbox]").click(function() {
                countClient = 0;
                $("#dropdown-client input[type=checkbox]:checked").each(function () {
                    countClient++;
                });

                $("#fillter-client").text(countClient == 0 ? 'All' : countClient + ' item selected');
            });

            $('.btn-apply-filter').on('click', function() {
                matterChecked = [];
                locationChecked = [];
                clientChecked = [];
                valAssignee = '';
                clientName = '';
                // type
                $.each($("input[name='fillter-specific-matters']:checked"), function() {
                    matterChecked.push($(this).val());
                });
                // client - location
                $.each($("input[name='fillter-client']:checked"), function() {
                    locationChecked.push($(this).val());
                    clientName = $(this).attr("data-client");
                    clientChecked.push(clientName);
                });

                table.search('').draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // type
                let flagMatter = matterChecked.length === 0 ? true : matterChecked.some(str => data[1].trim().toLowerCase().search(str.trim().toLowerCase()) > -1);
                // client - location
                let flagLocation = locationChecked.length === 0 ? true : locationChecked.some(str => data[4].trim().toLowerCase().search(str.trim().toLowerCase()) > -1);
                let flagClient = clientChecked.length === 0 ? true : clientChecked.some(str => data[3].trim().toLowerCase().search(str.trim().toLowerCase()) > -1);
                // date
                createdDate = $('#created-date-filter').val();
                dueDate = $('#due-date-filter').val();
                // assignee
                valAssignee = $('#assignee-filter').val();
                let indexAssignee = data[7].trim().indexOf(valAssignee);

                if(flagMatter && flagLocation && flagClient && (createdDate == '' || createdDate == data[5].trim()) && (dueDate == '' || dueDate == data[6].trim()) && (valAssignee == 'all' || indexAssignee > -1)) {
                    return true;
                }
                return false;
            });

            $('.btn-cancel-filter').click(function() {
                // type
                $("#dropdown-specific-matter :checkbox").prop("checked", false);
                $("#fillter-type").text("All");
                // client - location
                $("#dropdown-client :checkbox").prop("checked", false);
                $("#fillter-client").text("All");
                // date
                $('#created-date-filter').val('');
                $('#due-date-filter').val('');
                // assignee
                $('#assignee-filter').val('all');
                $('.filter-option-inner-inner').text('All');
                $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'All');

                $('.btn-apply-filter').trigger('click');
            });
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#matter_table").on('click', 'th', function() {
            $("#matter_table thead th").each(function(i, th) {
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

        $("#matter_table th").first().click().click();
        /* End */

        $(document).on('click', '#column-setting .dropdown-item', function(event){
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
                // type
                $("#dropdown-specific-matter :checkbox").prop("checked", false);
                $("#fillter-type").text("All");
                // Client-location
                $("#dropdown-client :checkbox").prop("checked", false);
                $("#fillter-client").text("All");
                // date
                $('#created-date-filter').val('');
                $('#due-date-filter').val('');
                // assignee
                $('#assignee-filter').val('all');
                $('.filter-option-inner-inner').text('All');
                $('button.btn.dropdown-toggle.border-btn-select').attr('title', 'All');

                $('div#filter-matter').removeClass('show');
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
                $('.btn-filter-client').removeClass('filter-select-clicked');
            });

            $('.dropdown-matter-user').on('click', function(event) {
                event.stopPropagation();
            });

            $('.btn-filter-matter').on('click', function(event) {
                $(this).toggleClass('filter-select-clicked');
            });

            $('.dropdown-client').on('click', function(event) {
                event.stopPropagation();
            });

            $('.btn-filter-client').on('click', function(event) {
                $(this).toggleClass('filter-select-clicked');
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
        //Search client - location
        function searchClient() {
            var input, filter, a, i;
            input = document.getElementById("filter-search-client");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown-client");
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