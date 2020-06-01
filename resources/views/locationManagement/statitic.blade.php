@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement'];
    $titleTable = ['Case Name', 'Status', 'Note'];
?>
@section('content')
@include('navbar')
@include('locationManagement.location-header')

<div class="row">
    <div class="col-lg-8 col-sm-12 col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab ? 'active':''}}" href="{{ route('showStatiticLocation', $locationId) }}">Statistic</a>
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
                <h3 class="title mb-3">Recent Cases</h3>
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <table class="table-style">
                            <tr class="align-content-center">
                                <td class="pr-2">
                                    <div class="vl"></div>
                                </td>
                                <td class="text-center pr-5">
                                    <span class="text-act">Pending cases</span><br/>
                                    <div class="text-number" style="color: #FECC1D;">9</div>
                                </td>
                                <td class="pr-2 custom-vl">
                                    <div class="vl-1"></div>
                                </td>
                                <td class="text-center pr-5">
                                    <span class="text-act">Active cases</span>
                                    <div class="text-number" style="color:  #16BB00;">5</div>
                                </td>
                                <td class="pr-2">
                                    <div class="vl-2"></div>
                                </td>
                                <td class="text-center">
                                    <span class="text-act">On hold cases</span>
                                    <div class="text-number" style="color: #FF5555;">7</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 custom-location-table">
                        <table width="100%" id="statitic-table" class="table">
                            <thead>
                                <tr class="column-name">
                                    @foreach ($titleTable as $title)
                                        <th style="border-top: 0px;">{{ ucfirst($title) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="column-content bg-white location-body">
                                @for($i=0; $i<=12; $i++)
                                    <tr>
                                        <td style="color: #0693B1;">Commonwealth Bank-Melborne-No.15</td>
                                        <td class="custom-color-location custom-status">
                                            <span class="custom-status-active-table">
                                                Active
                                            </span>
                                        </td>
                                        <td class="custom-color-location">Deposit remaining $300</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-12 col-sm-12 col-md-12 text-md-right">
                        <a class="view-more" href="#" style="color:#0693B1;text-decoration:underline;"><i>View more</i></a>
                    </div>
                </div>

                <!-- invoices -->
                <h3 class="title mt-5">Recent Invoices</h3>
                <div class="row ml-0 mr-0">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-body" style="background: #C4C4C4;height:242px;width: auto;">
                                <h1 style="text-align:center;font-weight:bold;">TBD</h1>
                            </div>
                        </div>
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
            $('#statitic-table').DataTable({
                "info": true,
                "paging": false,
                "lengthMenu": false,
                "info": false,
                "sDom": 'Rfrtlip',
                "order": [[ 1, "desc" ]],
            });

            var table = $('#statitic-table').DataTable();

            // Search input
            $('#contact-search-box').on('keyup', function() {
                table.search( this.value ).draw();
            } );
        });

        /* Change location of sort icon-datatable */
        var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
            spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

        $("#statitic-table").on('click', 'th', function() {
            $("#statitic-table thead th").each(function(i, th) {
                $(th).find('.arrow-hack').remove();
                var html = $(th).html(),
                cls = $(th).attr('class');
                var arrayName = ['Case Name','Note'];
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

        $("#statitic-table th").first().click().click();
    </script>
@stop