@if (!empty($accountClientInfo))
    <div class="col-xl-12" id="div-account-client-information">
        <div class="row">
            <div class="col-xl-12 mb-4">
                <div class="row m-0">
                    <p class="mb-0 custom-text-matter">Account Client</p>
                    @can('edit matters information')
                        <span class="cursor-pointer position-absolute-pen mr-0" data-toggle="collapse" data-target="#edit-account-client-information" id="show-edit-account-client"><img src="/images/btn_pen.png">&nbsp;<i>Edit account client</i></span>
                    @endcan
                </div>
                <div class="row m-0">
                    <h5><b>{{ $accountClientInfo['name'] }}</b></h5>
                </div>
                <div class="row m-0 mb-2">
                    <b>ABN: </b>&nbsp; {{ $accountClientInfo['abn'] }}
                </div>
                <div class="row m-0">
                    <b>Billing number: </b>&nbsp; {{ $accountClientInfo['billing_number'] }}
                </div>
            </div>
            
            <div class="col-xl-12 m-0">
                <div class="row">
                    @if (!empty($accountClientLocationInfo))
                        <div class="col-xl-6">
                            <p class="mb-0 custom-text-matter">Account Client Location</p>
                            <div class="row m-0">
                                <h5><b>{{ $accountClientLocationInfo['location_name'] }}</b></h5>
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Location ABN: </b>&nbsp; {{ $accountClientLocationInfo['abn'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <p class="mb-0 m-0">{{ $accountClientLocationInfo['address_1'] }}</p>
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Primary phone: </b>&nbsp; {{ $accountClientLocationInfo['primary_phone'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Secondary phone: </b>&nbsp; {{ $accountClientLocationInfo['secondary_phone'] }}
                            </div>

                            <div class="row m-0">
                                <b>Fax: </b>&nbsp; {{ $accountClientLocationInfo['fax'] }}
                            </div>
                        </div>
                    @endif

                    @if (!empty($accountContactInfo))
                        <div class="col-xl-6">
                            <p class="mb-0 custom-text-matter">Account Contact</p>
                            <div class="row m-0">
                                <h5><b>{{ $accountContactInfo['name'] }}</b></h5>
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Job title: </b>&nbsp; {{ $accountContactInfo['job_title'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Email: </b>&nbsp; {{ $accountContactInfo['email'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Primary phone: </b>&nbsp; {{ $accountContactInfo['phone'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Secondary phone: </b>&nbsp; {{ $accountContactInfo['mobile'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Fax: </b>&nbsp; {{ $accountContactInfo['fax'] }}
                            </div>

                            <div class="row m-0">
                                <b>Note: </b>&nbsp; {{ $accountContactInfo['note'] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 collapse" id="edit-account-client-information">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="row ml-0 mb-1">
                    <b style="text-decoration: underline;"><i>Edit account client form</i></b>
                </div>
            </div>
        </div>

        <div class="row bg-collapse">
            <form action="{{ route('editAccountInstructingInformation', [ $accountClientInfo['id'], $detailMatter['id']]) }}" method="POST" id="edit-account-client-form">
                @csrf
                <input hidden type="text" name="account-client-type" value="0">
                <div class="col-xl-12 col-form-label text-md-left" id="edit-show-hide-add-new-client">
                    <div class="row">
                        @if (!empty($accountClientInfo))
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('ACCOUNT CLIENT') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-client-name" class="col-form-label text-md-left title">{{ __('Account Client Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-client-name" type="text" class="form-control" name="edit-account-client-name" required autocomplete="edit-account-client-name" placeholder="Input client name" pattern=".*\S+.*" value="{{ $accountClientInfo['name'] }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-new-client-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-new-client-abn" type="text" class="form-control" name="edit-new-client-abn" required autocomplete="edit-new-client-abn" placeholder="48 123 123 124" pattern=".*\S+.*" value="{{ $accountClientInfo['abn'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-billing-number" class="col-form-label text-md-left title">{{ __('Billing Number') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-billing-number" type="text" class="form-control" name="edit-billing-number" required autocomplete="edit-billing-number" placeholder="Input number" pattern=".*\S+.*" value="{{ $accountClientInfo['billing_number'] }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12"><hr></div>

                        @if (!empty($accountClientLocationInfo))
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('ACCOUNT CLIENT LOCATION') }}</h5>
                            </div>
                            <input hidden name="account-client-location-id" value="{{ $accountClientLocationInfo['id'] }}">
                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-location-name" class="col-form-label text-md-left title">{{ __('Location Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-location-name" type="text" class="form-control" name="edit-location-name" required autocomplete="edit-location-name" placeholder="Input location name" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['location_name'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-new-location-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-new-location-abn" type="text" class="form-control" name="edit-new-location-abn" required autocomplete="edit-new-location-abn" placeholder="48 123 123 124" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['abn'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-address-1" class="col-form-label text-md-left title">{{ __('Address 1') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-address-1" type="text" class="form-control" name="edit-address-1" required autocomplete="edit-address-1" placeholder="Input address 1" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['address_1'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-address-2" class="col-form-label text-md-left title">{{ __('Address 2') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-address-2" type="text" class="form-control" name="edit-address-2" required autocomplete="edit-address-2" placeholder="Input address 2" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['address_2'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-select-country" class="col-form-label text-md-left title">{{ __('Country') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="edit-select-country" id="edit-select-country-{{ $accountClientLocationInfo['id'] }}" onchange="checkCountryClientLocation('{{ $accountClientLocationInfo['id'] }}')">
                                            @foreach($listCountries as $country)
                                                @if ($country->name == $accountClientLocationInfo['country'])
                                                    <option selected value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                                @else
                                                    <option value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-select-state" class="col-form-label text-md-left title">{{ __('State') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="edit-select-state" id="edit-select-state-{{ $accountClientLocationInfo['id'] }}" onchange="checkStateClientLocation('{{ $accountClientLocationInfo['id'] }}')">
                                            @if(!empty($accountClientLocationInfo['state']))
                                                <option value="{{ $accountClientLocationInfo['state'] }}">{{ $accountClientLocationInfo['state'] }}</option>
                                            @else
                                                <option value="">Choose a state</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-select-city" class="col-form-label text-md-left title">{{ __('City') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="edit-select-city" id="edit-select-city-{{ $accountClientLocationInfo['id'] }}">
                                            @if(!empty($accountClientLocationInfo['city']))
                                                <option value="{{ $accountClientLocationInfo['city'] }}">{{ $accountClientLocationInfo['city'] }}</option>
                                            @else
                                                <option value="">Choose a city</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-new-location-postcode" class="col-form-label text-md-left title">{{ __('Postcode') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-new-location-postcode" type="text" class="form-control" name="edit-new-location-postcode" required autocomplete="edit-new-location-postcode" placeholder="Input postcode" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['postcode'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-primary-phone" class="col-form-label text-md-left title">{{ __('Primary Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-primary-phone" type="text" class="form-control" name="edit-primary-phone" required autocomplete="edit-primary-phone" placeholder="Input number" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['primary_phone'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-secondary-phone" class="col-form-label text-md-left title">{{ __('Secondary phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-secondary-phone" type="text" class="form-control" name="edit-secondary-phone" required autocomplete="edit-secondary-phone" placeholder="Input number" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['secondary_phone'] }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-fax" type="text" class="form-control" name="edit-fax" required autocomplete="edit-fax" placeholder="Input number" pattern=".*\S+.*" value="{{ $accountClientLocationInfo['fax'] }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12"><hr></div>

                        @if (!empty($accountContactInfo))
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('ACCOUNT CONTACT (CLIENT LOCATION CONTACT)') }}</h5>
                            </div>
                            <input hidden name="account-client-contact-id" value="{{ $accountContactInfo['id'] }}">
                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-name" class="col-form-label text-md-left title">{{ __('Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-contact-name" type="text" class="form-control" name="edit-account-contact-name" required autocomplete="edit-account-contact-name" placeholder="Input given name" pattern=".*\S+.*" value="{{ $accountContactInfo['name'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-job-title" class="col-form-label text-md-left title">{{ __('Job Title') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-contact-job-title" type="text" class="form-control" name="edit-account-contact-job-title" required autocomplete="edit-account-contact-job-title" placeholder="Input job title" pattern=".*\S+.*" value="{{ $accountContactInfo['job_title'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-email" class="col-form-label text-md-left title">{{ __('Email') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-contact-email" type="email" class="form-control" name="edit-account-contact-email" required autocomplete="edit-account-contact-email" placeholder="Input email" pattern=".*\S+.*" value="{{ $accountContactInfo['email'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-phone" class="col-form-label text-md-left title">{{ __('Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-contact-phone" type="text" class="form-control" name="edit-account-contact-phone" required autocomplete="edit-account-contact-phone" placeholder="Input phone number" pattern=".*\S+.*" value="{{ $accountContactInfo['phone'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-mobile" class="col-form-label text-md-left title">{{ __('Mobile') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-contact-mobile" type="text" class="form-control" name="edit-account-contact-mobile" required autocomplete="edit-account-contact-mobile" placeholder="Input mobile number" pattern=".*\S+.*" value="{{ $accountContactInfo['mobile'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-account-contact-fax" type="text" class="form-control" name="edit-account-contact-fax" required autocomplete="edit-account-contact-fax" placeholder="Input fax number" pattern=".*\S+.*" value="{{ $accountContactInfo['fax'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-account-contact-note" class="col-form-label text-md-left title">{{ __('Note') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <textarea rows="3" style="width:100%;" class="form-control" name="edit-account-contact-note">{{ $accountContactInfo['note'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-12 col-form-label text-md-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="edit-btn-cancel-save-account-client">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="edit-btn-save-account-client">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="col-lg-12 col-sm-12 col-md-12 mb-0">
        <p class="mb-0 custom-text-matter">Account Client</p>
    </div>

    @can('edit matters information')
        <div class="col-lg-12 col-sm-12 col-md-12 mb-3">
            <a href="#" id="collapse-add-account-client-information" data-toggle="collapse" data-target="#add-account-client-information">
                <img src="/images/btn_plus.png">
                <i class="custom-font">Add information</i>
            </a>
        </div>

        <div class="col-xl-12 collapse" id="add-account-client-information">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="row ml-0">
                        <p class="pr-3 mb-0" hidden>
                            <input type="radio" name="new-client" id="select-new-client" checked="checked">
                            <label for="select-new-client" id="slt-new-client">{{ __('New client') }}</label>
                        </p>

                        <p class=" mb-0" hidden>
                            <input type="radio" name="new-client" id="select-client-from-client-list">
                            <label for="select-client-from-client-list" id="slt-client-from-client-list">{{ __('Select a client from client list') }}</label>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row bg-collapse">
                <form action="{{ route('createAccountInstructingInformation', $detailMatter['id']) }}" method="POST" id="add-new-client-form">
                    @csrf
                    <input hidden type="text" name="account-client-type" value="0">
                    <div class="col-xl-12 col-form-label text-md-left" id="show-hide-add-new-client">
                        <div class="row">
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('ACCOUNT CLIENT') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-client-name" class="col-form-label text-md-left title">{{ __('Account Client Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-client-name" type="text" class="form-control" name="account-client-name" required autocomplete="account-client-name" placeholder="Input client name" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="new-client-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="new-client-abn" type="text" class="form-control" name="new-client-abn" required autocomplete="new-client-abn" placeholder="48 123 123 124" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="billing-number" class="col-form-label text-md-left title">{{ __('Billing Number') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="billing-number" type="text" class="form-control" name="billing-number" required autocomplete="billing-number" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12"><hr></div>

                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('ACCOUNT CLIENT LOCATION') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="location-name" class="col-form-label text-md-left title">{{ __('Location Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="location-name" type="text" class="form-control" name="location-name" required autocomplete="location-name" placeholder="Input location name" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="new-location-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="new-location-abn" type="text" class="form-control" name="new-location-abn" required autocomplete="new-location-abn" placeholder="48 123 123 124" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="address-1" class="col-form-label text-md-left title">{{ __('Address 1') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="address-1" type="text" class="form-control" name="address-1" required autocomplete="address-1" placeholder="Input address 1" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="address-2" class="col-form-label text-md-left title">{{ __('Address 2') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="address-2" type="text" class="form-control" name="address-2" required autocomplete="address-2" placeholder="Input address 2" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="select-country" class="col-form-label text-md-left title">{{ __('Country') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="select-country" id="select-country">
                                            <option value="">Choose a country</option>
                                            @foreach($listCountries as $country)
                                                <option value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="select-state" class="col-form-label text-md-left title">{{ __('State') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="select-state" id="select-state">
                                            <option value="">Choose a state</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="select-city" class="col-form-label text-md-left title">{{ __('City') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="select-city" id="select-city">
                                            <option value="">Choose a city</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="new-location-postcode" class="col-form-label text-md-left title">{{ __('Postcode') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="new-location-postcode" type="text" class="form-control" name="new-location-postcode" required autocomplete="new-location-postcode" placeholder="Input postcode" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="primary-phone" class="col-form-label text-md-left title">{{ __('Primary Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="primary-phone" type="text" class="form-control" name="primary-phone" required autocomplete="primary-phone" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="secondary-phone" class="col-form-label text-md-left title">{{ __('Secondary phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="secondary-phone" type="text" class="form-control" name="secondary-phone" required autocomplete="secondary-phone" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="fax" type="text" class="form-control" name="fax" required autocomplete="fax" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12"><hr></div>

                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('ACCOUNT CONTACT (CLIENT LOCATION CONTACT)') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-name" class="col-form-label text-md-left title">{{ __('Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-contact-name" type="text" class="form-control" name="account-contact-name" required autocomplete="account-contact-name" placeholder="Input given name" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-job-title" class="col-form-label text-md-left title">{{ __('Job Title') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-contact-job-title" type="text" class="form-control" name="account-contact-job-title" required autocomplete="account-contact-job-title" placeholder="Input job title" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-email" class="col-form-label text-md-left title">{{ __('Email') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-contact-email" type="email" class="form-control" name="account-contact-email" required autocomplete="account-contact-email" placeholder="Input email" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-phone" class="col-form-label text-md-left title">{{ __('Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-contact-phone" type="text" class="form-control" name="account-contact-phone" required autocomplete="account-contact-phone" placeholder="Input phone number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-mobile" class="col-form-label text-md-left title">{{ __('Mobile') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-contact-mobile" type="text" class="form-control" name="account-contact-mobile" required autocomplete="account-contact-mobile" placeholder="Input mobile number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="account-contact-fax" type="text" class="form-control" name="account-contact-fax" required autocomplete="account-contact-fax" placeholder="Input fax number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="account-contact-note" class="col-form-label text-md-left title">{{ __('Note') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <textarea rows="3" style="width:100%;" class="form-control" name="account-contact-note"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-12 col-form-label text-md-right">
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-account-client">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-account-client">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="col-xl-12 col-form-label text-md-left" id="show-hide-select-a-client-from-list" hidden="hidden">
                    <div class="row">
                        <div class="col-xl-12 col-form-label text-md-left">
                            <h5>{{ __('ACCOUNT CLIENT') }}</h5>
                        </div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row pb-2">
                                <div class="col-xl-6 col-form-label text-md-left">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <label for="slt-account-client" class="col-form-label text-md-left title">{{ __('Account Client') }}</label>
                                        </div>
                                        <div class="col-xl-9">
                                            <select id="slt-account-client" name="slt-account-client" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Select Client" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-form-label text-md-left">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <label for="slt-billing-number" class="col-form-label text-md-left title">{{ __('Billing Number') }}</label>
                                        </div>
                                        <div class="col-xl-9">
                                            <input id="slt-billing-number" type="text" class="form-control" name="slt-billing-number" required autocomplete="slt-billing-number" placeholder="Input number" pattern=".*\S+.*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12"><hr></div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <h5>{{ __('ACCOUNT CLIENT LOCATION') }}</h5>
                        </div>

                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row pb-2">
                                <div class="col-xl-3">
                                    <label for="slt-location" class="col-form-label text-md-left title">{{ __('Location') }}</label>
                                </div>
                                <div class="col-xl-9">
                                    <select id="slt-location" name="slt-location" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Select Location" required>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12"><hr></div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <h5>{{ __('ACCOUNT CONTACT (CLIENT LOCATION CONTACT)') }}</h5>
                        </div>

                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row pb-2">
                                <div class="col-xl-3">
                                    <label for="slt-account-contact" class="col-form-label text-md-left title">{{ __('Account Contact') }}</label>
                                </div>
                                <div class="col-xl-9">
                                    <select id="slt-account-contact" name="slt-account-contact" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Select Contact" required>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-12 col-form-label text-md-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="slt-btn-cancel-save-account-client">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="slt-btn-save-account-client">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endif