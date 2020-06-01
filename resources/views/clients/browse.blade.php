@extends('layouts.app')

@section('content')
	<div class="d-sm-flex align-items-center justify-content-between mb-20">
	    <h1 class="title mb-0">Clients Management</h1>
        <div class="dropdown-menu-right">
            <a href="{{ route('showListTrashClient') }}" class="in-trash-user">View Deleted Clients</a>
            @can('create clients and client locations')
                <a href="{{ route('showCreateClient') }}"><button type="button" class="d-none d-sm-inline-block btn btn-create-style">CREATE CLIENT</button></a>
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
	<div class="table-responsive">
	    <table width="100%" id="client_table" class="table">
	        <thead>
	            <tr class="column-name">
	                @foreach ($columns as $col)
	                    <th>{{ ucfirst($col) }}</th>
	                @endforeach
	            </tr>
	        </thead>
	        <tbody class="column-content bg-white">
				@foreach ($listClients as $client)
                    @if (!$client->in_trash)
                        <tr>
                            <td class="data-{{$client->id}} has-image">
                                @if (!empty($client->image))
                                    <img src="{{ $client->image }}" alt="avata {{ $client->name }}" class="avata-client float-left">
                                @else
                                    <div class="member float-left">
                                        <span class="avata-client member-initials">{{ substr($client->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="mt-5 text-below-image">
                                    <a href="{{ route('showDetailClient', $client->id) }}" class="no-underline">
                                        {{ ucfirst($client->name) }}
                                    </a>
                                </div>
                            </td>
                            <td class="data-{{$client->id}}">
                                <div class="mt-5 ml-4">
                                    {{ DB::table('locations')->where('client_id', $client->id)->count() }}
                                </div>
                            </td>
                            <td class="data-{{$client->id}}">
                                <div class="mt-5 ml-4">
                                    {{ DB::table('contacts_list')->where('client_id', $client->id)->count() }}
                                </div>
                            </td>
                            <td class="data-{{$client->id}}">
                                <div class="mt-5 ml-4">
                                    {{ DB::table('cases')->where('client_id', $client->id)->count() }}
                                </div>
                            </td>
                            <td class="data-{{$client->id}}">
                                <div class="mt-5">
                                    <span class="status-client {{ ($client->status == 0) ? 'inactive-client bg-inactive-client' : 'active-client bg-active-client'}}">{{ ($client->status == 0) ? 'Inactive' : 'Active' }}</span>
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
            $('#client_table').DataTable({
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
            var table = $('#client_table').DataTable();
            // Search input
            $('#user-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );
        });
        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#client_table").on('click', 'th', function() {
            $("#client_table thead th").each(function(i, th) {
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

        $("#client_table th").first().click().click();
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
    </script>
@stop