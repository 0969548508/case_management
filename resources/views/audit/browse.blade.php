@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-20">
        <h1 class="title mb-0">Audit Logs</h1>
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
        <table width="100%" id="audit_table" class="table">
            <thead>
                <tr class="column-name">
                    @foreach ($columns as $col)
                        <th>{{ ucfirst($col) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="column-content bg-white">
                @foreach ($listAuditLogs as $audit)
                    @php
                        $userDetail = App\Repositories\Users\UserRepository::getDetailUserById($audit->user_id);

                        switch($audit->tags) {
                            case 'user':
                                $columns = array('name', 'family_name');
                                break;

                            case 'matter':
                                $columns = array('case_number');
                                break;

                            default:
                                $columns = array('name');
                                break;
                        }

                        $info = App\Repositories\Audit\AuditRepository::getInfoByModel($audit->auditable_type, $audit->auditable_id, $columns);

                        $name = "";
                        if (!empty($info)) {
                            switch($audit->tags) {
                                case 'user':
                                    $name = "'" . $info->name . ' ' . $info->family_name . "'";
                                    break;

                                case 'matter':
                                    $name = "'" . $info->case_number . "'";
                                    break;

                                default:
                                    $name = "'" . $info->name . "'";
                                    break;
                            }
                        }

                        // check show data
                        $checkShow = true;
                        if (empty($info)) {
                            $checkShow = false;
                            if ($audit->event != 'updated') {
                                $checkShow = true;
                            }
                        }
                    @endphp
                    @if (!isset($userDetail['message']) && $checkShow)
                        <tr>
                            <td class="data-{{$audit->id}} has-image">
                                @if (!empty($userDetail->image))
                                    <img src="{{ $userDetail->image }}" alt="avata {{ $userDetail->name }}" class="avata-client float-left">
                                @else
                                    <div class="member float-left">
                                        <span class="avata-client member-initials">{{ substr($userDetail->name, 0, 1) . '' . substr($userDetail->family_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="mt-5 text-below-image">
                                    {{ ucwords($userDetail->name) . ' ' . ucwords($userDetail->family_name) }}
                                </div>
                            </td>
                            <td class="data-{{$audit->id}} text-dark">
                                <div class="mt-5">
                                    {{ ucwords(App\Repositories\Users\UserRepository::getRoleNameByUserId($audit->user_id)) }}
                                </div>
                            </td>
                            <td class="data-{{$audit->id}} text-dark">
                                <div class="mt-5">
                                    {{ $audit->created_at->format('d/m/Y h:i:s') }}
                                </div>
                            </td>
                            <td class="data-{{$audit->id}} text-dark">
                                <div class="mt-5">
                                    @include('audit.action')
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
            $('#audit_table').DataTable({
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
                    { "width": "20%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "50%", "targets": 3 },
                ]
            });
            var table = $('#audit_table').DataTable();
            // Search input
            $('#user-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );
        });
        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#audit_table").on('click', 'th', function() {
            $("#audit_table thead th").each(function(i, th) {
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

        $("#audit_table th").first().click().click();
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