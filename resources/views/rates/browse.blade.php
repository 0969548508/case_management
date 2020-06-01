@extends('layouts.app')
<?php
    $actions = ['View Cases', 'Deactivate Location', 'View Invoices', 'Create Invoice', 'Upload Fee Agreement', 'Eidt Information', 'Deactivate Client'];
    $titleTable = ['Invoice Item', 'Default Description', 'Default Price'];
?>
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-30">
        <h1 class="title mb-0">Rate Management</h1>
        @can('create rates')
            <a href="{{ route('viewcreateRate') }}"><button type="button" class="d-none d-sm-inline-block btn btn-create-style">ADD RATE</button></a>
        @endcan
    </div>
    <div class="row">
        <div class="table-responsive mx-20">
            <table width="100%" id="rate_table" class="table">
                <thead>
                    <tr class="column-name">
                        @foreach ($titleTable as $title)
                            <th style="border-top:0px;">{{ ucfirst($title) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="column-content bg-white">
                    @foreach($listRates as $rate)
                        <tr>
                            <td class="custom-color-location">
                                <a href="{{ route('showDetailRate', $rate->id) }}">
                                    {{ ucwords($rate->name) }}
                                </a>
                            </td>
                            <td class="custom-color-location">
                                {{ ucfirst($rate->description) }}
                            </td>
                            <td class="custom-color-location @if (!empty($rate->default_price) && $rate->default_price < 0) text-danger @endif">
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
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <script type="text/javascript">
            $(document).ready(function() {
                $.fn.DataTable.ext.pager.numbers_length = 5;
                $('#rate_table').DataTable({
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
                    "order": [[0, "asc"]],
                });
            });
            /* Change location of sort icon-datatable */
            var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
                spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
                spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

            $("#rate_table").on('click', 'th', function() {
                $("#rate_table thead th").each(function(i, th) {
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

            $("#rate_table th").first().click().click();
            /* End */
            </script>
        </div>
    </div>
@endsection