<tr id="input-data-parent-{{ $typeDetail->id }}">
    <td class="data-parent-{{$typeDetail->id}}">
        <a class="show-input type-toggle-dropdown float-left" href="javascript:void(0);" data-id="{{$typeDetail->id}}" onclick="toggleDropdown('{{ $typeDetail->id }}')">
            <img src="{{ asset('/images/drop_down.png') }}">
        </a>
        <div class="type-title mt-5 float-left" id="parent-name-{{$typeDetail->id}}">
            <span>{{ $typeDetail->name }}</span> <span class="count-subtype" id="count-subtype-{{$typeDetail->id}}">(0 sub-types)</span>
        </div>
        <div class="mt-5 float-left width-90 d-none" id="input-edit-parent-name-{{$typeDetail->id}}">
            <input class="input-type" type="text" value="{{ $typeDetail->name }}">
        </div>
    </td>
    <td class="action-data-parent-{{$typeDetail->id}}">
        <div class="show-input mt-5 mr-3 float-right">
            <div class="action-parent-form">
                @can('create types')
                    <span class="cursor-pointer mr-5" id="add-subtype-{{$typeDetail->id}}" onclick="showAddChildForm('{{ $typeDetail->id }}')">
                        <img class="office-img add-sub-type" src="/images/btn_plus.png"> Add subtype
                    </span>
                @endcan
                @can('edit types')
                    <span class="cursor-pointer mr-3" id="edit-{{$typeDetail->id}}" onclick="showEditParentForm('{{ $typeDetail->id }}')">
                        <img class="office-img" src="/images/btn_pen_black.png">
                    </span>
                @endcan
                @can('delete types')
                    <span class="cursor-pointer" id="delete-{{$typeDetail->id}}" onclick="showDeleteTypeForm('{{ $typeDetail->id }}')">
                        <img class="office-img" src="/images/img-delete-black.png">
                    </span>
                @endcan
            </div>
            <div class="edit-parent-form d-none">
                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editParent('{{ $typeDetail->id }}')">
                    {{ __('Save') }}
                </button>
                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditParent('{{ $typeDetail->id }}')">
                {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </td>
</tr>
<tr id="input-add-subtype-{{$typeDetail->id}}" class="d-none">
    <td class="add-subtype-{{$typeDetail->id}}">
        <div class="mt-5 pl-5 float-left w-100" id="input-add-subtype-name-{{$typeDetail->id}}">
            <input class="input-type" type="text" value="">
        </div>                           
    </td>
    <td class="action-add-subtype-{{$typeDetail->id}}">
        <div class="show-input mt-5 mr-3 mg-top-13 float-right">
            <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="addSubtype('{{$typeDetail->id}}')">
                {{ __('Save') }}
            </button>
            <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelAddSubtype('{{$typeDetail->id}}')">
            {{ __('Cancel') }}
            </button>
        </div>                                
    </td>
</tr>