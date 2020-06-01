@if ($type == 'state')
    <select id="edit-select-state" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a state" name="state" @if (count($listStates) == 0) disabled @endif>
        <option value="" selected>Choose a state</option>
        @foreach ($listStates as $state)
            <option value="{{ $state->id }}">{{ $state->name }}</option>
        @endforeach
    </select>

    <script>
        $('#edit-select-state').change(function() {
            var stateId = $(this).val();
            var url = "{{ route('getListCities', '__stateId') }}".replace('__stateId', stateId);
            if (stateId) {
                $.ajax({
                    type: "get",
                    data: {'action' : 'edit'},
                    url: url,
                    success: function(response) {
                        $("#edit-select-city-form").html(response.html);
                    }
                });
            }
        });
    </script>
@elseif ($type == 'city')
    <select id="edit-select-city" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a city" name="city" @if (count($listCities) == 0) disabled @endif>
        <option value="" selected>Choose a city</option>
        @foreach ($listCities as $city)
            <option value="{{ $city->id }}">{{ $city->name }}</option>
        @endforeach
    </select>
@endif