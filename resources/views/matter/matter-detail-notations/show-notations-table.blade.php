<?php
    $columns = array('Notation', 'Date/Time', 'Category', 'Note', '');
?>
<div class="table-responsive">
    <table width="100%" id="notation_table" class="table">
        <thead>
            <tr class="column-name">
                @foreach ($columns as $title)
                    @if($title == 'Notation')
                        <th>{{ ucfirst($title) }}</th>
                    @else
                        <th>{{ ucfirst($title) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody class="column-content bg-white">
        @foreach ($listNotations as $notation)
            <?php
                $fileName = $notation['file'];
                $path = "matters/$detailMatterId/notations/" . $fileName;
            ?>
            @if (Storage::disk(env('DISK_STORAGE'))->exists($path))
            <tr>
                <td>
                    <?php
                        $url = App\Repositories\Matter\MatterRepository::loadFileForNotation($detailMatterId, $notation['file']);
                        $categoryName = App\Repositories\Notations\NotationsRepository::getCategoryName($notation['category_id']);
                    ?>
                    <img src="/images/file.png" alt="avata" class="img-file-manager float-left mr-0" style="margin-left: -3px;">
                    <div class="mt-5 text-below-image">
                        <a href="{{ isset($url) ? $url : "#" }}" rel="noopener noreferrer" target="__blank">{{ $notation['file'] }}</a>
                    </div>
                </td>
                <td class="color-content">{{ isset($notation['created_at']) ? date('H:i:s d/m/Y', strtotime($notation['created_at'])) : $notation['created_at'] }}</td>
                <td class="color-content">{{ App\Repositories\Notations\NotationsRepository::getCategoryName($notation['category_id']) }}</td>
                <td class="color-content">{{ $notation['note'] }}</td>
                @if(auth()->user()->hasAnyPermission('edit notations', 'delete notations'))
                    <td width="15%">
                        <div style="width: 50px;">
                            @can('edit notations')
                                <span class="cursor-pointer mr-3" id="edit-{{ $notation['id'] }}" onclick="showModalUpdate('{{ $notation['id'] }}', '{{ $categoryName }}', '{{ $notation['category_id'] }}', '{{ $notation['note'] }}', '{{ $notation['file'] }}')">
                                    <img class="office-img" src="/images/btn_pen_black.png">
                                </span>
                            @endcan
                            @can('delete notations')
                                <span class="cursor-pointer" onclick="showModalDelete('{{ $notation['id'] }}');">
                                    <img class="office-img" src="/images/img-delete-black.png">
                                </span>
                            @endcan
                        </div>
                    </td>
                @else
                    <td class="p-0 m-0"></td>
                @endif
            </tr>
            @else
                @php
                    App\Repositories\Notations\NotationsRepository::deletePermanentlyNotation($notation['id'], $detailMatterId);
                @endphp
            @endif
        @endforeach
        </tbody>
    </table>
</div>

<script>
    if ({!!$result!!}.error !== undefined) {
        toastr.error("Action failed");
    } else {
        toastr.success({!!$result!!}.message);
    }

    $(document).ready(function() {
        $('#notation_table').DataTable({
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
            "columnDefs": [
                { "orderable": false, "targets": 4 }
            ]
        });
        notationTable = $('#notation_table').DataTable();

        $("input.date-picker").datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom"
        });

        // Search input
        $('#notation-search-box').on('keyup', function() {
            notationTable.search( this.value ).draw();
        });

        var createdDate, valCategory;

        $('.btn-apply-filter').on('click', function() {
            valCategory = '';

            notationTable.search('').draw();
        });

        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            // date
            createdDate = $('#created-date-filter').val();
            let indexDate = data[1].trim().indexOf(createdDate);
            // category
            valCategory = $('#category-filter').val();
            let indexCategory = data[2].trim().indexOf(valCategory);

            if((createdDate == '' || indexDate > -1) && (valCategory == 'all' || indexCategory > -1)) {
                return true;
            }
            return false;
        });
    });
    /* Change location of sort icon-datatable */
    var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
        spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
        spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

    $("#notation_table").on('click', 'th', function() {
        $("#notation_table thead th").each(function(i, th) {
            $(th).find('.arrow-hack').remove();
            var html = $(th).html(),
            cls = $(th).attr('class');
            var arrayName = ['Notation', 'Date/Time', 'Category', 'Note'];
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
    $("#notation_table th").first().click().click();
    /* End */
</script>