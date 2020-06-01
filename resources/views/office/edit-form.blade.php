<!-- modal edit office -->
<div class="modal fade" id="modal-edit-office" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="edit-office-form" action="{{ route('updateOffice', $officeDetail->id) }}">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Edit Office') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Office Name *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-name" type="text" class="form-control" name="name" value="{{ $officeDetail->name }}" required autocomplete="name" placeholder="Input name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Address *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-address" type="text" class="form-control" name="address" value="{{ $officeDetail->address }}" required autocomplete="address" placeholder="Input address" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Country *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select id="edit-select-country" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a country" name="country" required>
                                <option value="" selected>Choose a country</option>
                                @foreach ($listCountries as $country)
                                    <option value="{{ $country->id }}" @if ($country->id == $officeDetail->country) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('State *') }}</label>

                        <div class="col-md-12 col-lg-9" id="edit-select-state-form">
                            <select id="edit-select-state" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a state" name="state" @if (count($listStates) == 0) disabled @endif>
                                <option value="" selected>Choose a state</option>
                                @foreach ($listStates as $state)
                                    <option value="{{ $state->id }}" @if ($state->id == $officeDetail->state) selected @endif>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('City *') }}</label>

                        <div class="col-md-12 col-lg-9" id="edit-select-city-form">
                            <select id="edit-select-city" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a city" name="city" @if (count($listCities) == 0) disabled @endif>
                                <option value="" selected>Choose a city</option>
                                @foreach ($listCities as $city)
                                    <option value="{{ $city->id }}" @if ($city->id == $officeDetail->city) selected @endif>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Postcode *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-postal-code" type="text" class="form-control" name="postal_code" value="{{ $officeDetail->postal_code }}" required autocomplete="postal_code" placeholder="Input postcode" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Phone *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-phone-number" type="text" class="form-control" name="phone_number" value="{{ $officeDetail->phone_number }}" required autocomplete="phone_number" placeholder="Input phone number" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Fax') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-fax-number" type="text" class="form-control" name="fax_number" value="{{ $officeDetail->fax_number }}" autocomplete="fax_number" placeholder="Input fax number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="submit" class="btn btn-add-modal" id="btn-add-office-modal">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#modal-edit-office').modal('show');

    $('#edit-select-country').change(function() {
        var countryId = $(this).val();
        var url = "{{ route('getListStates', '__countryId') }}".replace('__countryId', countryId);
        if (countryId) {
            $.ajax({
                type: "get",
                data: {'action' : 'edit'},
                url: url,
                success: function(response) {
                    $("#edit-select-state-form").html(response.html);
                    let html = '<select id="edit-select-city" class="show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose a city" name="city" disabled><option value="" selected>Choose a city</option></select>';
                    $("#edit-select-city-form").html(html);
                }
            });
        }
    });

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