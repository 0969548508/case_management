@extends('layouts.app')
<?php
    $titleTable = ['Location', 'Contacts', 'Active Cases', 'Last Case', 'Status'];
    $columns = ['', 'Contacts', 'Active Cases', 'Last Case', 'Status'];
?>
@section('content')
@include('clients.header-client-detail')

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link border-0 active" href="{{ route('showDetailClient', $clientDetail['id']) }}" style="font-weight:bold;">Location List</a>
            </li>
            @can('view client contacts')
                <li class="nav-item">
                    <a class="nav-link border-0" href="{{ route('showContactListClient', $clientDetail['id']) }}" style="font-weight:bold;">Contact List</a>
                </li>
            @endcan
            @can('view client price list')
                <li class="nav-item">
                    <a class="nav-link border-0" href="{{ route('showPriceListClient', $clientDetail['id']) }}" style="font-weight:bold;">Price List</a>
                </li>
            @endcan
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body tab-content custom-location-table border-0">
                <div style="display: inline-block; vertical-align: top;">
                    <form role="search" class="user-search">
                        <input id="location-search-box" class="form-control search-box-list" type="text" placeholder="Search" aria-label="Search">
                        <a href="#" class="active">
                            <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
                        </a>
                    </form>
                </div>
                <div style="display: inline-block;" class="container-filter-columns pb-2">
                    <div id="filter" style="border: 1px solid #E5E5E5;" class="filter" data-toggle="collapse" data-target="#filter-user" aria-expanded="false">
                        <i class="fa fa-filter" aria-hidden="true"></i> Filters
                    </div>
                    <div style="border: 1px solid #E5E5E5;" class="show-column dropdown-toggle active" data-toggle="dropdown" id="dropdown-column">
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

                <table id="location-list" class="table mt-4">
                    <thead>
                        <tr class="column-name">
                            @foreach ($titleTable as $title)
                                <th>{{ ucfirst($title) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="column-content bg-white location-body">
                        @foreach($listLocations as $location)
                            @if ($location->trash == 0)
                                <tr>
                                    <td>
                                        @can('edit client and client locations information')
                                            <a href="{{ route('showStatiticLocation', $location->id) }}">
                                                {{ ucwords($location->name) }}
                                            </a>
                                        @else
                                            {{ ucwords($location->name) }}
                                        @endcan
                                        @if($location->is_primary)
                                            <span class="ml-5 text-primary">Primary</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="ml-4">
                                            {{ DB::table('contacts_list')->where('location_id', $location->id)->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="ml-4">
                                            {{ DB::table('cases')->where('location_id', $location->id)->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $day = DB::table('cases')->select('created_at')->where('location_id', $location->id)->orderBy('created_at', 'DESC')->first();
                                        @endphp
                                        @if (!empty($day))
                                            {{ date_format(date_create($day->created_at), 'd/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="custom-status">
                                        <span id="status-location" class="status-client {{ ($location->status == 0) ? 'inactive-client bg-inactive-client' : 'active-client bg-active-client'}}">{{ ($location->status == 0) ? 'Inactive' : 'Active' }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @include('clients.script-client-detail')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#location-list').DataTable({
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

            var table = $('#location-list').DataTable();

            // Search input
            $('#location-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );

            rows = $('#location-list').DataTable().rows({ 'search': 'applied' }).nodes();

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

        $("#location-list").on('click', 'th', function() {
            $("#location-list thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                cls = $(th).attr('class');
                var arrayName = ['Location', 'Contacts', 'Active Cases', 'Last Case', 'Status'];
                switch (cls) {
                    case 'sorting_asc':
                        if (arrayName.indexOf(html) != -1) {
                            $(th).html(html+spanAsc);
                        }
                        break;
                    case 'sorting_desc':
                        if (arrayName.indexOf(html) != -1) {
                            $(th).html(html+spanDesc);
                        }
                        break;
                    default:
                        if (arrayName.indexOf(html) != -1) {
                            $(th).html(html+spanSorting);
                        }
                        break;
                }
            });
        });

        $("#location-list th").first().click().click();

        $(document).on('click', '#column-setting .dropdown-item', function(event){
            event.stopPropagation();
            $('.container-filter-columns').addClass('show');
            $("#dropdown-column").attr("aria-expanded","true");
            $('#column-setting').addClass('show');
        });
    </script>
@stop