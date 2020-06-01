@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement', 'Eidt Information', 'Deactivate Client'];
    $titleTable = ['Name', 'Phone', 'Fax', 'Email', 'Note', '', ''];
    $selectActions = ['Send Mail', 'Edit Contact', 'Delete Contact'];
?>
@section('content')
@include('clients.header-client-detail')

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link border-0" href="{{ route('showDetailClient', $clientDetail['id']) }}" style="font-weight:bold;">Location List</a>
            </li>
            @can('view client contacts')
                <li class="nav-item">
                    <a class="nav-link border-0 {{ $activeTab ? 'active':''}}" href="{{ route('showContactListClient', $clientDetail['id']) }}" style="font-weight:bold;">Contact List</a>
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

@can('view client contacts')
<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body tab-content recent-cases">
                <div class="row mb-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Contacts List</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="row ml-0">
                            @can('delete client contacts')
                                <div id="hide-btn-delete" class="mr-3">
                                    <button type="button" class="form-control custom-action-location custom-btn-delete" id="btn-delete-contact">DELETE</button>
                                </div>
                            @endcan
                            <div style="display: inline-block; vertical-align: top;">
                                <form role="search" class="user-search">
                                    <input id="contact-search-box" class="form-control search-box-list" type="text" placeholder="Search" aria-label="Search">
                                    <a href="#" class="active">
                                        <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 custom-location-table">
                        <table id="contact-list-table" class="table">
                            <thead>
                                <tr class="column-name">
                                    @foreach ($titleTable as $title)
                                        @if ($title != '')
                                            @if ($title == 'Name')
                                                @can('delete client contacts')
                                                    <th><label class="col-md-1 mb-0"><input class="role-checkbox select_all" type="checkbox" id="checkbox_select_all" onClick="toggle('checkbox_select_all')"><span class="checkmark"></span></label></th>
                                                @endcan
                                                <th>{{ ucfirst($title) }}</th>
                                            @else
                                                <th>{{ ucfirst($title) }}</th>
                                            @endif
                                        @else
                                            <th hidden>{{ ucfirst($title) }}</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="column-content bg-white location-body">
                                @foreach($contactList as $key => $contact)
                                    <tr id="input-data-{{ $contact['id'] }}">
                                        @can('delete client contacts')
                                        <td style="font-weight:bold; width:10px;" class="custom-color-location">
                                            <label class="col-md-1">
                                                <input class="role-checkbox item_checkbox" type="checkbox" name="row_id" id="{{ $contact['id'] }}" value="{{ $key }}" onClick="toggle('{{ $contact['id'] }}')"><span class="checkmark"></span>
                                            </label>
                                        </td>
                                        @endcan
                                        <td class="custom-color-location">
                                            @can('edit client contacts')
                                            <a href="javascript:void(0)" id="{{ $contact['id'] }}" name="{{ $contact['name'] }}" onclick="openEditForm('{{ $contact['id'] }}')">
                                                {{ ucfirst($contact['name']) }}
                                            </a>
                                            @else
                                                {{ ucfirst($contact['name']) }}
                                            @endif
                                        </td>
                                        <td class="custom-color-location">{{ ucfirst($contact['phone']) }}</td>
                                        <td class="custom-color-location">{{ ucfirst($contact['fax']) }}</td>
                                        <td>{{ $contact['email'] }}</td>
                                        <td class="custom-color-location">{{ ucfirst($contact['note']) }}</td>
                                        <td hidden class="custom-color-location">{{ ucfirst($contact['job_title']) }}</td>
                                        <td hidden class="custom-color-location">{{ ucfirst($contact['mobile']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- modal edit contact -->
<div class="modal fade" id="modal-edit-contact" tabindex="-1" role="dialog" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="#" id="edit-contact-form">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="editTitle">{{ __('Edit Contact') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label for="edit-name" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Name *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-name" type="text" class="form-control" name="edit-name" value="{{ old('edit-name') }}" required autocomplete="edit-name" autofocus placeholder="Input name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit_job_title" class="col-md-12 col-lg-3 col-form-label">{{ __('Job Title *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit_job_title" type="text" class="form-control" name="edit_job_title" value="{{ old('edit_job_title') }}" required autocomplete="edit_job_title" placeholder="Input job title" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-email" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Email *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-email" type="email" class="form-control" name="edit-email" value="{{ old('edit-email') }}" required autocomplete="edit-email" placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-phone" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Phone *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-phone" type="text" class="form-control" name="edit-phone" value="{{ old('edit-phone') }}" required autocomplete="edit-phone" placeholder="Input phone" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-mobile" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Mobile') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-mobile" type="text" class="form-control" name="edit-mobile" value="{{ old('edit-mobile') }}" autocomplete="edit-mobile" placeholder="Input mobile" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-fax" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Fax') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-fax" type="text" class="form-control" name="edit-fax" value="{{ old('edit-fax') }}" autocomplete="edit-fax" placeholder="Input fax">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-note" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Note') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <textarea rows="3" style="width:100%;" class="form-control" name="edit-note" id="edit-note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="submit" class="btn btn-add-modal" id="btn-edit-contact-modal">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal delete contact -->
<div class="modal fade" id="modal-delete-contact" tabindex="-1" role="dialog" aria-labelledby="deleteContactTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteContactTitle">{{ __('Delete contact') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete the contact?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('NO') }}</button>
                <button type="button" class="btn btn-delete-modal" id="btn-delete-contact-modal">{{ __('YES') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    @include('clients.script-client-detail')
    <script type="text/javascript">
        var table
        $(document).ready(function() {
            $('#contact-list-table').DataTable({
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
                "order": [[ 1, "asc" ]],
            });

            table = $('#contact-list-table').DataTable();

            // Search input
            $('#contact-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );

            rows = $('#contact-list-table').DataTable().rows({ 'search': 'applied' }).nodes();

            // delete contact list
            $('#btn-delete-contact-modal').click(function() {
                var url = "{{ route('deleteContactFromClient', ['clientId'=>$clientDetail['id']]) }}";
                if (selectedContact.length == 0) {
                    $('#modal-delete-contact').modal('hide');
                    return toastr.error('Please choose at least one contact to delete!');
                }
                var data = {selectedContact};
                var result = sendAjaxDelete(url, data);

                if (result) {
                    $('#modal-delete-contact').modal('hide');
                    for (var i = 0; i < selectedContact.length; i++) {
                        table.row('#input-data-'+selectedContact[i]).remove().draw(false);
                    }
                    selectedContact = [];

                    // check null data when delete contact
                    var count = $("table#contact-list-table").dataTable().fnSettings().aoData.length;
                    if (count == 0)
                    {
                        $("#checkbox_select_all").prop("checked", false);
                    }
                }
            });
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#contact-list-table").on('click', 'th', function() {
            $("#contact-list-table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                cls = $(th).attr('class');
                var arrayName = ['Name'];
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

        $("#contact-list-table th").first().click().click();

        // select all
        var selectedContact = [];
        function toggle(source) {
            if(source !== 'checkSubscription'){
                if(selectedContact.indexOf(source) > -1){
                    if(selectedContact.length > 0){
                        for(var j = 0; j < selectedContact.length; j++){
                            if(selectedContact[j] === source){
                                selectedContact.splice(j, 1);
                            }
                        }
                    }
                }else{
                    selectedContact.push(source);
                }
            }

            var check_all = 0;
            let parents = document.getElementById(source);
            if(typeof source != 'undefined'){
                if(!parents.checked){
                    let div_header = $('#checkbox_select_all').get();
                    if(div_header[0].checked){
                        div_header[0].checked = false;
                    }
                }else{
                    let div_col = $('.item_checkbox', rows).get();
                    for(var j = 0; j < div_col.length; j++){
                        if(div_col[j].checked){
                            check_all++;
                        }
                    }
                    if(check_all == div_col.length){
                        let div_header = $('#checkbox_select_all').get();
                        if(!div_header[0].checked){
                            div_header[0].checked = true;
                        }
                    }
                }
            }

            if(source == 'checkbox_select_all'){
                selectedContact = [];

                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                var rows_selected = $('.item_checkbox', rows).get();
                for(var j = 0; j<rows_selected.length; j++){
                    rows_selected[j].checked = parents.checked;
                    if(rows_selected[j].checked === true){
                        selectedContact.push(rows_selected[j].id);
                    }
                }
            }
        }

        // show modal
        $('#btn-add-contact').click(function() {
            $('#modal-add-contact').modal('show');
        });

        $('#btn-delete-contact').click(function () {
            $('#modal-delete-contact').modal('show');
        });

        function sendAjaxDelete(url, data) {
            var result;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                data: data,
                method: 'get',
                async: false,
                success: function(response){
                    if (typeof (response.errors) !== 'undefined') {
                        result = false;
                        toastr.error(response.errors);
                    } else {
                        result = true;
                        toastr.success(response.success);
                    }
                }
            });

            return result;
        }

        // show modal
        $(document).on('click', "table tbody tr a", function() {
            var options = {
              'backdrop': 'static'
            };
            $('#modal-edit-contact').modal(options);

            // get the data
            contactRowId = $(this).attr('id');
            let dataRows = table.row('#input-data-'+contactRowId).data();
            let name = $(this).attr('name');
            let phone = dataRows[2];
            let fax = dataRows[3];
            let email = dataRows[4];
            let note = dataRows[5];
            let jobTitle = dataRows[6];
            let mobile = dataRows[7];

            // fill the data in the input fields
            $("#edit-name").val(name);
            $("#edit_job_title").val(jobTitle);
            $("#edit-email").val(email);
            $("#edit-phone").val(phone);
            $("#edit-mobile").val(mobile);
            $("#edit-fax").val(fax);
            $("#edit-note").val(note);
        });

        function openEditForm(contactId) {
            var url = '{{ route("editContactFromClient", ["clientId"=>$clientDetail["id"], "contactId"=>"__contactRowId"]) }}'.replace("__contactRowId", contactId);
            $('#edit-contact-form').attr("action", url);
        }
    </script>
@stop