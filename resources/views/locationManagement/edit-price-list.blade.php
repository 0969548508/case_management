@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement'];
    $titleTable = ['', 'Invoice Item', 'Default Description', 'Default Price', 'Custom Price'];
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
                        <a href="{{ route('showPriceListLocation', $locationId) }}"><i>Price list</i></a>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 custom-location-table">
                        <form method="POST" action="{{ route('editPriceListLocation', ['locationId'=>$locationId]) }}" id="edit-price-form">
                        @csrf
                            <table width="100%" id="edit-price-list-table" class="table">
                                <thead>
                                    <tr class="column-name">
                                        @foreach ($titleTable as $title)
                                            @if ($title == '')
                                                <th hidden>{{ ucfirst($title) }}</th>
                                            @else
                                                <th style="border-top:0px;" class="@if (in_array($title, array('Default Price', 'Custom Price'))) text-right @endif">{{ ucfirst($title) }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="column-content bg-white location-body">
                                    @foreach($listRates as $rate)
                                            <tr id="input-data-{{ $rate->id }}">
                                                <td hidden>
                                                    <input name="item[{{$loop->index}}][id]" value="{{ $rate->id }}">
                                                </td>
                                                <td class="custom-color-location font-weight-bold">
                                                    <div class="m-auto">
                                                        {{ $rate->name }}
                                                    </div>
                                                </td>
                                                <td class="custom-color-location">
                                                    {{ $rate->description }}
                                                </td>
                                                <td style="width:12%;" class="text-right @if (!empty($rate->default_price) && $rate->default_price < 0) text-danger @endif">
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
                                                <td style="width:12%;">
                                                    <input id="{{ $rate->id }}" type="text" class="form-control text-right" name="item[{{$loop->index}}][custom_price]" autocomplete="custom-item" autofocus value="{{ $rate->custom_price }}" numeric oninput="this.value = this.value.replace(/[^0-9-.,]/g, '').split(/\-/).slice(0, 2).join('-')" onchange="this.value = this.value.trim()">
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-5">
                                <button type="button" class="btn btn-primary m-auto custom-button-cancel" id="btn-cancel-save-price-list" onclick="goBack()">
                                    {{ __('CANCEL') }}
                                </button>
                                <?php
                                    $countRate = count($listRates);
                                ?>
                                <button type="submit" class="btn btn-primary m-auto custom-button" @if ($countRate == "0") disabled @endif id="btn-save-price-list">
                                    {{ __('SAVE') }}
                                </button>
                            </div>
                        </form>
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
            $('#edit-price-list-table').DataTable({
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

            table = $('#edit-price-list-table').DataTable();

            // Search input
            $('#contact-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            });

            rows = $('#edit-price-list-table').DataTable().rows({ 'search': 'applied' }).nodes();
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#edit-price-list-table").on('click', 'th', function() {
            $("#edit-price-list-table thead th").each(function(i, th) {
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

        $("#edit-price-list-table th").first().click().click();

        function goBack() {
            window.location.replace("{{ route('showPriceListLocation', $locationId) }}");
        }
    </script>
@stop