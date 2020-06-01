@extends('layouts.app')
<?php
    $titleTable = ['', ''];
?>
@section('content')
    <div class="col-xl-8 p-0 d-sm-flex align-items-center justify-content-between mb-30">
        <h1 class="title mb-0">Matter Type - Subtype</h1>
        @can('create types')
            <a href="javascript:void(0);" onclick="showAddType()"><button type="button" class="d-sm-inline-block btn btn-create-style">ADD TYPE</button></a>
        @endcan
    </div>

    <div class="row">
        <div class="col-xl-8 p-0 table-responsive mx-20">
            <table width="100%" id="type_table" class="table">
                <thead>
                    <tr class="column-name">
                        @foreach ($titleTable as $title)
                            <th style="border-top:0px;">{{ ucfirst($title) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="column-content bg-white">
                    <tr id="input-add-type" class="d-none">
                        <td class="add-type">
                            <div class="mt-5 float-left ml-3 width-90" id="input-add-type-name">
                                <input class="input-type" type="text" value="">
                            </div>                           
                        </td>
                        <td class="action-add-type">
                            <div class="show-input mt-5 mr-3 mg-top-13 float-right">
                                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="addType()">
                                    {{ __('Save') }}
                                </button>
                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelAddType()">
                                {{ __('Cancel') }}
                                </button>
                            </div>                                
                        </td>
                    </tr>
                    @foreach ($listTypes as $type)
                        <tr id="input-data-parent-{{ $type->id }}">
                            <td class="data-parent-{{$type->id}}">
                                <a class="show-input type-toggle-dropdown float-left" href="javascript:void(0);" data-id="{{$type->id}}" onclick="toggleDropdown('{{ $type->id }}')">
                                    <img src="{{ asset('/images/drop_down.png') }}">
                                </a>
                                <div class="type-title mt-5 float-left" id="parent-name-{{$type->id}}">
                                    <span>{{ $type->name }}</span> <span class="count-subtype" id="count-subtype-{{$type->id}}">({{ count($type->children) }} sub-types)</span>
                                </div>
                                <div class="mt-5 float-left width-90 d-none" id="input-edit-parent-name-{{$type->id}}">
                                    <input class="input-type" type="text" value="{{ $type->name }}">
                                </div>
                            </td>
                            <td class="action-data-parent-{{$type->id}}">
                                @php
                                    $checkInUse = App\Repositories\SpecificMatters\SpecificMattersRepository::checkInUseForType($type->id, true);
                                @endphp
                                <div class="show-input mt-5 mr-3 float-right">
                                    <div class="action-parent-form @if ($checkInUse && auth()->user()->hasAnyPermission('delete types')) mg-right-18 @endif">
                                        @can('create types')
                                            <span class="cursor-pointer mr-5" id="add-subtype-{{$type->id}}" onclick="showAddChildForm('{{ $type->id }}')">
                                                <img class="office-img add-sub-type" src="/images/btn_plus.png"> Add subtype
                                            </span>
                                        @endcan
                                        @can('edit types')
                                            <span class="cursor-pointer mr-3" id="edit-{{$type->id}}" onclick="showEditParentForm('{{ $type->id }}')">
                                                <img class="office-img" src="/images/btn_pen_black.png">
                                            </span>
                                        @endcan
                                        @can('delete types')
                                            @if (!$checkInUse)
                                                <span class="cursor-pointer" id="delete-{{$type->id}}" onclick="showDeleteTypeForm('{{ $type->id }}')">
                                                    <img class="office-img" src="/images/img-delete-black.png">
                                                </span>
                                            @endif
                                        @endcan
                                    </div>
                                    <div class="edit-parent-form d-none">
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editParent('{{ $type->id }}')">
                                            {{ __('Save') }}
                                        </button>
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditParent('{{ $type->id }}')">
                                        {{ __('Cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr id="input-add-subtype-{{$type->id}}" class="d-none">
                            <td class="add-subtype-{{$type->id}}">
                                <div class="mt-5 pl-5 float-left w-100" id="input-add-subtype-name-{{$type->id}}">
                                    <input class="input-type" type="text" value="">
                                </div>                           
                            </td>
                            <td class="action-add-subtype-{{$type->id}}">
                                <div class="show-input mt-5 mr-3 mg-top-13 float-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="addSubtype('{{$type->id}}')">
                                        {{ __('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelAddSubtype('{{$type->id}}')">
                                    {{ __('Cancel') }}
                                    </button>
                                </div>                                
                            </td>
                        </tr>
                        @if (count($type->children) > 0)
                            @foreach ($type->children as $child)
                                <tr id="input-data-child-{{ $child->id }}" class="parent-{{$type->id}} d-none">
                                    <td class="data-child-{{$child->id}}">
                                        <div class="subtype-title mt-5 pl-5" id="child-name-{{$child->id}}">
                                            {{ $child->name }}
                                        </div>
                                        <div class="mt-5 pl-5 d-none" id="input-edit-child-name-{{$child->id}}">
                                            <input class="input-type" type="text" value="{{ $child->name }}">
                                        </div>
                                    </td>
                                    <td class="action-data-child-{{$child->id}}">
                                        <div class="show-input mt-5 mr-3 float-right">
                                            @php
                                                $checkChildInUse = App\Repositories\SpecificMatters\SpecificMattersRepository::checkInUseForType($child->id);
                                            @endphp
                                            <div class="action-child-form @if ($checkChildInUse && auth()->user()->hasAnyPermission('delete types')) mg-right-18 @endif">
                                                @can('edit types')
                                                    <span class="cursor-pointer mr-3" id="edit-{{$child->id}}" onclick="showEditChildForm('{{ $child->id }}')">
                                                        <img class="office-img" src="/images/btn_pen_black.png">
                                                    </span>
                                                @endcan
                                                @can('delete types')
                                                    @if (!$checkChildInUse)
                                                        <span class="cursor-pointer" id="delete-{{$child->id}}" onclick="showDeleteSubTypeForm('{{ $child->id }}')">
                                                            <img class="office-img" src="/images/img-delete-black.png">
                                                        </span>
                                                    @endif
                                                @endcan
                                            </div>
                                            <div class="edit-child-form d-none">
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editChild('{{ $child->id }}')">
                                                    {{ __('Save') }}
                                                </button>
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditChild('{{ $child->id }}')">
                                                {{ __('Cancel') }}
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- modal delete type - subtype -->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
        $(document).ready(function() {
            $.fn.DataTable.ext.pager.numbers_length = 5;
            $('#type_table').DataTable({
                "paging":   false,
                "ordering": false,
                "info":     false,
                "sDom": 'Rfrtlip',
                "order": [],
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                    { "orderable": false, "targets": 1, "width": "35%" }
                ]
            });
        });

        function toggleDropdown(parentId) {
            var checkTransform = $('#input-data-parent-' + parentId + ' .type-toggle-dropdown').find('img').hasClass('transform-90');
            var parentId = $('#input-data-parent-' + parentId + ' .type-toggle-dropdown').data('id');

            if (checkTransform) {
                $('#input-data-parent-' + parentId + ' .type-toggle-dropdown').find('img').removeClass('transform-90');
                $('.parent-' + parentId).addClass('d-none');
            } else {
                $('#input-data-parent-' + parentId + ' .type-toggle-dropdown').find('img').addClass('transform-90');
                $('.parent-' + parentId).removeClass('d-none');
            }
        };

        // edit parent
        function showEditParentForm(parentId) {
            // biding data
            $('#input-edit-parent-name-' + parentId).find('input').val($('#parent-name-' + parentId).find('span').not('.count-subtype').text().replace(/^\s+|\s+$/g, ''));

            $('#parent-name-' + parentId).addClass('d-none');
            $('#input-edit-parent-name-' + parentId).removeClass('d-none');
            $('.action-data-parent-' + parentId).find('.action-parent-form').addClass('d-none');
            $('.action-data-parent-' + parentId).find('.edit-parent-form').removeClass('d-none');
            $('#input-data-parent-' + parentId).find('.show-input').addClass('mg-top-13');
        }

        function cancelEditParent(parentId) {
            $('#parent-name-' + parentId).removeClass('d-none');
            $('#input-edit-parent-name-' + parentId).addClass('d-none');
            $('.action-data-parent-' + parentId).find('.action-parent-form').removeClass('d-none');
            $('.action-data-parent-' + parentId).find('.edit-parent-form').addClass('d-none');
            $('#input-data-parent-' + parentId).find('.show-input').removeClass('mg-top-13');
        }

        function editParent(parentId) {
            let newName = $('#input-edit-parent-name-' + parentId).find('input').val();
            let data = {
                'name' : newName
            }

            sendAjaxEditType(parentId, data, 'parent');
        }
        // end edit parent

        // edit children
        function showEditChildForm(childId) {
            // biding data
            $('#input-edit-child-name-' + childId).find('input').val($('#child-name-' + childId).text().replace(/^\s+|\s+$/g, ''));

            $('#child-name-' + childId).addClass('d-none');
            $('#input-edit-child-name-' + childId).removeClass('d-none');
            $('.action-data-child-' + childId).find('.action-child-form').addClass('d-none');
            $('.action-data-child-' + childId).find('.edit-child-form').removeClass('d-none');
            $('#input-data-child-' + childId).find('.show-input').addClass('mg-top-13');
        }

        function cancelEditChild(childId) {
            $('#child-name-' + childId).removeClass('d-none');
            $('#input-edit-child-name-' + childId).addClass('d-none');
            $('.action-data-child-' + childId).find('.action-child-form').removeClass('d-none');
            $('.action-data-child-' + childId).find('.edit-child-form').addClass('d-none');
            $('#input-data-child-' + childId).find('.show-input').removeClass('mg-top-13');
        }

        function editChild(childId) {
            let newName = $('#input-edit-child-name-' + childId).find('input').val();
            let data = {
                'name' : newName
            }

            sendAjaxEditType(childId, data);
        }
        // end edit children

        function sendAjaxEditType(typeId, data, type = 'child') {
            let url = "{{ route('ajaxEditType', '__typeId') }}".replace('__typeId', typeId);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            jQuery.ajax({
                url: url,
                data: data,
                method: 'get',
                async: false,
                success: function(response) {
                    if (response['alert-type'] == 'error') {
                        toastr.error(response['message']);
                    } else {
                        toastr.success(response['message']);
                        refeshDataByEdit(typeId, data, type);
                    }
            }});
        }

        function refeshDataByEdit(typeId, data, type = 'child') {
            if (type == 'child') {
                $('#child-name-' + typeId).text($('#input-edit-child-name-' + typeId).find('input').val().replace(/^\s+|\s+$/g, ''));

                cancelEditChild(typeId);
            } else if (type == 'parent') {
                $('#parent-name-' + typeId).find('span').not('.count-subtype').text($('#input-edit-parent-name-' + typeId).find('input').val().replace(/^\s+|\s+$/g, ''));

                cancelEditParent(typeId);
            }
        }

        // add children
        function showAddChildForm(parentId) {
            $('#input-add-subtype-' + parentId).removeClass('d-none');
        }

        function cancelAddSubtype(parentId) {
            $('#input-add-subtype-' + parentId).find('input').val('');
            $('#input-add-subtype-' + parentId).addClass('d-none');
        }

        function addSubtype(parentId) {
            let name = $('#input-add-subtype-name-' + parentId).find('input').val();
            let checkTransform = $('#input-data-parent-' + parentId).find('img').hasClass('transform-90');
            let data = {
                'name' : name,
                'parent_id': parentId,
                'check_transform' : (checkTransform) ? 1 : 0
            };

            sendAjaxAddType(data);
        }
        // end add children

        //add parent
        function showAddType() {
            $('#input-add-type').removeClass('d-none');
            $('#input-add-type-name').find('input').val('');
        }

        function cancelAddType() {
            $('#input-add-type').addClass('d-none');
        }

        function addType() {
            let name = $('#input-add-type-name').find('input').val();
            let data = {
                'name' : name,
                'check_transform' : 0
            };

            sendAjaxAddType(data, 'parent');
        }
        //end add parent

        function sendAjaxAddType(data, type = 'child') {
            let url = "{{ route('ajaxAddType') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            jQuery.ajax({
                url: url,
                data: data,
                method: 'get',
                async: false,
                success: function(response) {
                    if (response['alert-type'] == 'error') {
                        toastr.error(response['message']);
                    } else {
                        toastr.success(response['message']);
                        refeshDataByAddType(data, response['html'], type);
                    }
            }});
        }

        function refeshDataByAddType(data, html, type = 'child') {
            if (type == 'child') {
                let count = $('table#type_table .parent-' + data.parent_id).length + 1;
                $('#count-subtype-' + data.parent_id).text('(' + count + ' sub-types)');
                $('#input-add-subtype-' + data.parent_id).after(html);
                cancelAddSubtype(data.parent_id);
            } else if (type == 'parent') {
                $('#type_table').find('tbody').append(html);
                cancelAddType();
            }
        }

        // delete type - subtype
        function showDeleteTypeForm(typeId) {
            var url = "{{ route('deleteType', '__typeId') }}".replace('__typeId', typeId);
            $('#delete-form').attr('action', url);
            $('h5#deleteTitle').text('Delete Type');
            $('#modal-delete').find('.modal-body').text('Delete type will also delete all subtype underneath it. Are you sure you want to delete it?');

            $('#modal-delete').modal('show');
        }

        function showDeleteSubTypeForm(typeId) {
            var url = "{{ route('deleteType', '__typeId') }}".replace('__typeId', typeId);
            $('#delete-form').attr('action', url);
            $('h5#deleteTitle').text('Delete Subtype');
            $('#modal-delete').find('.modal-body').text('Are you sure you want to delete the subtype?');

            $('#modal-delete').modal('show');
        }
        // end delete
    </script>
@stop