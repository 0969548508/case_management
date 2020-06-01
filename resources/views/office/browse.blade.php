@extends('layouts.app')
<?php
    $columns = array('Name', 'Address', 'Phone', 'Fax', 'State', '');
?>

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-20">
    <h1 class="title mb-0">Office List</h1>
    <div class="dropdown-menu-right">
        @can('create offices')
            <a href="javascript:void(0);"><button type="button" id="btn-add-office" class="d-none d-sm-inline-block btn btn-create-style">CREATE OFFICE</button></a>
        @endcan
    </div>
</div>

<div style="display: inline-block; vertical-align: top;">
    <form role="search" class="user-search">
        <input id="office-search-box" class="form-control search-box-list" type="text" placeholder="Search" aria-label="Search">
        <a href="#" class="active">
            <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
        </a>
    </form>
</div>
<div style="display: inline-block;" class="container-filter-columns pb-2">
    <div id="filter" class="filter" data-toggle="collapse" data-target="#filter-office" aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i> Filters
    </div>
</div>
<div class="collapse" id="filter-office">
    <div class="card card-body show-filter">
        <div class="card-text">
            <span class="text-filter">State</span>
            <select id="status-filter" class="btn-filter-role px-2">
                <option>All</option>
            </select>
            <div class="float-lg-right float-md-right">
                <button type="button" class="btn btn-cancel-filter pt-0">Cancel</button>
                <button type="button" class="btn btn-apply-filter pt-0 mr-40">Apply</button>
                <div class="x-close" id="close-dropdown-filter">&times;</div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table width="100%" id="office_table" class="table">
        <thead>
            <tr class="column-name">
                @foreach ($columns as $title)
                    <th>{{ ucfirst($title) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="column-content bg-white" id="content-office">
            @foreach ($listOffice as $office)
                <tr id="input-data-{{ $office->id }}">
                    <td class="data-{{$office->id}}">
                        <div class="mt-5">
                            {{ $office->name }}
                        </div>
                    </td>
                    <td class="data-{{$office->id}}">
                        <div class="mt-5">
                            {{ $office->address }}
                        </div>
                    </td>
                    <td class="data-{{$office->id}}">
                        <div class="mt-5">
                            {{ $office->phone_number }}
                        </div>
                    </td>
                    <td class="data-{{$office->id}}">
                        <div class="mt-5">
                            {{ $office->fax_number }}
                        </div>
                    </td>
                    <td class="data-{{$office->id}}">
                        <div class="mt-5">
                            @if (!empty($office->state))
                                @php
                                    $stateDetail = App\Repositories\Offices\OfficeRepository::getStateDetail(array('name', 'state_code'), $office->state);
                                @endphp
                                @if (!empty($stateDetail))
                                    {{ $stateDetail[0]->name }} ({{ $stateDetail[0]->state_code }})
                                @endif
                            @endif
                        </div>
                    </td>
                    <td class="data-{{$office->id}}">
                        <div class="mt-5">
                            @can('edit offices')
                                <span class="cursor-pointer mr-3" id="edit-{{$office->id}}" onclick="showEditForm('{{ $office->id }}')">
                                    <img class="office-img" src="/images/btn_pen_black.png">
                                </span>
                            @endcan
                            @can('delete offices')
                                @if (DB::table('users_belong_offices')->where('office_id', $office->id)->count() == 0)
                                    <span class="cursor-pointer" id="delete-{{$office->id}}" onclick="showDeleteForm('{{ $office->id }}')">
                                        <img class="office-img" src="/images/img-delete-black.png">
                                    </span>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- modal add office -->
<div class="modal fade" id="modal-add-office" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="add-office-form" action="{{ route('createOffice') }}">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Create Office') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Office Name *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Input name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Address *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required autocomplete="address" placeholder="Input address" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Country *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select id="select-country" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a country" name="country" required>
                                <option value="" selected>Choose a country</option>
                                @foreach ($listCountries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('State *') }}</label>

                        <div class="col-md-12 col-lg-9" id="select-state-form">
                            <select id="select-state" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a state" name="state" disabled>
                                <option value="" selected>Choose a state</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('City *') }}</label>

                        <div class="col-md-12 col-lg-9" id="select-city-form">
                            <select id="select-city" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a city" name="city" disabled>
                                <option value="" selected>Choose a city</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Postcode *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="postal-code" type="text" class="form-control" name="postal_code" value="{{ old('postal_code') }}" required autocomplete="postal_code" placeholder="Input postcode" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Phone *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="phone-number" type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="phone_number" placeholder="Input phone number" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Fax') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="fax-number" type="text" class="form-control" name="fax_number" value="{{ old('fax_number') }}" autocomplete="fax_number" placeholder="Input fax number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="submit" class="btn btn-add-modal" id="btn-add-office-modal">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="form-update-office">    
</div>

<!-- modal delete office -->
<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitle">{{ __('Delete Office') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete the office?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <form action="" method="POST" id="delete-form">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-delete-modal">{{ __('DELETE') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script type="text/javascript">
        var table;

        // show modal add office
        $('#btn-add-office').click(function() {
            // refesh modal
            $('input#name').val();
            $('input#address').val();
            $('input#postal-code').val();
            $('input#phone-number').val();
            $('input#fax-number').val();

            $('#modal-add-office').modal('show');
        });

        $('#select-country').change(function() {
            var countryId = $(this).val();
            var url = "{{ route('getListStates', '__countryId') }}".replace('__countryId', countryId);
            if (countryId) {
                $.ajax({
                    type: "get",
                    url: url,
                    success: function(response) {
                        $("#select-state-form").html(response.html);
                        let html = '<select id="select-city" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a city" name="city" disabled><option value="" selected>Choose a city</option></select>';
                        $("#select-city-form").html(html);
                    }
                });
            }
        });

        var checkShowModelEdit = true;
        // show modal edit office
        function showEditForm(officeId) {
            var url = "{{ route('showEditForm', '__officeId') }}".replace('__officeId', officeId);
            if (officeId && checkShowModelEdit) {
                checkShowModelEdit = false;
                $.ajax({
                    type: "get",
                    url: url,
                    success: function(response) {
                        checkShowModelEdit = true;
                        $("#form-update-office").html(response.html);
                    }
                });
            }
        }

        // show modal delete office
        function showDeleteForm(officeId) {
            var url = "{{ route('showDeleteForm', '__officeId') }}".replace('__officeId', officeId);
            $('#delete-form').attr('action', url);

            $('#modal-delete').modal('show');
        }

        $(document).ready(function() {
            var oldColor = 'background-tab-black';

            $('#office_table').DataTable({
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
                "columnDefs": [
                    { "orderable": false, "targets": 5 }
                ]
            });

            table = $('#office_table').DataTable();

            // Search input
            $('#office-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#office_table").on('click', 'th', function() {
            $("#office_table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                    cls = $(th).attr('class');
                if (html != '') {
                    switch (cls) {
                        case 'sorting_asc' :
                            $(th).html(html+spanAsc); break;
                        case 'sorting_desc' :
                            $(th).html(html+spanDesc); break;
                        default :
                            $(th).html(html+spanSorting); break;
                    }
                }
            });
        });

        $("#office_table th").first().click().click();
        /* End */

        $(document).on('click', '.dropdown-item', function(event){
            event.stopPropagation();
            $('.container-filter-columns').addClass('show');
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