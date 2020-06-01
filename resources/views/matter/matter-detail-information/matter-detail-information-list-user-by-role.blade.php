<link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<select id="slt-investigator" name="list-user[]" class="form-control show-menu-arrow selectpicker selectpicker-investigator" data-live-search="true" data-style="form-control" title="Select user (s)" data-selected-text-format="count > 0" multiple="" tabindex="-98">
    @foreach ($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
    @endforeach
</select>
<script type="text/javascript">
    $( document ).ready(function() {
        $('button.btn.dropdown-toggle.border-btn-select').css("color", "#000000");
    });

    $('.selectpicker-investigator').selectpicker({
        style: 'border-btn-select',
        liveSearchPlaceholder: 'Search',
        tickIcon: 'checkbox-select checkmark-select',
        showIcon: true,
        size: 20,
        countSelectedText: function(num) {
            if (num == 1) {
                return "{0} selected";
            } else {
                return "{0} selecteds";
            }
        }
    });

    $('.selectpicker-investigator').selectpicker("refresh");

    $('#slt-investigator').change(function() {
        let count = 0;
        $('#slt-investigator :selected').each(function(i, selected) {
            count ++;
        });

        if (count == 0) {
            $("#btn-add-investigator-modal").attr("disabled", "disabled");
        } else {
            $("#btn-add-investigator-modal").removeAttr("disabled");
        }

        this.arrayData = $(this).val();
        this.showSelected = '';
        var survey = this;
        $('#slt-investigator option').each(function(i){
            let val = $(this).text();
            if ($(this).is(':selected')) {
                var userId = $(this).val();
                survey.showSelected += '<div class="div-selected-user-'+userId+'"><i style="font-style: normal;">'+val+'</i>&nbsp;&nbsp;<img style="cursor: pointer;" class="img-remove-selected-user-'+userId+'" onclick="removeSelectedUser(__selectedUserId)" src="/images/img_remove.png"></div>'.replace("__selectedUserId", "'"+userId+"'");
            }
        });
        $('#show-sub-text-selected-user').html(survey.showSelected);
    });

    // remove selected user
    function removeSelectedUser (userId) {
        var count = 0;
        $('.div-selected-user-'+userId).attr("hidden",  "hidden");
        $(".selectpicker-investigator").find("[value="+userId+"]").prop("selected", false);

        $('#slt-investigator :selected').each(function(i, selected) {
            count ++;
        });

        if (count == 0) {
            $("#btn-add-investigator-modal").attr("disabled", "disabled");
            $('button .filter-option .filter-option-inner .filter-option-inner-inner').text("Select user (s)");
        } else {
            $("#btn-add-investigator-modal").removeAttr("disabled");
            if (count == 1) {
                $('button .filter-option .filter-option-inner .filter-option-inner-inner').text(count+" selected");
            } else {
                $('button .filter-option .filter-option-inner .filter-option-inner-inner').text(count+" selecteds");
            }
        }
    }

    $("#btn-cancel-add-investigator-modal, #btn-close-add-investigator").click(function (e){
        e.preventDefault();
        const parent = document.getElementById("show-sub-text-selected-user");
        while (parent.firstChild) {
            parent.firstChild.remove();
        }
    });
</script>