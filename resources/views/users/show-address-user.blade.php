<?php
    $typeAddress = array('Home', 'Office');

    $count_address = 0;
    $primaryAddress['id'] = '';
    $primaryAddress['type_name'] = '';
    $primaryAddress['address'] = '';
    $primaryAddress['country'] = '';
    $primaryAddress['state'] = '';
    $primaryAddress['city'] = '';
    $primaryAddress['postal_code'] = '';
    foreach ($addressInfo as $key => $address) {
        $addressInfo[$key]['countryId'] = App\Repositories\Addresses\AddressRepository::getCountryIdByName($address['country']);
        $addressInfo[$key]['stateId'] = App\Repositories\Addresses\AddressRepository::getStateIdByName($address['state']);
        $addressInfo[$key]['cityId'] = App\Repositories\Addresses\AddressRepository::getCityIdByName($address['city']);

        if ($address['is_primary'] == 1){
            $primaryAddress = $addressInfo[$key];
        }
    }
?>

<div class="row">
@if($primaryAddress['id'] != '')
    <div class="col-md-6 col-sm-12 col-form-label text-md-left">
        <div class="card card-address">
            <div class="card-header pt-1">Primary</div>
            <div class="card-body">
                <b>{{ $primaryAddress['type_name'] }} Address</b>
                <span class="cursor-pointer position-absolute-pen" data-toggle="collapse" data-target="#update-address-{{ $primaryAddress['id'] }}" onclick="showEditFormAddress('{{ $primaryAddress['id'] }}', '{{ $primaryAddress['country'] }}', '{{ $primaryAddress['state'] }}', '{{ $primaryAddress['city'] }}', {{ $primaryAddress['countryId'] }}, {{ $primaryAddress['stateId'] }}, {{ $primaryAddress['cityId'] }})">
                    <img src="/images/btn_pen.png">
                </span>&nbsp;&nbsp;
                <span class="cursor-pointer position-absolute-delete" onclick="deleteAddress('{{ route('deleteUserAddress', $primaryAddress['id']) }}')">
                    <img src="/images/img-delete.png" id="delete-address">
                </span>
                <div class="pt-2">
                    {{ ucwords($primaryAddress['address']) }},<br/>
                    @if (isset($primaryAddress['state']))
                        {{ $primaryAddress['state'] }},<br>
                    @endif
                    @if (isset($primaryAddress['city']))
                        {{ $primaryAddress['city'] }},
                    @endif
                        {{ $primaryAddress['postal_code'] }}<br/>{{ $primaryAddress['country'] }}
                </div>
                <div class="row collapse pt-2" id="update-address-{{ $primaryAddress['id'] }}">
                    <div class="col-12 bg-collapse">
                        <div class="col-md-12 col-sm-12 col-form-label text-md-left pt-3" id="cancel-get">
                            <div class="form-group row">
                                <label for="type" class="col-md-2 col-lg-3 font-bold">{{ __('Type') }}</label>

                                <div class="col-md-10 col-lg-9">
                                    <select class="form-control" id="type-address-{{ $primaryAddress['id'] }}" name="type-address-{{ $primaryAddress['id'] }}">
                                        @foreach($typeAddress as $type)
                                            @if($primaryAddress['type_name'] == $type)
                                                <option selected>{{ $type }}</option>
                                                <option id="type-address-hide-{{$primaryAddress['id']}}" hidden value="{{ $type }}"></option>
                                            @else
                                                <option>{{ $type }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-2 col-lg-3 font-bold">{{ __('Address') }}</label>

                                <div class="col-md-10 col-lg-9">
                                    <input id="address-{{ $primaryAddress['id'] }}" type="text" class="form-control" name="" required autocomplete="address" placeholder="Input address" value="{{ isset($primaryAddress['address']) ? $primaryAddress['address'] : '' }}">
                                    <input id="address-hide-{{ $primaryAddress['id'] }}" hidden value="{{ isset($primaryAddress['address']) ? $primaryAddress['address'] : '' }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country" class="col-md-2 col-lg-3 font-bold">{{ __('Country') }}</label>

                                <div class="col-md-10 col-lg-9">
                                    <select class="form-control" id="country-{{ $primaryAddress['id'] }}" name="" onclick="getCountry('{{$primaryAddress['id']}}')">
                                        <option value="">Choose a country</option>
                                        @foreach($listCountries as $country)
                                            @if($primaryAddress['country'] == $country->name)
                                                <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                                                <option id="country-hide-{{$primaryAddress['id']}}" hidden option-name="{{ $country->name }}" value="{{ $country->id }}"></option>
                                            @else
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="state" class="col-md-2 col-lg-3 font-bold">{{ __('State') }}</label>
                                <div class="col-md-10 col-lg-9">
                                    <select class="form-control" name="" id="state-{{ $primaryAddress['id'] }}" onclick="getState('{{ $primaryAddress['id'] }}')">
                                        <option value="{{ $primaryAddress['id'] }}">{{ $primaryAddress['state'] }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="city" class="col-md-2 col-lg-3 font-bold">{{ __('City') }}</label>

                                <div class="col-md-10 col-lg-9">
                                    <select class="form-control" name="" id="city-{{ $primaryAddress['id'] }}">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label for="postcode" class="col-md-2 col-lg-3 font-bold">{{ __('Postcode') }}</label>

                                <div class="col-md-10 col-lg-9">
                                    <input id="postcode-{{ $primaryAddress['id'] }}" type="text" class="form-control" name="" required autocomplete="postcode" placeholder="Input postcode" value="{{ isset($primaryAddress['postal_code']) ? $primaryAddress['postal_code'] : '' }}">
                                    <input id="postcode-hide-{{ $primaryAddress['id'] }}" value="{{ isset($primaryAddress['postal_code']) ? $primaryAddress['postal_code'] : '' }}" hidden>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                                    <label class="col-lg-12">
                                        <input id="set-primary-address-{{ $primaryAddress['id'] }}" class="role-checkbox" type="checkbox" name="" value="{{ $primaryAddress['is_primary'] }}" onclick="$(this).attr('value', this.checked ? 1 : 0)" {{ ($primaryAddress['is_primary'] == 1) ? 'checked' : '' }}><span class="checkmark"></span> <span>Set as primary</span>
                                        <input id="set-primary-address-hide-{{ $primaryAddress['id'] }}" value="{{ $primaryAddress['is_primary'] }}" hidden {{ ($primaryAddress['is_primary'] == 1) ? 'checked' : '' }}>
                                    </label>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditAddress('{{ $primaryAddress['id'] }}')">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editAddress('{{ $primaryAddress['id'] }}')">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@foreach($addressInfo as $address)
    @if($address['is_primary'] != 1)
        <div class="col-md-6 col-sm-12 col-form-label text-md-left">
            <div class="card card-address">
                <div class="card-body pb-46">
                    <div class="font-bold">
                        {{ $address['type_name'] }} Address {{ $count_address = $count_address + 1 }}
                        <span class="cursor-pointer position-absolute-pen" data-toggle="collapse" data-target="#update-address-{{ $address['id'] }}" onclick="showEditFormAddress('{{ $address['id'] }}', '{{ $address['country'] }}', '{{ $address['state'] }}', '{{ $address['city'] }}', {{ $address['countryId'] }}, {{ $address['stateId'] }}, {{ $address['cityId'] }})">
                            <img src="/images/btn_pen.png">
                        </span>&nbsp;&nbsp;
                        <span class="cursor-pointer position-absolute-delete" onclick="deleteAddress('{{ route('deleteUserAddress', $address['id']) }}')">
                            <img src="/images/img-delete.png" id="delete-address">
                        </span>
                    </div>
                    <div class="pt-2">
                        {{ ucwords($address['address']) }},<br/>
                        @if (isset($address['state']))
                            {{ $address['state'] }},<br>
                        @endif
                        @if (isset($address['city']))
                            {{ $address['city'] }},
                        @endif
                            {{ $address['postal_code'] }}<br/>{{ $address['country'] }}
                    </div>
                    <div class="row collapse pt-2" id="update-address-{{ $address['id'] }}">
                        <div class="col-12 bg-collapse">
                            <div class="col-md-12 col-sm-12 col-form-label text-md-left pt-3">
                                <div class="form-group row">
                                    <label for="type" class="col-md-2 col-lg-3 font-bold">{{ __('Type') }}</label>

                                    <div class="col-md-10 col-lg-9">
                                        <select class="form-control" id="type-address-{{ $address['id'] }}" name="">
                                            @foreach($typeAddress as $type)
                                                @if($address['type_name'] == $type)
                                                    <option selected>{{ $type }}</option>
                                                    <option id="type-address-hide-{{$address['id']}}" hidden value="{{ $type }}"></option>
                                                @else
                                                    <option>{{ $type }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="address" class="col-md-2 col-lg-3 font-bold">{{ __('Address') }}</label>

                                    <div class="col-md-10 col-lg-9">
                                        <input id="address-{{ $address['id'] }}" type="text" class="form-control" name="" required autocomplete="address" placeholder="Input address" value="{{ isset($address['address']) ? $address['address'] : '' }}">
                                        <input id="address-hide-{{ $address['id'] }}" hidden value="{{ isset($address['address']) ? $address['address'] : '' }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="country" class="col-md-2 col-lg-3 font-bold">{{ __('Country') }}</label>

                                    <div class="col-md-10 col-lg-9">
                                        <select class="form-control" id="country-{{ $address['id'] }}" name="" onclick="getCountry('{{ $address['id'] }}')">
                                            <option value="">Choose a country</option>
                                            @foreach($listCountries as $country)
                                                @if($address['country'] == $country->name)
                                                    <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                                                    <option id="country-hide-{{$address['id']}}" hidden option-name="{{ $country->name }}" value="{{ $country->id }}"></option>
                                                @else
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="state" class="col-md-2 col-lg-3 font-bold">{{ __('State') }}</label>

                                    <div class="col-md-10 col-lg-9">
                                        <select class="form-control" name="" id="state-{{ $address['id'] }}" onclick="getState('{{ $address['id'] }}')">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="city" class="col-md-2 col-lg-3 font-bold">{{ __('City') }}</label>

                                    <div class="col-md-10 col-lg-9">
                                        <select class="form-control" name="" id="city-{{ $address['id'] }}">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                {{ csrf_field() }}

                                <div class="form-group row">
                                    <label for="postcode" class="col-md-2 col-lg-3 font-bold">{{ __('Postcode') }}</label>

                                    <div class="col-md-10 col-lg-9">
                                        <input id="postcode-{{ $address['id'] }}" type="text" class="form-control" name="" required autocomplete="postcode" placeholder="Input postcode" value="{{ isset($address['postal_code']) ? $address['postal_code'] : '' }}">
                                        <input id="postcode-hide-{{ $address['id'] }}" value="{{ isset($address['postal_code']) ? $address['postal_code'] : '' }}" hidden>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                                        <label class="col-lg-12">
                                            <input id="set-primary-address-{{ $address['id'] }}" class="role-checkbox" type="checkbox" name="" value="{{ $address['is_primary'] }}" onclick="@if($primaryAddress['id'] != '') updateValuePrimaryAddress('{{ $address['id'] }}'); @else $(this).attr('value', this.checked ? 1 : 0) @endif" {{ ($address['is_primary'] == 1) ? 'checked' : '' }}><span class="checkmark"></span> <span>Set as primary</span>
                                            <input id="set-primary-address-hide-{{ $address['id'] }}" value="{{ $address['is_primary'] }}" hidden {{ ($address['is_primary'] == 1) ? 'checked' : '' }}>
                                        </label>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditAddress('{{ $address['id'] }}')">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editAddress('{{ $address['id'] }}')">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
</div>
@can('edit users')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <a href="" data-toggle="collapse" data-target="#add-address">
                <img src="/images/btn_plus.png">
                <i class="custom-font">Add Address</i>
            </a>
        </div>
    </div>
@endcan

<div class="row collapse" id="add-address">
    <div class="col-md-6 col-sm-12 col-form-label text-md-left">
        <div class="row bg-collapse">
            <div class="col-md-12 col-sm-12 col-form-label text-md-left pt-3">
                <div class="form-group row">
                    <label for="type" class="col-md-2 col-lg-3 font-bold">{{ __('Type') }}</label>

                    <div class="col-md-10 col-lg-9">
                        <select class="form-control" id="type-address" name="type-address">
                            <option value="">Select</option>
                            <option>Home</option>
                            <option>Office</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-2 col-lg-3 font-bold">{{ __('Address') }}</label>

                    <div class="col-md-10 col-lg-9">
                        <input id="address" type="text" class="form-control" name="address" required autocomplete="address" placeholder="Input address">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="country" class="col-md-2 col-lg-3 font-bold">{{ __('Country') }}</label>

                    <div class="col-md-10 col-lg-9">
                        <select class="form-control" id="country" name="country">
                            <option value="">Choose a country</option>
                            @foreach($listCountries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="state" class="col-md-2 col-lg-3 font-bold">{{ __('State') }}</label>

                    <div class="col-md-10 col-lg-9">
                        <select class="form-control" name="state" id="state">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city" class="col-md-2 col-lg-3 font-bold">{{ __('City') }}</label>

                    <div class="col-md-10 col-lg-9">
                        <select class="form-control" name="city" id="city">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="postcode" class="col-md-2 col-lg-3 font-bold">{{ __('Postcode') }}</label>

                    <div class="col-md-10 col-lg-9">
                        <input id="postcode" type="text" class="form-control" name="postcode" required autocomplete="postcode" placeholder="Input postcode">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                        <label class="col-lg-12">
                            <input id="set-primary-address" class="role-checkbox" type="checkbox" name="set-primary-address" value="0" onclick="@if($primaryAddress['id'] != '') changeValueAddress(); @else $(this).attr('value', this.checked ? 1 : 0) @endif"><span class="checkmark"></span> <span>Set as primary</span>
                        </label>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-address">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-address">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    @if (isset($response['alert-type']))
        @if ($response['alert-type'] == 'errors')
            toastr.error("{{ $response['message'] }}");
        @else
            $("#btn-cancel-save-address").trigger('click');
            toastr.success("{{ $response['message'] }}");
        @endif
    @endif

    var oldTypeAddress,
        oldAddress,
        oldCountry,
        oldPostCode,
        oldPrimaryAddress,
        checkCountryId,
        textCountryId;

    // Choose country - state - city
    $("select#country").change(function() {
        var countryId = $(this).val();
        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#state").empty();
                        $("#city").empty();
                        $("#state").append('<option value="">Choose a state</option>');
                        $.each(response, function(key, value) {
                            $("#state").append('<option value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });

    $("select#state").change(function() {
        var stateId = $(this).val();
        var url = "{{ route('getCities', '__stateId') }}".replace('__stateId', stateId);
        if(stateId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#city").empty();
                        $("#city").append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#city").append('<option value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });

    function getCountry(formId) {
        $("select#country-" + formId).change(function() {
            var countryId = $(this).val();
            checkCountryId = countryId;
            textCountryId = $("select#country-" + formId + ' :selected').text();

            if(countryId) {
                ajaxLoadState(formId, countryId);
            }
        });

        $("select#state-" + formId).change(function() {
            var stateId = $(this).val();

            if(stateId) {
                ajaxLoadCity(formId, stateId);
            }
        });
    }

    function getState(formId) {
        var stateId = $("select#state-" + formId).val();

        if(stateId) {
            ajaxLoadCity(formId, stateId);
        }
    }

    function showEditFormAddress(formId, countryName, stateName, cityName, countryId, stateId, cityId) {
        if(countryId) {
            ajaxLoadState(formId, countryId);
            setTimeout(function() {
                $('select#state-' + formId + ' option[value="' + stateId + '"]').attr('selected', true);
            }, 500);
        }

        if(stateId) {
            ajaxLoadCity(formId, stateId, cityId);
            setTimeout(function() {
                $("select#city-" + formId + " option:selected").text(cityName);
            }, 500);
        }
    }

    function ajaxLoadState(formId, countryId) {
        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);

        $.ajax({
            type: "get",
            url: url,
            success: function(response) {
                if (response) {
                    $("#state-" + formId).empty();
                    $("#city-" + formId).empty();
                    $("#state-" + formId).append('<option value="">Choose a state</option>');
                    $.each(response, function(key, value) {
                        $("#state-" + formId).append('<option value="'+value['id']+'">'+value['name']+'</option>');
                    });
                }
            }
        });
    }

    function ajaxLoadCity(formId, stateId, cityId = null) {
        var url = "{{ route('getCities', '__stateId') }}".replace('__stateId', stateId);

        $.ajax({
            type: "get",
            url: url,
            success: function(response) {
                if(response) {
                    $("#city-" + formId).empty();
                    $("#city-" + formId).append('<option value="">Choose a city</option>');
                    $.each(response, function(key, value) {
                        if (value['id'] == cityId) {
                            $("#city-" + formId).append('<option value="'+value['id']+'" selected>'+value['name']+'</option>');
                        } else {
                            $("#city-" + formId).append('<option value="'+value['id']+'">'+value['name']+'</option>');
                        }
                    });
                }
            }
        });
    }

    $('#btn-save-address').click(function() {
        var country = $('select#country :selected').text();
        var state = $('select#state :selected').text();
        var city = $('select#city :selected').text();
        var url = "{{ route('storeAddress', $detailUser['id']) }}";
        var data = {
            'type_address': $('select#type-address').val(),
            'address': $('input#address').val(),
            'country': ($('select#country').val() == '') ? '' : country,
            'state': ($('select#state').val() == '') ? '' : state,
            'city': ($('select#city').val() == '') ? '' : city,
            'postcode': $('input#postcode').val(),
            'is_primary': $('input#set-primary-address').val()
        };
        if(data['type_address'] == '' || data['address'] == '' || data['country'] == '' || data['postcode'] == '') {
            return toastr.error("Data is missing!");
        }
        sendAjaxAddress(url, data);
    });

    function showModalAddress() {
        $('#modalShow').modal('show');
        $('.btn-cancel-add-modal').click(function() {
            $("#set-primary-address").prop("checked", false);
        });
        $('.btn-x-modal').click(function() {
            $("#set-primary-address").prop("checked", false);
        });
        $('#btn-ok-modal').click(function() {
            $('#modalShow').modal('hide');
            $("#set-primary-address").prop("checked", true);
            $("#set-primary-address").attr('value', 1);
        });
    }

    function deleteUser() {
        $('#modalDeleteUser').modal('show');
    }

    function changeValueAddress() {
        if($("input#set-primary-address").attr('value') == 0)
        {
            $('.set-primary-modal').text('{{ __('Set address as primary') }}');
            $('.modal-body.content-set-primary').text('{{ __('Do you want to set this address as primary?') }}');
            showModalAddress();
        } else {
            $("input#set-primary-address").attr('value', 0);
        }
    }

    function updateValuePrimaryAddress(addressId) {
        if($("input#set-primary-address-" + addressId).attr('value') == 0)
        {
            $('.set-primary-modal').text('{{ __('Set address as primary') }}');
            $('.modal-body.content-set-primary').text('{{ __('Do you want to set this address as primary?') }}');
            showModalSetPrimaryAddress(addressId);
        } else {
            $("input#set-primary-address-" + addressId).attr('value', 0);
        }
    }

    function showModalSetPrimaryAddress(addressId) {
        $('#modalShow').modal('show');
        $('.btn-cancel-add-modal').click(function() {
            $("#set-primary-address-" + addressId).prop("checked", false);
        });
        $('.btn-x-modal').click(function() {
            $("#set-primary-address-" + addressId).prop("checked", false);
        });
        $('#btn-ok-modal').click(function() {
            $('#modalShow').modal('hide');
            $("#set-primary-address-" + addressId).prop("checked", true);
            $("#set-primary-address-" + addressId).attr('value', 1);
        });
    }

    function sendAjaxAddress(url, data) {
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
            success: function(response){
                $("div#show-address").html(response);
                $('#modalDelete').modal('hide');
        }});
    }

    $("#btn-cancel-save-address").click(function() {
        setAddressNoneValue();
        $("div#add-address").removeClass('show');
    });

    function setAddressNoneValue() {
        $("select#type-address").val('');
        $('input#address').val('');
        $('select#country').val('');
        $('select#state').val('');
        $('select#city').val('');
        $('input#postcode').val('');
        $("input#set-primary-address").prop("checked", false);
        $("input#set-primary-address").attr('value', 0);
    }

    function editAddress(addressId) {
        var country = $('#country-' + addressId + ' :selected').text();
        var state = $('select#state-' + addressId + ' :selected').text();
        var city = $('select#city-' + addressId + ' :selected').text();
        var url = "{{ route('updateAddress') }}";
        var data = {
            'id': addressId,
            'user_id': "{{ $detailUser['id'] }}",
            'type_address': $('select#type-address-' + addressId).val(),
            'address': $('input#address-' + addressId).val(),
            'country': ($('select#country-' + addressId).val() == '') ? '' : country,
            'state': ($('select#state-' + addressId).val() == '') ? '' : state,
            'city': ($('select#city-' + addressId).val() == '') ? '' : city,
            'postcode': $('input#postcode-' + addressId).val(),
            'is_primary': $('input#set-primary-address-' + addressId).val()
        };

        if(data['type_address'] == '' || data['address'] == '' || data['country'] == '' || data['postcode'] == '') {
            return toastr.error("Data is missing!");
        }
        sendAjaxAddress(url, data);
    }

    function cancelEditAddress(addressId) {
        oldTypeAddress = $("#type-address-hide-" + addressId).val();
        $("select#type-address-" + addressId).val(oldTypeAddress);

        oldAddress = $('input#address-hide-' + addressId).val();
        $('input#address-' + addressId).val(oldAddress);

        oldCountryId = $('#country-hide-' + addressId).val();
        oldCountryName = $('#country-hide-' + addressId).attr('option-name');

        $("#country-" + addressId + " option[value=" + checkCountryId + "]").remove();
        $('select#country-' + addressId + ' :selected').val(oldCountryId);
        $('select#country-' + addressId + ' :selected').text(oldCountryName);
        var getCountryId = document.getElementById("country-" + addressId);
        var optionCountry = document.createElement("option");
        optionCountry.value = checkCountryId;
        optionCountry.text = textCountryId;
        getCountryId.add(optionCountry, getCountryId[checkCountryId]);

        oldPostCode = $('input#postcode-hide-' + addressId).val();
        $('input#postcode-' + addressId).val(oldPostCode);

        oldPrimaryAddress = $('input#set-primary-address-hide-' + addressId).attr('value');
        if(oldPrimaryAddress == 1) {
            $("input#set-primary-address-" + addressId).prop("checked", true);
            $("input#set-primary-address-" + addressId).attr('value', 1);
        } else {
            $("input#set-primary-address-" + addressId).prop("checked", false);
            $("input#set-primary-address-" + addressId).attr('value', 0);
        }

        $("div#update-address-" + addressId).removeClass('show');
    }
</script>