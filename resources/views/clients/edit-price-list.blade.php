@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement', 'Eidt Information', 'Deactivate Client'];
    $titleTable = ['', 'Invoice Item', 'Default Description', 'Default Price', 'Custom Price'];
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
                        <a href="{{ route('showPriceListClient', $clientDetail['id']) }}"><i>Price list</i></a>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <form method="POST" action="{{ route('editPriceListClient', ['clientId'=>$clientDetail['id']]) }}">
                        @csrf
                            <table width="100%" class="table">
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
                                <tbody class="column-content bg-white">
                                    @foreach($listRates as $rate)
                                        <tr>
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
</div>
@endsection

@section('javascript')
    @include('clients.script-client-detail')
    <script type="text/javascript">
        function goBack() {
            window.location.replace("{{ route('showPriceListClient', $clientDetail['id']) }}");
        }
    </script>
@stop