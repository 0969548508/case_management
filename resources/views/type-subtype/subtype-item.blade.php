<tr id="input-data-child-{{ $typeDetail->id }}" class="parent-{{$typeDetail->parent_id}} @if (!$checkTransform) d-none @endif">
    <td class="data-child-{{$typeDetail->id}}">
        <div class="subtype-title mt-5 pl-5" id="child-name-{{$typeDetail->id}}">
            {{ $typeDetail->name }}
        </div>
        <div class="mt-5 pl-5 d-none" id="input-edit-child-name-{{$typeDetail->id}}">
            <input class="input-type" type="text" value="{{ $typeDetail->name }}">
        </div>
    </td>
    <td class="action-data-child-{{$typeDetail->id}}">
        <div class="show-input mt-5 mr-3 float-right">
            <div class="action-child-form">
                @can('edit types')
                    <span class="cursor-pointer mr-3" id="edit-{{$typeDetail->id}}" onclick="showEditChildForm('{{ $typeDetail->id }}')">
                        <img class="office-img" src="/images/btn_pen_black.png">
                    </span>
                @endcan
                @can('delete types')
                    <span class="cursor-pointer" id="delete-{{$typeDetail->id}}" onclick="showDeleteSubTypeForm('{{ $typeDetail->id }}')">
                        <img class="office-img" src="/images/img-delete-black.png">
                    </span>
                @endcan
            </div>
            <div class="edit-child-form d-none">
                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editChild('{{ $typeDetail->id }}')">
                    {{ __('Save') }}
                </button>
                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditChild('{{ $typeDetail->id }}')">
                {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </td>
</tr>