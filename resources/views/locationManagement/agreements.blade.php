@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement'];
    $titleTable = ['Document', 'Start Date', 'End Date', 'Last Update', ''];
?>
@section('content')
@include('navbar')
@include('locationManagement.location-header')

<div class="row">
    <div class="col-lg-8 col-sm-12 col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('showStatiticLocation', $locationId) }}">Statistic</a>
            </li>
            @can('view client location contacts')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('showContactListLocation', $locationId) }}">Contacts List</a>
                </li>
            @endcan
            @can('view agreements')
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab ? 'active':''}}" href="{{ route('showAgreementLocation', $locationId) }}">Agreements</a>
                </li>
            @endcan
            @can('view client location price list')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('showPriceListLocation', $locationId) }}">Price List</a>
                </li>
            @endcan
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body tab-content recent-cases">
                <div class="row mb-2">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Upload Agreement</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6">
                        <div class="card">
                            <div class="card-body" style="background: #F2F2F2;">
                                <div class="form-group row">
                                    <label for="location-name" class="col-md-12 col-lg-12 col-form-label text-md-left">{{ __('Start Date') }}</label>
                                    <div class="col-md-12 col-lg-12 inner-addon right-addon">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                        <input id="start-date" type="text" class="form-control" name="start-date" autocomplete="off" placeholder="DD-MM-YYYY">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="location-name" class="col-md-12 col-lg-12 col-form-label text-md-left">{{ __('End Date') }}</label>
                                    <div class="col-md-12 col-lg-12 inner-addon right-addon">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                        <input id="end-date" type="text" class="form-control" name="end-date" autocomplete="off" placeholder="DD-MM-YYYY">
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-12 pl-0 pr-0 name-file">
                                    <a href="#">
                                        <input type="file" class="custom-file-input" id="customFile" style="width: 0px;height: 0px;overflow: hidden;">
                                        <label tabindex="0" for="customFile" class="upload-files upload-files-agreement">Upload files</label>
                                    </a>
                                </div>

                                <div class="col-md-12 col-lg-12 text-md-right pr-0">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-duration">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-duration">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Agreements List</h3>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-12 col-sm-12 col-md-12 custom-location-table">
                        <table width="100%" id="agreements-table" class="table">
                            <thead>
                                <tr class="column-name">
                                    @foreach ($titleTable as $title)
                                        <th>{{ ucfirst($title) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="column-content bg-white location-body">
                                @for($i=0; $i<=12; $i++)
                                    <tr>
                                        <td>Contract Commonwealth Bank - 12/2018.doc</td>
                                        <td class="custom-color-location">20/10/2018</td>
                                        <td class="custom-color-location">16/11/2018</td>
                                        <td class="custom-color-location">25/11/2018</td>
                                        <td class="custom-color-location">
                                            <a href="#" data-toggle="collapse" data-target="#add-name" style="text-decoration: none;">
                                                <img src="/images/btn_pen.png">
                                            </a>&nbsp;&nbsp;&nbsp;
                                            <a href="#" style="text-decoration: none;">
                                                <img src="/images/img-delete.png" id="delete-phone">
                                            </a>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-12 col-md-12 pl-lg-0">
        @include('locationManagement.company-info')
        @include('locationManagement.location-info')
    </div>
</div>
@endsection

@section('javascript')
    @include('locationManagement.script-location-header')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#agreements-table').DataTable({
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
                "order": [[ 1, "desc" ]],
            });

            var table = $('#agreements-table').DataTable();

            // Search input
            $('#contact-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#agreements-table").on('click', 'th', function() {
            $("#agreements-table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                cls = $(th).attr('class');
                var arrayName = ['Document', ''];
                switch (cls) {
                    case 'sorting_asc' : 
                        if (arrayName.indexOf(html) == -1) {
                            $(th).html(html+spanAsc);
                        }
                        break;
                    case 'sorting_desc' :
                        if (arrayName.indexOf(html) == -1) {
                            $(th).html(html+spanDesc);
                        }
                        break;
                    default : 
                        if (arrayName.indexOf(html) == -1) {
                            $(th).html(html+spanSorting);
                        }
                        break;
                }
            });
        });

        $("#agreements-table th").first().click().click();

        $(function () {
            $("#start-date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            });
        });

        $(function () {
            $("#end-date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            });
        });

        // upload file
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).closest(".row").find('.name-file').before('<div class="col-md-12 col-lg-12 pl-1 pr-0"><a href="#"><i class=""><label class="upload-files-agreement">'+fileName+'</label>&nbsp;&nbsp;&nbsp;<button type="button" class="close custom-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></i></a></div>');
        });

        $(document).on("click", "button.custom-close" , function() {
            $(this).parent().parent().parent().remove();
        });
    </script>
@stop