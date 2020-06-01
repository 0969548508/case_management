@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement', 'Eidt Information', 'Deactivate Client'];
    $titleTable = ['Invoice Item', 'Default Description', 'Default Price', 'Custom Price'];
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
                    <a class="nav-link border-0" href="{{ route('showContactListClient', $clientDetail['id']) }}" style="font-weight:bold;">Contact List</a>
                </li>
            @endcan
            @can('view client price list')
                <li class="nav-item">
                    <a class="nav-link border-0 {{ $activeTab ? 'active':''}}" href="{{ route('showPriceListClient', $clientDetail['id']) }}" style="font-weight:bold;">Price List</a>
                </li>
            @endcan
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body tab-content recent-cases">
                <div class="row mb-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 d-sm-flex align-items-center justify-content-between">
                        <h3 class="title mb-0">Price List</h3>
                        @can('edit client price list')
                            <a href="{{ route('showEditPriceListClient', $clientDetail['id']) }}"><i>Edit custom price</i></a>
                        @endcan
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 col-sm-12 col-md-12 custom-location-table">
                        <table width="100%" class="table">
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
</div>
@endsection

@section('javascript')
    @include('clients.script-client-detail')
@stop