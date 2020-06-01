@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement'];
    $titleTable = ['Invoice Item', 'Default Description', 'Default Price', 'Custom Price'];
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
                    <a class="nav-link" href="{{ route('showAgreementLocation', $locationId) }}">Agreements</a>
                </li>
            @endcan
            @can('view client location price list')
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab ? 'active':''}}" href="{{ route('showPriceListLocation', $locationId) }}">Price List</a>
                </li>
            @endcan
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body tab-content recent-cases">
                <div class="row mb-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Price List</h3>
                        @can('edit client location price list')
                            <a href="{{ route('showEditPriceListLocation', $locationId) }}"><i>Edit custom price</i></a>
                        @endcan
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 custom-location-table">
                        <table width="100%" id="price-location-table" class="table">
                            <thead>
                                <tr class="column-name">
                                    @foreach ($titleTable as $title)
                                        <th style="border-top:0px;" class="@if (in_array($title, array('Default Price', 'Custom Price'))) text-right @endif">{{ ucfirst($title) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="column-content bg-white location-body">
                                @foreach($listRates as $rate)
                                    <tr>
                                        <td class="custom-color-location font-weight-bold">
                                            {{ $rate->name }}
                                        </td>
                                        <td class="custom-color-location">
                                            {{ $rate->description }}
                                        </td>
                                        <td class="custom-color-location text-right @if (!empty($rate->default_price) && $rate->default_price < 0) text-danger @endif">
                                            <?php
                                                if (!empty($rate->default_price)) {
                                                    if ($rate->default_price > 0)
                                                        echo '$' . $rate->default_price;
                                                    else
                                                        echo '-$' . abs($rate->default_price);
                                                } else {
                                                    echo '';
                                                }
                                            ?>
                                        </td>
                                        <td class="custom-color-location text-right @if (!empty($rate->custom_price) && $rate->custom_price < 0) text-danger @endif">
                                            <?php
                                                if (!empty($rate->custom_price)) {
                                                    if ($rate->custom_price > 0)
                                                        echo '$' . $rate->custom_price;
                                                    else
                                                        echo '-$' . abs($rate->custom_price);
                                                } else {
                                                    echo '';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                @endforeach
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
        var table;
        $(document).ready(function() {
            $('#price-location-table').DataTable({
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

            table = $('#price-location-table').DataTable();

            // Search input
            $('#contact-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            });

            rows = $('#price-location-table').DataTable().rows({ 'search': 'applied' }).nodes();
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#price-location-table").on('click', 'th', function() {
            $("#price-location-table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                cls = $(th).attr('class');
                var arrayName = [];
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

        $("#price-location-table th").first().click().click();
    </script>
@stop