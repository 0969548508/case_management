<?php
    $typePhone = array('work', 'home', 'mobile', 'fax', 'others');

    $primaryPhone['id'] = '';
    $primaryPhone['phone_number'] = '';
    $primaryPhone['type_name'] = '';
    $primaryPhone['is_primary'] = '';
    foreach ($phoneInfo as $phone) {
        if ($phone['is_primary'] == 1){
            $primaryPhone = $phone;
        }
    }
?>

<div class="row col-md-12 col-sm-12 col-form-label text-md-left">
    <b>
        @if($primaryPhone['phone_number'] != '') {{ ucwords($primaryPhone['type_name']) }}
        @else {{ __('Mobile') }}
        @endif <span id="primary-{{ $primaryPhone['id'] }}" class="all-primary">{{ __('(Primary)') }} </span>:&nbsp;
    </b>
    <span id="phone-number-{{ $primaryPhone['id'] }}">{{ $primaryPhone['phone_number'] }}</span> &nbsp;
    @if($primaryPhone['phone_number'] != '')
        <span class="cursor-pointer" data-toggle="collapse" data-target="#update-phone-{{ $primaryPhone['id'] }}">
            <img src="/images/btn_pen.png">
        </span>
        <span class="cursor-pointer" onclick="deletePhone('{{ route('deleteUserPhone', $primaryPhone['id']) }}')">
                &nbsp;&nbsp;
            <img src="/images/img-delete.png" id="delete-phone-{{ $primaryPhone['id'] }}">
        </span>
    @endif
</div>
<div class="row collapse bg-collapse mt-2" id="update-phone-{{ $primaryPhone['id'] }}">
    <div class="col-md-12 col-sm-12 col-form-label text-md-left">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                <select class="form-control" name="type-phone" id="type-phone-update-{{ $primaryPhone['id'] }}" onchange="changeTypePhone('{{ $primaryPhone['id'] }}')">
                @foreach($typePhone as $key => $type)
                    @if (!in_array(strtolower($primaryPhone['type_name']), $typePhone) && $key == 0)
                        <option selected>{{ ucwords($primaryPhone['type_name']) }}</option>
                        <option id="type-phone-hide-{{$primaryPhone['id']}}" hidden value="{{ $primaryPhone['type_name'] }}"></option>
                    @endif
                    @if(strtolower($primaryPhone['type_name']) == $type)
                        <option selected>{{ ucwords($type) }}</option>
                        <option id="type-phone-hide-{{$primaryPhone['id']}}" hidden value="{{ $type }}"></option>
                    @else
                        <option>{{ ucwords($type) }}</option>
                    @endif
                @endforeach
                </select>
                <input type="text" id="input-type-phone-update-{{ $primaryPhone['id'] }}" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input phone type">
            </div>

            <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                <input id="phone-update-{{ $primaryPhone['id'] }}" type="text" class="form-control" required autocomplete="phone" autofocus placeholder="Input number" value="{{ isset($primaryPhone['phone_number']) ? $primaryPhone['phone_number'] : '' }}">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                <label class="col-lg-12">
                    <input id="set-primary-phone-{{ $primaryPhone['id'] }}" class="role-checkbox number-phone" type="checkbox" name="set-primary-phone-{{ $primaryPhone['id'] }}" value="{{ $primaryPhone['is_primary'] }}" onclick="$(this).attr('value', this.checked ? 1 : 0)"  {{ ($primaryPhone['is_primary'] == 1) ? 'checked' : '' }}><span class="checkmark"></span> <span>Set as primary</span>
                    <input id="set-primary-phone-hide-{{ $primaryPhone['id'] }}" value="{{ $primaryPhone['is_primary'] }}" hidden {{ ($primaryPhone['is_primary'] == 1) ? 'checked' : '' }}>
                </label>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditPhone('{{ $primaryPhone["id"] }}')">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editPhone('{{ $primaryPhone["id"] }}')">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
@foreach($phoneInfo as $phone)
    @if($phone['is_primary'] != 1)
        <div class="row col-md-12 col-sm-12 col-form-label text-md-left">
            <b>{{ ucwords($phone['type_name']) }} <span id="primary-{{ $phone['id'] }}" class="all-primary"></span> : </b> &nbsp; <span id="phone-number-{{ $phone['id'] }}">{{ $phone['phone_number'] }}</span> &nbsp;
            <span class="cursor-pointer" data-toggle="collapse" data-target="#update-phone-{{ $phone['id'] }}">
                <img src="/images/btn_pen.png">
            </span>
            <span class="cursor-pointer" onclick="deletePhone('{{ route('deleteUserPhone', $phone['id']) }}')">
                @if(isset($phone['phone_number']))
                    &nbsp;&nbsp;
                    <img src="/images/img-delete.png" id="delete-phone-{{ $phone['id'] }}">
                @endif
            </span>
        </div>

        <div class="row collapse bg-collapse mt-2" id="update-phone-{{ $phone['id'] }}">
            <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                        <select class="form-control" name="type-phone" id="type-phone-update-{{ $phone['id'] }}" onchange="changeTypePhone('{{ $phone['id'] }}')">
                        @foreach($typePhone as $key => $type)
                            @if (!in_array(strtolower($phone['type_name']), $typePhone) && $key == 0)
                                <option selected>{{ ucwords($phone['type_name']) }}</option>
                                <option id="type-phone-hide-{{$phone['id']}}" hidden value="{{ $phone['type_name'] }}"></option>
                            @endif
                            @if($phone['type_name'] == $type)
                                <option selected>{{ ucwords($type) }}</option>
                                <option id="type-phone-hide-{{$phone['id']}}" hidden value="{{ $type }}"></option>
                            @else
                                <option>{{ ucwords($type) }}</option>
                            @endif
                        @endforeach
                        </select>
                        <input type="text" id="input-type-phone-update-{{ $phone['id'] }}" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input phone type">
                    </div>

                    <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                        <input id="phone-update-{{ $phone['id'] }}" type="text" class="form-control" required autocomplete="phone" autofocus placeholder="Input number" value="{{ isset($phone['phone_number']) ? $phone['phone_number'] : '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                        <label class="col-lg-12">
                            <input id="set-primary-phone-{{ $phone['id'] }}" class="role-checkbox number-phone" type="checkbox" name="set-primary-phone-{{ $phone['id'] }}" value="{{ $phone['is_primary'] }}" onclick="@if($primaryPhone['phone_number'] != '') changeValueUpdatePhone('{{ $phone["id"] }}'); @else $(this).attr('value', this.checked ? 1 : 0) @endif"  {{ ($phone['is_primary'] == 1) ? 'checked' : '' }}><span class="checkmark"></span> <span>Set as primary</span>
                            <input id="set-primary-phone-hide-{{ $phone['id'] }}" value="{{ $phone['is_primary'] }}" hidden {{ ($phone['is_primary'] == 1) ? 'checked' : '' }}>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditPhone('{{ $phone["id"] }}');">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editPhone('{{ $phone["id"] }}')">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
    @if (isset($response['alert-type']) && $response['alert-type'] == 'errors')
        toastr.error("{{ $response['message'] }}");
    @else
        $("#btn-cancel-save-phone").trigger('click');
        toastr.success("{{ $response['message'] }}");
    @endif
</script>