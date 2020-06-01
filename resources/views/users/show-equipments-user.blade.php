@foreach($equipmentInfo as $equipment)
    <div class="row">
        <div class="col-md-12 col-sm-12 col-form-label text-md-left">
            <div class="card card-address">
                <div class="card-body">
                    <div class="font-bold">
                        {{ $equipment['type'] }}
                        <span class="cursor-pointer position-absolute-pen span-collapse-edit-equipment" data-toggle="collapse" data-target="#edit-equipment-{{ $equipment['id'] }}" id="span-edit-equipment" onclick="showEditEquipment('{{ $equipment['id'] }}')">
                            <img src="/images/btn_pen.png">
                        </span>&nbsp;&nbsp;
                        <span class="cursor-pointer position-absolute-delete">
                            <img src="/images/img-delete.png" id="{{ $equipment['id'] }}" onclick="deleteEquipment('{{ $equipment['id'] }}')">
                        </span>
                    </div>

                    <div class="pt-2">
                        <b>Model: </b>{{ $equipment['model'] }}
                    </div>

                    <!-- edit equipment -->
                    <div class="row collapse collapse-edit-equipment mt-2" id="edit-equipment-{{ $equipment['id'] }}" data-id="">
                        <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                            <div><b><i style="text-decoration:underline;">Edit form</i></b></div>
                            <div class="row bg-collapse">
                                <div class="col-md-12 col-sm-12 col-form-label text-md-left pt-3">
                                    <form action="#" method="POST" id="edit-equipment-form">
                                    @csrf
                                        <div class="form-group row">
                                            <label for="edit-type-equipment" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Type') }}</label>

                                            <div class="col-md-12 col-lg-9">
                                                <input id="edit-type-equipment-{{ $equipment['id'] }}" type="text" class="form-control" name="edit-type-equipment" required autocomplete="edit-number-equipment" placeholder="Input equipment" value="{{ $equipment['type'] }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="edit-country-equipment" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Model') }}</label>

                                            <div class="col-md-12 col-lg-9">
                                                <input id="edit-model-equipment-{{ $equipment['id'] }}" type="text" class="form-control" name="edit-model-equipment" required autocomplete="edit-model-equipment" placeholder="Input model" value="{{ $equipment['model'] }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-edit-equipment" onclick="cancelEditEquipment('{{ $equipment['id'] }}');">
                                                    {{ __('Cancel') }}
                                                </button>
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-edit-equipment" btn-edit-id="{{ $equipment['id'] }}" onclick="editEquipment('{{ $equipment['id'] }}');">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@can('edit users')
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-form-label text-md-left">
            <a href="" data-toggle="collapse" data-target="#add-equipments">
                <img src="/images/btn_plus.png" id="img-add-equipments">
                <i class="custom-font" id="i-add-equipments">Add Equipments</i>
            </a>
        </div>
    </div>
@endcan

<div class="row collapse bg-collapse" id="add-equipments">
    <div class="col-md-12 col-sm-12 col-form-label text-md-left">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <label for="type-equipments" class="col-form-label text-md-left">{{ __('Type') }}</label>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <input id="type-equipments" type="text" class="form-control" name="type-equipments" required autocomplete="type-equipments" placeholder="Input equipments" pattern=".*\S+.*">
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <label for="model-equipments" class="col-form-label text-md-left">{{ __('Model') }}</label>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <input id="model-equipments" type="text" class="form-control" name="model-equipments" required autocomplete="model-equipments" placeholder="Input Model" pattern=".*\S+.*">
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-equipments" onclick="cancelAddEquipment();">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-equipments">
                    {{ __('Save') }}
                </button>
            </div>
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

    $("#i-add-equipments, #img-add-equipments").click(function () {
        $(".collapse-edit-equipment").collapse('hide');
    });

    $(".span-collapse-edit-equipment").click(function () {
        $("#add-equipments").collapse('hide');
    });

    $("#btn-save-equipments").click (function () {
        var typeEquipment = $("input#type-equipments").val(),
            modelEquipment = $("input#model-equipments").val();

        if (typeEquipment == '' || typeEquipment == undefined) {$('input#type-equipments')[0].focus(); return toastr.error("Please fill out this field!");}
        if (modelEquipment == '' || modelEquipment == undefined) {$('input#model-equipments')[0].focus(); return toastr.error("Please fill out this field!");}

        let url = "{{ route('createUserEquipment', $detailUser['id']) }}";
        let data = {
            "type": typeEquipment,
            "model": modelEquipment,
        }
        if(data['type'].trim() == '' || data['model'].trim() == '') {
            return toastr.error("Please fill out this field!");
        }

        sendAjaxToAddAndEditEquipment(url, data);
    });

    var oldTypeEquipment = '';
    var oldModelEquipment = '';
    function showEditEquipment(equipmentId) {
        if ($("div.collapse-edit-equipment").hasClass("show")) {
            $(".collapse-edit-equipment.show").removeClass("show");
        }

        oldTypeEquipment = $("#edit-type-equipment-"+equipmentId).val();
        oldModelEquipment = $("#edit-model-equipment-"+equipmentId).val();
    }

    function editEquipment(equipmentId) {
        let typeEquipment = $("#edit-type-equipment-"+equipmentId).val(),
            modelEquipment = $("#edit-model-equipment-"+equipmentId).val();

        if (typeEquipment == '' || typeEquipment == undefined) {$("#edit-type-equipment-"+equipmentId)[0].focus(); return toastr.error("Please fill out this field!");}
        if (modelEquipment == '' || modelEquipment == undefined) {$("#edit-model-equipment-"+equipmentId)[0].focus(); return toastr.error("Please fill out this field!");}

        let url = "{{ route('editUserEquipment', $detailUser['id']) }}";
        let data = {
            "id": equipmentId,
            "type": typeEquipment,
            "model": modelEquipment,
        }

        sendAjaxToAddAndEditEquipment(url, data);
    }

    function cancelAddEquipment() {
        $("#add-equipments").collapse('hide');
        $("#type-equipments").val('');
        $("#model-equipments").val('');
    }

    function cancelEditEquipment(equipmentId) {
        $("#edit-type-equipment-"+equipmentId).val(oldTypeEquipment);
        $("#edit-model-equipment-"+equipmentId).val(oldModelEquipment);
        $("#edit-equipment-"+equipmentId).collapse('hide');
    }

    function sendAjaxToAddAndEditEquipment(url, data) {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        jQuery.ajax({
            url: url,
            data: data,
            method: 'post',
            async: false,
            success: function(response){
                $("div#show-equipments").html(response.html);
                $('#modal-delete-equipment').modal('hide');
                $('.modal-backdrop.fade.show').hide();
            }
        });
    }
</script>