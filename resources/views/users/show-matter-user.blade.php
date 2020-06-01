<form action="{{ route('updateUserType', $detailUser['id']) }}" method="POST" id="show-edit-matter-form">
    @csrf
    <div class="row bg-collapse" id="show-edit-matter">
        <div class="col-md-12 col-sm-12 col-form-label text-md-left">
            <div class="row">
                <div class="col-12">
                    <label for="matter" class="col-form-label text-md-right font-weight-bold">{{ __('Matter Expertise') }}</label>
                </div>
            </div>

            <div class="row">
                <div class="col-12 multi-select-matter">
                    @php
                        $arrayTypeIdOfUser = [];
                        foreach ($listSpecificMattersByUser as $type1) {
                            array_push($arrayTypeIdOfUser, $type1['id']);
                        }
                    @endphp
                    <select id="select-matter" name="select-matter[]" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="{{ count($arrayTypeIdOfUser) > 0 ? count($arrayTypeIdOfUser) . ' items selected' : 'Choose Type - Subtype' }}" multiple data-selected-text-format="count">
                        @foreach ($listSpecificMatters as $type)
                            <optgroup label="{{ $type->name }}">
                                @if (count($type->children) > 0)
                                    @foreach ($type->children as $child)
                                        <option value="{{ $child->id }}" data-name="{{ $child->name }}" data-group="{{ $type->name }}" @if (in_array($child['id'], $arrayTypeIdOfUser)) selected @endif>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </optgroup>
                        @endforeach
                    </select>
                    <div hidden>
                        <select id="select-matter-hidden" class="selectpicker show-menu-arrow form-control" multiple>
                            @foreach ($listSpecificMatters as $type)
                                <optgroup label="{{ $type->name }}">
                                    @if (count($type->children) > 0)
                                        @foreach ($type->children as $child)
                                            <option value="{{ $child->id }}" data-name="{{ $child->name }}" data-group="{{ $type->name }}" @if (in_array($child['id'], $arrayTypeIdOfUser)) selected @endif>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <ul style="list-style: none;" id="selected-matter" class="col-12 my-1">
                    @foreach ($listSpecificMattersByUser as $type)
                        <li class="removable-matter-{{ $type['id'] }}">{{ $type['parent'][0]['name'] }}/{{ $type['name'] }}<i class="fa fa-times-circle float-right mr-1 mt-1 cursor-pointer remove-matter-btn-{{ $type['id'] }}" aria-hidden="true" data-id="{{ $type['id'] }}" onclick="clearSelectedType('{{ $type['id'] }}');"></i></li>
                    @endforeach
                </ul>
                <ul hidden style="list-style: none;" id="selected-matter-hidden" class="col-12 my-1">
                    @foreach ($listSpecificMattersByUser as $type)
                        <li class="removable-matter-{{ $type['id'] }}">{{ $type['parent'][0]['name'] }}/{{ $type['name'] }}<i class="fa fa-times-circle float-right mr-1 mt-1 cursor-pointer remove-matter-btn-{{ $type['id'] }}" aria-hidden="true" data-id="{{ $type['id'] }}" onclick="clearSelectedType('{{ $type['id'] }}');"></i></li>
                    @endforeach
                </ul>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="matter" class="col-form-label text-md-right font-weight-bold">{{ __('Role') }}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @php
                        $arrayRoles = [];
                        foreach ($listRoleByUser as $role) {
                            array_push($arrayRoles, $role->role_id);
                        }
                    @endphp
                    <select id="multi-select-roles" name="select-roles[]" class="selectpicker-role show-menu-arrow form-control" data-style="form-control" title="{{ count($arrayRoles) > 0 ? count($arrayRoles) . ' items selected' : 'Choose Role' }}" multiple data-selected-text-format="count">
                        @foreach ($allRole as $role)
                            <option value="{{ $role->name }}" @if (in_array($role->id, $arrayRoles)) selected @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <div hidden>
                        <select id="multi-select-roles-hidden" class="selectpicker-role show-menu-arrow form-control" multiple>
                            @foreach ($allRole as $role)
                                <option value="{{ $role->name }}" @if (in_array($role->id, $arrayRoles)) selected @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="show-selected-roles" class="col-12 my-1"></div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="matter" class="col-form-label text-md-right font-weight-bold">{{ __('Offices') }}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @php
                        $arrayOfficeIdOfUser = [];
                        foreach($listOfficesByUser as $office1) {
                            array_push($arrayOfficeIdOfUser, $office1['id']);
                        }
                    @endphp
                    <select id="multi-select-office" name="select-office[]" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="{{ count($arrayOfficeIdOfUser) > 0 ? count($arrayOfficeIdOfUser) . ' items selected' : 'Choose Office' }}" multiple data-selected-text-format="count">
                        @foreach ($listOffices as $office)
                            <option data-name="{{ $office['name'] }}" value="{{ $office['id'] }}" @if (in_array($office['id'], $arrayOfficeIdOfUser)) selected @endif @if (in_array($office['id'], $arrayOfficeIdOfUser) && DB::table('cases')->where('office_id', $office['id'])->count() > 0) disabled @endif>{{ $office['name'] }}</option>
                        @endforeach
                    </select>
                    <div hidden>
                        <select id="multi-select-office-hidden" class="selectpicker show-menu-arrow form-control" multiple>
                            @foreach ($listOffices as $office)
                                <option value="{{ $office['id'] }}" @if (in_array($office['id'], $arrayOfficeIdOfUser)) selected @endif>{{ $office['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="selected-offices" class="col-12 my-1">
                    @foreach($listOfficesByUser as $office)
                        <div class="removable-select-{{ $office['id'] }}">
                            {{ $office['name'] }}
                            @if (DB::table('cases')->where('office_id', $office['id'])->count() == 0)
                            <i class="fa fa-times-circle float-right mr-1 mt-1 cursor-pointer remove-office-btn-{{ $office['id'] }}" aria-hidden="true" data-id="{{ $office['id'] }}" onclick="clearSelectedOffice('{{ $office['id'] }}');"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div hidden id="selected-offices-hidden" class="col-12 my-1">
                    @foreach($listOfficesByUser as $office)
                        <div class="removable-select-{{ $office['id'] }}">
                            {{ $office['name'] }}
                            @if (DB::table('cases')->where('office_id', $office['id'])->count() == 0)
                            <i class="fa fa-times-circle float-right mr-1 mt-1 cursor-pointer remove-office-btn-{{ $office['id'] }}" aria-hidden="true" data-id="{{ $office['id'] }}" onclick="clearSelectedOffice('{{ $office['id'] }}');"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group row col-form-label float-left m-0 mt-3">
                <button type="button" class="btn btn-primary collapse-btn-cancel pt-0" id="btn-cancel-edit-matter">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary collapse-btn-save pt-0" id="btn-edit-matter">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</form>

<div id="show-inform-matter">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-left">
            <span class="font-weight-bold">Matter Expertise:</span><br/>
            <ul class="list-matters">
                @foreach ($listSpecificMattersByUser as $type)
                    <li class="pt-1">{{ $type['parent'][0]['name'] }}/{{ $type['name'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <b>{{ __('Role: ') }}</b>
            @foreach ($listRoleByUser as $key => $roleName)
                {{ ucwords($roleName->name) }}@if ($key < (count($listRoleByUser) - 1)),&nbsp;@endif
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-left">
            <span class="font-weight-bold">Offices:</span>
            @foreach($listOfficesByUser as $office)
                <div class="my-2" style="border: 1px solid #F2F2F2;">
                    <div class="show-offices show-offices-{{ $office['id'] }} p-2 pl-3 cursor-pointer" data-toggle="collapse" data-target="#show-offices-{{ $office['id'] }}" onclick="changeArrow('{{ $office['id'] }}');">
                        <i class="fa fa-caret-right" aria-hidden="true"></i>&nbsp;&nbsp;{{ $office['name'] }}
                    </div>
                    <div class="col-12 collapse" id="show-offices-{{ $office['id'] }}">
                        <div class="py-2">{{ ucfirst($office['address']) }},
                            @if(!empty($office['state']))
                                {{ $office['state'] }},
                            @endif
                            @if(!empty($office['city']))
                                {{ $office['city'] }},
                            @endif
                            {{ ucfirst($office['country']) }}<br/>
                            <span class="font-weight-bold">Phone:</span>&nbsp;{{ $office['phone_number'] }}<br/>
                            <span class="font-weight-bold">Fax:</span>&nbsp;{{ $office['fax_number'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script type="text/javascript">
    @if (isset($response['alert-type']))
        @if ($response['alert-type'] == 'errors')
            toastr.error("{{ $response['message'] }}");
        @else
            toastr.success("{{ $response['message'] }}");
        @endif
    @endif

    var arrOfficeId = [];
    var arrTypeId = [];
    var arrRoleId = [];

    var oldOffices = $('select#multi-select-office-hidden').val();
    var oldTypes = $('select#select-matter-hidden').val();
    var oldRoles = $('select#multi-select-roles-hidden').val();

    $.fn.selectpicker.Constructor.BootstrapVersion = '4';

    $('.selectpicker').selectpicker({
        style: 'border-btn-select',
        liveSearchPlaceholder: 'Search',
        tickIcon: 'checkbox-select checkmark-select',
        size: 5
    });

    $('.selectpicker-role').selectpicker({
        style: 'border-btn-select',
        tickIcon: 'checkbox-select checkmark-select',
        size: 5
    });

    $('#show-edit-matter').hide();

    $('.position-pen-matter').click(function() {
        $('h3.edit-matter').html('Edit Matter Information');
        $('.position-pen-matter').hide();
        $('#show-inform-matter').hide();
        $('#show-edit-matter').show();
    });

    $('#btn-cancel-edit-matter').click(function() {
        $('h3.edit-matter').text('Matter Information');
        $('.position-pen-matter').show();
        $('#show-inform-matter').show();
        $('#show-edit-matter').hide();
        resetValueType();
        resetValueRole();
        resetValueOffice();
    });

    function resetValueType() {
        if (arrTypeId.length > 0) {
            arrTypeId.forEach(function(type) {
                $('.removable-matter-' + type).removeAttr('hidden');
            });
        }

        $('button[data-id="select-matter"]').attr("title", "{{ count($arrayTypeIdOfUser) > 0 ? count($arrayTypeIdOfUser) . ' items selected' : 'Choose Type - Subtype' }}");

        $('button[data-id="select-matter"]').find('.filter-option-inner-inner').text("{{ count($arrayTypeIdOfUser) > 0 ? count($arrayTypeIdOfUser) . ' items selected' : 'Choose Type - Subtype' }}");

        $('select#select-matter').val(oldTypes);
        var oldHtmlType = $('#selected-matter-hidden').html();
        $('#selected-matter').html(oldHtmlType);
    }

    function resetValueRole() {
        $('button[data-id="multi-select-roles"]').attr("title", "{{ count($arrayRoles) > 0 ? count($arrayRoles) . ' items selected' : 'Choose Role' }}");

        $('button[data-id="multi-select-roles"]').find('.filter-option-inner-inner').text("{{ count($arrayRoles) > 0 ? count($arrayRoles) . ' items selected' : 'Choose Role' }}");

        $('select#multi-select-roles').val(oldRoles);
        $('#show-selected-roles').html('Selected: ' + oldRoles);
    }

    function resetValueOffice() {
        if (arrOfficeId.length > 0) {
            arrOfficeId.forEach(function(office) {
                $(".removable-select-" + office).removeAttr('hidden');
            });
        }

        $('button[data-id="multi-select-office"]').attr("title", "{{ count($arrayOfficeIdOfUser) > 0 ? count($arrayOfficeIdOfUser) . ' items selected' : 'Choose Office' }}");

        $('button[data-id="multi-select-office"]').find('.filter-option-inner-inner').text("{{ count($arrayOfficeIdOfUser) > 0 ? count($arrayOfficeIdOfUser) . ' items selected' : 'Choose Office' }}");

        $('select#multi-select-office').val(oldOffices);
        var oldHtmlOffice = $('#selected-offices-hidden').html();
        $('#selected-offices').html(oldHtmlOffice);
    }

    // rotate arrow 90 - show office
    function changeArrow(officeId) {
        $('.show-offices-' + officeId + ' i').toggleClass('fa-caret-down');
        $('.show-offices-' + officeId + ' i').toggleClass('fa-caret-right');
    }

    $('#select-matter').on('change', function () {
        if ($(this).val() == '') {
            $('button[data-id="select-matter"]').attr('title', 'Choose Type - Subtype');
            $('button[data-id="select-matter"]').find('.filter-option-inner-inner').text('Choose Type - Subtype');
        }

        this.arrayData = $(this).val();
        this.showSelected = '';
        var survey = this;
        $('#select-matter option').each(function(i){
            let val = $(this).attr('data-name');
            if ($(this).is(':selected')) {
                var typeId = $(this).val();
                let getGroup = $(this).data("group");
                survey.showSelected += '<li class="removable-matter-' + typeId + '">' + getGroup + '/' + val + '<i class="fa fa-times-circle float-right mr-1 mt-1 cursor-pointer remove-matter-btn-' + typeId + '" aria-hidden="true" data-id="' + typeId + '" onclick="clearSelectedType(__typeId);"></i></li>'.replace("__typeId", "'" + typeId + "'");
            }
        });
        $('#selected-matter').html(survey.showSelected);
    });

    function clearSelectedType(typeId) {
        var count = 0;
        $("#select-matter").find("[value=" + typeId + "]").prop("selected", false);
        arrTypeId.push(typeId);
        $('.removable-matter-' + typeId).attr('hidden', 'hidden');
        $('#select-matter :selected').each(function(i, selected) {
            count++;
        });

        if(count == 0) {
            $('button[data-id="select-matter"]').attr("title", "Choose Type - Subtype");
            $('button[data-id="select-matter"]').find('.filter-option-inner-inner').text('Choose Type - Subtype');
        } else {
            $('button[data-id="select-matter"]').attr("title", count + " items selected");
            $('button[data-id="select-matter"]').find('.filter-option-inner-inner').text( count + ' items selected');
        }
    }

    $('#btn-edit-matter').click(function(e)
    {
        $('#multi-select-office option').attr('disabled', false);
        e.preventDefault();
        var data = {
            'select-roles': $('select#multi-select-roles').val(),
            'select-office': $('select#multi-select-office').val(),
            'select-matter': $('select#select-matter').val()
        };
        if(data['select-roles'] == '' || data['select-office'] == '' || data['select-matter'] == '') {
            return toastr.error("Data is missing!");
        }

        $("#show-edit-matter-form").submit();
    });

    $("#show-edit-matter-form").submit(function() {
        $(this).append(newFormField);
        return true;
    });

    // show selected roles
    $('#show-selected-roles').html('Selected: ' + $('#multi-select-roles').val());

    $('#multi-select-roles').change(function() {
        if ($(this).val() == '') {
            $('button[data-id="multi-select-roles"]').attr('title', 'Choose Role');
            $('button[data-id="multi-select-roles"]').find('.filter-option-inner-inner').text('Choose Role');
        }
        $('#show-selected-roles').html('Selected: ' + $(this).val());
    });

    // show selected offices
    $('#multi-select-office').change(function() {
        this.arrayData = $(this).val();
        this.showSelected = '';
        var survey = this;

        if ($(this).val() == '') {
            $('button[data-id="multi-select-office"]').attr('title', 'Choose Office');
            $('button[data-id="multi-select-office"]').find('.filter-option-inner-inner').text('Choose Office');
        }

        $('#multi-select-office option').each(function(i){
            let val = $(this).attr('data-name');
            if ($(this).is(':selected')) {
                var officeId = $(this).val();
                if ($(this).is(':disabled')) {
                    survey.showSelected += '<div class="removable-select-' + officeId + '">' + val + '</div>'.replace("__officeId", "'" + officeId + "'");
                } else {
                    survey.showSelected += '<div class="removable-select-' + officeId + '">' + val + '<i class="fa fa-times-circle float-right mr-1 mt-1 cursor-pointer remove-office-btn-' + officeId + '" aria-hidden="true" data-id="' + officeId + '" onclick="clearSelectedOffice(__officeId);"></i></div>'.replace("__officeId", "'" + officeId + "'");
                }
            }
        });
        $('#selected-offices').html(survey.showSelected);
    });

    function clearSelectedOffice(officeId) {
        var count = 0;
        $("#multi-select-office").find("[value=" + officeId + "]").prop("selected", false);
        arrOfficeId.push(officeId);
        $(".removable-select-" + officeId).attr('hidden', 'hidden');
        $('#multi-select-office :selected').each(function(i, selected) {
            count++;
        });

        if(count == 0) {
            $('button[data-id="multi-select-office"]').attr("title", "Choose Office");
            $('button[data-id="multi-select-office"]').find('.filter-option-inner-inner').text('Choose Office');
        } else {
            $('button[data-id="multi-select-office"]').attr("title", count + " items selected");
            $('button[data-id="multi-select-office"]').find('.filter-option-inner-inner').text( count + ' items selected');
        }
    }
</script>