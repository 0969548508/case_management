<script type="text/javascript">
    $.fn.selectpicker.Constructor.BootstrapVersion = '4';

    if ($("#select-specific-user").is(':checked')) {
        $("#select-specific-user").val(1);
    }

    $("#btn-add-investigator-modal").attr("disabled", "disabled");

    $("#matter-type-icon, #matter-office-icon, #matter-client-location-icon").click(function () {
        $("#edit-general-info").modal("show");
    });

    function showModal(paramRole) {
        $("#role-id").val(paramRole[0]);
        var url = "{{ route('getListUserByRoleId', ['__roleId', $detailMatter['id']]) }}".replace('__roleId', paramRole[0]);
        if(paramRole[0]) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    $("#load-user-by-role").html(response.html);
                    setTimeout(function(){
                        $("#addInvestigatorTitle").text("Add "+paramRole[1].replace(/\b\w/g, v => v.toUpperCase()));
                        $("#add-investigator").modal("show");
                    }, 500);
                }
            });
        }
    }

    // set color for radio text
    if ($("#select-specific-user").is(':checked'))
    {
        $("#slt-specific-user").css("font-weight", "bold");
    }

    $("#select-specific-user").click(function () {
        $("#slt-specific-user").css("font-weight", "bold");
        $("#slt-assign-to-all-available-user").css("font-weight", "normal");
    });

    $("#assign-to-all-available-user").click(function () {
        $("#slt-assign-to-all-available-user").css("font-weight", "bold");
        $("#slt-specific-user").css("font-weight", "normal");
    });
    // end set color for radio text

    $("#select-specific-user, #slt-specific-user").change(function(e) {
        e.preventDefault();
        $(this).val(1);
        $("#assign-to-all-available-user").removeAttr("checked");
        $("#assign-to-all-available-user").val(0);
        $("#div-dropdown-user").css("visibility", "visible");
        $("#div-selected-user").css("visibility", "visible");
    });

    $("#assign-to-all-available-user, #slt-assign-to-all-available-user").change(function(e) {
        e.preventDefault();
        $(this).val(0);
        $("#select-specific-user").removeAttr("checked");
        $("#select-specific-user").val(1);
        $("#div-dropdown-user").css("visibility", "hidden");
        $("#div-selected-user").css("visibility", "hidden");
    });

    $("#btn-add-investigator-modal").click(function (e){
        e.preventDefault();
        $("#assign-investigator-form").submit();
    });

    // edit general info
    $("#btn-edit-general-info-modal").click(function (e) {
        e.preventDefault();
        $("#edit-general-info-form").submit();
    });
    // end edit general info

    // for add new client
    $("#collapse-add-account-client-information").click (function () {
        $("#collapse-add-account-client-information").attr("hidden", "hidden");
    });

    $("#btn-cancel-save-account-client, #slt-btn-cancel-save-account-client").click (function () {
        $("#collapse-add-account-client-information").removeAttr("hidden");
        $('#add-account-client-information').removeClass("show");
    });

        $("#select-new-client").val(1);
        $("#slt-new-client").css("font-weight", "bold");

    $("#select-new-client, #slt-new-client").change(function(e) {
        e.preventDefault();
        $(this).val(1);
        $("#slt-new-client").css("font-weight", "bold");
        $("#slt-client-from-client-list").css("font-weight", "normal");
        $("#show-hide-add-new-client").removeAttr("hidden");
        $("#show-hide-select-a-client-from-list").attr("hidden", "hidden");
        $("#select-client-from-client-list").removeAttr("checked");
        $("#select-client-from-client-list").val(0);
    });

    $("#select-client-from-client-list, #slt-client-from-client-list").change(function(e) {
        e.preventDefault();
        $(this).val(0);
        $("#slt-client-from-client-list").css("font-weight", "bold");
        $("#slt-new-client").css("font-weight", "normal");
        $("#show-hide-add-new-client").attr("hidden", "hidden");
        $("#show-hide-select-a-client-from-list").removeAttr("hidden");
        $("#select-new-client").removeAttr("checked");
        $("#select-new-client").val(1);
    });

    if ($("#select-insurer-new-client").is(':checked')) {
        $("#select-insurer-new-client").val(1);
    }

    if ($("#select-insurer-new-client").is(':checked'))
    {
        $("#slt-insurer-new-client").css("font-weight", "bold");
    }

    $("#select-insurer-new-client, #slt-insurer-new-client").change(function(e) {
        e.preventDefault();
        $(this).val(1);
        $("#slt-insurer-new-client").css("font-weight", "bold");
        $("#slt-insurer-client-from-client-list").css("font-weight", "normal");
        $("#select-insurer-client-from-client-list").removeAttr("checked");
        $("#select-insurer-client-from-client-list").val(0);
        $("#show-hide-insurer-slt-a-client").attr("hidden", "hidden");
        $("#show-hide-insurer-add-new-client").removeAttr("hidden");
    });

    $("#select-insurer-client-from-client-list, #slt-insurer-client-from-client-list").change(function(e) {
        e.preventDefault();
        $(this).val(0);
        $("#slt-insurer-client-from-client-list").css("font-weight", "bold");
        $("#slt-insurer-new-client").css("font-weight", "normal");
        $("#select-insurer-new-client").removeAttr("checked");
        $("#select-insurer-new-client").val(1);
        $("#show-hide-insurer-add-new-client").attr("hidden", "hidden");
        $("#show-hide-insurer-slt-a-client").removeAttr("hidden")
    });

    $("#collapse-insurer").click (function () {
        $("#collapse-insurer").attr("hidden", "hidden");
    });

    $("#btn-cancel-save-insurer-client, #slt-btn-cancel-save-insurer-client").click (function () {
        $("#collapse-insurer").removeAttr("hidden");
        $('#add-insurer-client-information').removeClass("show");
    });

    $("#instructing-select-new-client").val(1);
    $("#instructing-slt-new-client").css("font-weight", "bold");

    $("#instructing-select-new-client, #instructing-slt-new-client").change(function(e) {
        e.preventDefault();
        $(this).val(1);
        $("#instructing-slt-new-client").css("font-weight", "bold");
        $("#instructing-slt-client-from-client-list").css("font-weight", "normal");
        $("#instructing-select-client-from-client-list").removeAttr("checked");
        $("#instructing-select-client-from-client-list").val(0);
        $("#instructing-show-hide-select-a-client-from-list").attr("hidden", "hidden");
        $("#instructing-show-hide-add-new-client").removeAttr("hidden");
    });

    $("#instructing-select-client-from-client-list, #instructing-slt-client-from-client-list").change(function(e) {
        e.preventDefault();
        $(this).val(0);
        $("#instructing-slt-client-from-client-list").css("font-weight", "bold");
        $("#instructing-slt-new-client").css("font-weight", "normal");
        $("#instructing-select-new-client").removeAttr("checked");
        $("#instructing-select-new-client").val(1);
        $("#instructing-show-hide-add-new-client").attr("hidden", "hidden");
        $("#instructing-show-hide-select-a-client-from-list").removeAttr("hidden");
    });

    $("#collapse-add-instructing-client").click(function() {
        $("#collapse-add-instructing-client").attr("hidden", "hidden");
    });

    $("#instructing-btn-cancel-save-account-client, #instructing-slt-btn-cancel-save-account-client").click(function() {
        $("#collapse-add-instructing-client").removeAttr("hidden");
        $('#add-instructing-client').removeClass("show");
    });
    // end for add new client

    // country - state - city
    $("select#select-country").change(function() {
        var countryId = $("#select-country").find(':selected').attr('data-value');
        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#select-state").empty();
                        $("#select-state").append('<option value="">Choose a state</option>');
                        $.each(response, function(key, value) {
                            $("#select-state").append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });

    $("select#select-state").change(function() {
        var stateId = $("#select-state").find(':selected').attr('data-value');
        var url = "{{ route('getCities', '__stateId') }}".replace('__stateId', stateId);
        if(stateId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#select-city").empty();
                        $("#select-city").append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#select-city").append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });
    // end country - state - city

    // instructing country - state - city
    $("select#instructing-select-country").change(function() {
        var countryId = $("#instructing-select-country").find(':selected').attr('data-value');
        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#instructing-select-state").empty();
                        $("#instructing-select-state").append('<option value="">Choose a state</option>');
                        $.each(response, function(key, value) {
                            $("#instructing-select-state").append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });

    $("select#instructing-select-state").change(function() {
        var stateId = $("#instructing-select-state").find(':selected').attr('data-value');
        var url = "{{ route('getCities', '__stateId') }}".replace('__stateId', stateId);
        if(stateId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#instructing-select-city").empty();
                        $("#instructing-select-city").append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#instructing-select-city").append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    });
    // end instructing country - state - city

    function checkCountryClientLocation(clientLocationId) {
        var countryId = $("#edit-select-country-"+clientLocationId).find(':selected').attr('data-value');

        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#edit-select-state-"+clientLocationId).empty();
                        $("#edit-select-state-"+clientLocationId).append('<option value="">Choose a state</option>');
                        $("#edit-select-city-"+clientLocationId).append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#edit-select-state-"+clientLocationId).append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    }

    function checkStateClientLocation(clientLocationId) {
        var stateId = $("#edit-select-state-"+clientLocationId).find(':selected').attr('data-value');

        var url = "{{ route('getCities', '__stateId') }}".replace('__stateId', stateId);
        if(stateId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#edit-select-city-"+clientLocationId).empty();
                        $("#edit-select-city-"+clientLocationId).append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#edit-select-city-"+clientLocationId).append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    }
    // end edit account client

    function checkCountryInstructingClientLocation(instructingClientLocationId) {
        var countryId = $("#edit-instructing-select-country-"+instructingClientLocationId).find(':selected').attr('data-value');

        var url = "{{ route('getStates', '__countryId') }}".replace('__countryId', countryId);
        if(countryId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#edit-instructing-select-state-"+instructingClientLocationId).empty();
                        $("#edit-instructing-select-state-"+instructingClientLocationId).append('<option value="">Choose a state</option>');
                        $("#edit-instructing-select-city-"+instructingClientLocationId).empty();
                        $("#edit-instructing-select-city-"+instructingClientLocationId).append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#edit-instructing-select-state-"+instructingClientLocationId).append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    }

    function checkStateInstructingClientLocation(instructingClientLocationId) {
        var stateId = $("#edit-instructing-select-state-"+instructingClientLocationId).find(':selected').attr('data-value');

        var url = "{{ route('getCities', '__stateId') }}".replace('__stateId', stateId);
        if(stateId) {
            $.ajax({
                type: "get",
                url: url,
                success: function(response)
                {
                    if(response)
                    {
                        $("#edit-instructing-select-city-"+instructingClientLocationId).empty();
                        $("#edit-instructing-select-city-"+instructingClientLocationId).append('<option value="">Choose a city</option>');
                        $.each(response, function(key, value) {
                            $("#edit-instructing-select-city-"+instructingClientLocationId).append('<option value="'+value['name']+'" data-value="'+value['id']+'">'+value['name']+'</option>');
                        });
                    }
                }
            });
        }
    }
    // end edit instructing client

    // assign to me
    $("#checkmark-assign-to-me, #input-assign-to-me").click(function(e) {
        $("#add-assign-to-me-investigator").modal("show");
    });

    $("#btn-add-assign-to-me-modal").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("#assign-to-me-form").submit();
    });
    // end assign to me

    // cancel button
    $("#edit-btn-cancel-save-account-client, #edit-btn-cancel-save-insurer-client, #edit-instructing-btn-cancel-save-account-client").click(function () {
        location.reload();
    });

    var __paramToDelete;
    function showModalDeleteAssign(paramToDelete, userId) {
        __paramToDelete = paramToDelete;
        $("#modalDeleteUserMatter").modal("show");
    }

    function sendDelete() {
        let __roleId = __paramToDelete[0];
        let __userId = __paramToDelete[1];
        var url = "{{ route('deleteUserMatter', ['__roleId', $detailMatter['id'], '__userId']) }}".replace('__roleId', __roleId).replace('__userId', __userId);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: url,
            method: 'get',
            async: false,
            success: function(response){
                location.reload();
            }
        });
    }

    function showModalAddDate(param) {
        $("#addDateTitle").text("Select "+param[1].replace(/\b\w/g, v => v.toUpperCase()));
        $("#date-type").val(param[0]);
        $("#add-date").modal("show");
        $("#div-date").datepicker({
            format: "dd-mm-yyyy",
            weekStart: 1,
            maxViewMode: 2,
            todayBtn: false,
            todayHighlight: true,
            orientation: "bottom"
        });
    }

    $("#btn-cancel-add-assign-to-me-modal").click (function () {
        $("#input-assign-to-me").prop("checked", false);
    });

    // edit assign to me
    $("#edit-checkmark-assign-to-me, #edit-input-assign-to-me").click(function(e) {
        $("#edit-assign-to-me-investigator").modal("show");
    });

    $("#edit-btn-cancel-add-assign-to-me-modal").click (function () {
        $("#edit-input-assign-to-me").prop("checked", true);
    });
</script>