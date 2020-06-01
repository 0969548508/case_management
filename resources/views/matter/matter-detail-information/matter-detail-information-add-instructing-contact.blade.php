@if (!empty($instructingClientInfo))
    <div class="col-xl-12" id="div-instructing-client-information">
        <div class="row">
            <div class="col-xl-12 mb-4">
                <div class="row m-0">
                    <p class="mb-0 custom-text-matter">Instructing Client</p>
                    @can('edit matters information')
                        <span class="cursor-pointer position-absolute-pen mr-0" data-toggle="collapse" data-target="#edit-instructing-client" id=""><img src="/images/btn_pen.png">&nbsp;<i>Edit instructing client</i></span>
                    @endcan
                </div>
                <div class="row m-0">
                    <h5><b>{{ $instructingClientInfo['name'] }}</b></h5>
                </div>
                <div class="row m-0 mb-2">
                    <b>ABN: </b>&nbsp; {{ $instructingClientInfo['abn'] }}
                </div>
                <div class="row m-0">
                    <b>Billing number: </b>&nbsp; {{ $instructingClientInfo['billing_number'] }}
                </div>
            </div>
            
            <div class="col-xl-12 m-0">
                <div class="row">
                    @if (!empty($instructingClientLocationInfo))
                        <div class="col-xl-6">
                            <p class="mb-0 custom-text-matter">Instructing Client Location</p>
                            <div class="row m-0">
                                <h5><b>{{ $instructingClientLocationInfo['location_name'] }}</b></h5>
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Location ABN: </b>&nbsp; {{ $instructingClientLocationInfo['abn'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <p class="mb-0 m-0">{{ $instructingClientLocationInfo['address_1'] }}</p>
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Primary phone: </b>&nbsp; {{ $instructingClientLocationInfo['primary_phone'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Secondary phone: </b>&nbsp; {{ $instructingClientLocationInfo['secondary_phone'] }}
                            </div>

                            <div class="row m-0">
                                <b>Fax: </b>&nbsp; {{ $instructingClientLocationInfo['fax'] }}
                            </div>
                        </div>
                    @endif

                    @if (!empty($instructingContactInfo))
                        <div class="col-xl-6">
                            <p class="mb-0 custom-text-matter">Instructing Contact</p>
                            <div class="row m-0">
                                <h5><b>{{ $instructingContactInfo['name'] }}</b></h5>
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Job title: </b>&nbsp; {{ $instructingContactInfo['job_title'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Email: </b>&nbsp; {{ $instructingContactInfo['email'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Primary phone: </b>&nbsp; {{ $instructingContactInfo['phone'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Secondary phone: </b>&nbsp; {{ $instructingContactInfo['mobile'] }}
                            </div>

                            <div class="row m-0 mb-2">
                                <b>Fax: </b>&nbsp; {{ $instructingContactInfo['fax'] }}
                            </div>

                            <div class="row m-0">
                                <b>Note: </b>&nbsp; {{ $instructingContactInfo['note'] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 collapse" id="edit-instructing-client">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="row ml-0 mb-1">
                    <b style="text-decoration: underline;"><i>Edit instructing client form</i></b>
                </div>
            </div>
        </div>

        <div class="row bg-collapse">
            <form action="{{ route('editAccountInstructingInformation', [ $instructingClientInfo['id'], $detailMatter['id']]) }}" method="POST" id="edit-new-instructing-client-form">
            @csrf
                <input hidden type="text" name="account-client-type" value="1">
                <div class="col-xl-12 col-form-label text-md-left" id="edit-instructing-show-hide-add-new-client">
                    <div class="row">
                        @if (!empty($instructingClientInfo))
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('INSTRUCTING CLIENT') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-client-name" class="col-form-label text-md-left title">{{ __('Instructing Client Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-client-name" type="text" class="form-control" name="edit-instructing-account-client-name" required autocomplete="edit-instructing-account-client-name" placeholder="Input client name" pattern=".*\S+.*" value="{{ $instructingClientInfo['name'] }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-new-client-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-new-client-abn" type="text" class="form-control" name="edit-instructing-new-client-abn" required autocomplete="edit-instructing-new-client-abn" placeholder="48 123 123 124" pattern=".*\S+.*" value="{{ $instructingClientInfo['abn'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-billing-number" class="col-form-label text-md-left title">{{ __('Instructing Reference Number') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-billing-number" type="text" class="form-control" name="edit-instructing-billing-number" required autocomplete="edit-instructing-billing-number" placeholder="Input number" pattern=".*\S+.*" value="{{ $instructingClientInfo['billing_number'] }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12"><hr></div>

                        @if (!empty($instructingClientLocationInfo))

                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('INSTRUCTING CLIENT LOCATION') }}</h5>
                            </div>

                            <input hidden name="account-client-location-id" value="{{ $instructingClientLocationInfo['id'] }}">

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-location-name" class="col-form-label text-md-left title">{{ __('Location Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-location-name" type="text" class="form-control" name="edit-instructing-location-name" required autocomplete="edit-instructing-location-name" placeholder="Input location name" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['location_name'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-new-location-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-new-location-abn" type="text" class="form-control" name="edit-instructing-new-location-abn" required autocomplete="edit-instructing-new-location-abn" placeholder="48 123 123 124" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['abn'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-address-1" class="col-form-label text-md-left title">{{ __('Address 1') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-address-1" type="text" class="form-control" name="edit-instructing-address-1" required autocomplete="edit-instructing-address-1" placeholder="Input address 1" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['address_1'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-address-2" class="col-form-label text-md-left title">{{ __('Address 2') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-address-2" type="text" class="form-control" name="edit-instructing-address-2" required autocomplete="edit-instructing-address-2" placeholder="Input address 2" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['address_2'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-select-country" class="col-form-label text-md-left title">{{ __('Country') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select id="edit-instructing-select-country-{{ $instructingClientLocationInfo['id'] }}" name="edit-instructing-select-country" class="form-control" onchange="checkCountryInstructingClientLocation('{{ $instructingClientLocationInfo['id'] }}')">
                                            @foreach($listCountries as $country)
                                                @if ($country->name == $instructingClientLocationInfo['country'])
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
                                        <label for="edit-instructing-select-state" class="col-form-label text-md-left title">{{ __('State') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="edit-instructing-select-state" id="edit-instructing-select-state-{{ $instructingClientLocationInfo['id'] }}" onchange="checkStateInstructingClientLocation('{{ $instructingClientLocationInfo['id'] }}')">
                                            @if(!empty($instructingClientLocationInfo['state']))
                                                <option value="{{ $instructingClientLocationInfo['state'] }}">{{ $instructingClientLocationInfo['state'] }}</option>
                                            @else
                                                <option value="">Choose a state</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-select-city" class="col-form-label text-md-left title">{{ __('City') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="edit-instructing-select-city" id="edit-instructing-select-city-{{ $instructingClientLocationInfo['id'] }}">
                                            @if(!empty($instructingClientLocationInfo['city']))
                                                <option value="{{ $instructingClientLocationInfo['city'] }}">{{ $instructingClientLocationInfo['city'] }}</option>
                                            @else
                                                <option value="">Choose a city</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-postcode" class="col-form-label text-md-left title">{{ __('Postcode') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-postcode" type="text" class="form-control" name="edit-instructing-postcode" required autocomplete="edit-instructing-postcode" placeholder="Input postcode" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['postcode'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-primary-phone" class="col-form-label text-md-left title">{{ __('Primary Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-primary-phone" type="text" class="form-control" name="edit-instructing-primary-phone" required autocomplete="edit-instructing-primary-phone" placeholder="Input number" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['primary_phone'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-secondary-phone" class="col-form-label text-md-left title">{{ __('Secondary phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-secondary-phone" type="text" class="form-control" name="edit-instructing-secondary-phone" required autocomplete="edit-instructing-secondary-phone" placeholder="Input number" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['secondary_phone'] }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-fax" type="text" class="form-control" name="edit-instructing-fax" required autocomplete="edit-instructing-fax" placeholder="Input number" pattern=".*\S+.*" value="{{ $instructingClientLocationInfo['fax'] }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12"><hr></div>

                        @if (!empty($instructingContactInfo))
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('INSTRUCTING CONTACT (CLIENT LOCATION CONTACT)') }}</h5>
                            </div>

                            <input hidden name="account-client-contact-id" value="{{ $instructingContactInfo['id'] }}">

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-name" class="col-form-label text-md-left title">{{ __('Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-contact-name" type="text" class="form-control" name="edit-instructing-account-contact-name" required autocomplete="edit-instructing-account-contact-name" placeholder="Input given name" pattern=".*\S+.*" value="{{ $instructingContactInfo['name'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-job-title" class="col-form-label text-md-left title">{{ __('Job Title') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-contact-job-title" type="text" class="form-control" name="edit-instructing-account-contact-job-title" required autocomplete="edit-instructing-account-contact-job-title" placeholder="Input job title" pattern=".*\S+.*" value="{{ $instructingContactInfo['job_title'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-email" class="col-form-label text-md-left title">{{ __('Email') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-contact-email" type="email" class="form-control" name="edit-instructing-account-contact-email" required autocomplete="edit-instructing-account-contact-email" placeholder="Input email" pattern=".*\S+.*" value="{{ $instructingContactInfo['email'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-phone" class="col-form-label text-md-left title">{{ __('Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-contact-phone" type="text" class="form-control" name="edit-instructing-account-contact-phone" required autocomplete="edit-instructing-account-contact-phone" placeholder="Input phone number" pattern=".*\S+.*" value="{{ $instructingContactInfo['phone'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-mobile" class="col-form-label text-md-left title">{{ __('Mobile') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-contact-mobile" type="text" class="form-control" name="edit-instructing-account-contact-mobile" required autocomplete="edit-instructing-account-contact-mobile" placeholder="Input mobile number" pattern=".*\S+.*" value="{{ $instructingContactInfo['mobile'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="edit-instructing-account-contact-fax" type="text" class="form-control" name="edit-instructing-account-contact-fax" required autocomplete="edit-instructing-account-contact-fax" placeholder="Input fax number" pattern=".*\S+.*" value="{{ $instructingContactInfo['fax'] }}">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="edit-instructing-account-contact-note" class="col-form-label text-md-left title">{{ __('Note') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <textarea rows="3" style="width:100%;" class="form-control" name="edit-instructing-account-contact-note">{{ $instructingContactInfo['note'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-12 col-form-label text-md-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="edit-instructing-btn-cancel-save-account-client">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="edit-instructing-btn-save-account-client">
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
        <p class="mb-0 custom-text-matter">Instructing Client</p>
    </div>

    @can('edit matters information')
        <div class="col-lg-12 col-sm-12 col-md-12 mb-3">
            <a href="#" id="collapse-add-instructing-client" data-toggle="collapse" data-target="#add-instructing-client">
                <img src="/images/btn_plus.png">
                <i class="custom-font">Add information</i>
            </a>
        </div>

        <div class="col-xl-12 collapse" id="add-instructing-client">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="row ml-0">
                        <p class="pr-3 mb-0" hidden>
                            <input type="radio" name="instructing-new-client" id="instructing-select-new-client" checked="checked">
                            <label for="instructing-select-new-client" id="instructing-slt-new-client">{{ __('New client') }}</label>
                        </p>

                        <p class=" mb-0" hidden>
                            <input type="radio" name="instructing-new-client" id="instructing-select-client-from-client-list">
                            <label for="instructing-select-client-from-client-list" id="instructing-slt-client-from-client-list">{{ __('Select a client from client list') }}</label>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row bg-collapse">
                <form action="{{ route('createAccountInstructingInformation', $detailMatter['id']) }}" method="POST" id="add-new-instructing-client-form">
                @csrf
                    <input hidden type="text" name="account-client-type" value="1">
                    <div class="col-xl-12 col-form-label text-md-left" id="instructing-show-hide-add-new-client">
                        <div class="row">
                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('INSTRUCTING CLIENT') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-client-name" class="col-form-label text-md-left title">{{ __('Instructing Client Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-client-name" type="text" class="form-control" name="instructing-account-client-name" required autocomplete="instructing-account-client-name" placeholder="Input client name" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="instructing-new-client-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-new-client-abn" type="text" class="form-control" name="instructing-new-client-abn" required autocomplete="instructing-new-client-abn" placeholder="48 123 123 124" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="instructing-billing-number" class="col-form-label text-md-left title">{{ __('Instructing Reference Number') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-billing-number" type="text" class="form-control" name="instructing-billing-number" required autocomplete="instructing-billing-number" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12"><hr></div>

                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('INSTRUCTING CLIENT LOCATION') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-location-name" class="col-form-label text-md-left title">{{ __('Location Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-location-name" type="text" class="form-control" name="instructing-location-name" required autocomplete="instructing-location-name" placeholder="Input location name" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-new-location-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-new-location-abn" type="text" class="form-control" name="instructing-new-location-abn" required autocomplete="instructing-new-location-abn" placeholder="48 123 123 124" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-address-1" class="col-form-label text-md-left title">{{ __('Address 1') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-address-1" type="text" class="form-control" name="instructing-address-1" required autocomplete="instructing-address-1" placeholder="Input address 1" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-address-2" class="col-form-label text-md-left title">{{ __('Address 2') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-address-2" type="text" class="form-control" name="instructing-address-2" required autocomplete="instructing-address-2" placeholder="Input address 2" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-select-country" class="col-form-label text-md-left title">{{ __('Country') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select id="instructing-select-country" name="instructing-select-country" class="form-control">
                                            <option value="">Choose a country</option>
                                            @foreach($listCountries as $country)
                                                <option value="{{ $country->name }}" data-value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-select-state" class="col-form-label text-md-left title">{{ __('State') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="instructing-select-state" id="instructing-select-state">
                                            <option value="">Choose a state</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-select-city" class="col-form-label text-md-left title">{{ __('City') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select class="form-control" name="instructing-select-city" id="instructing-select-city">
                                            <option value="">Choose a city</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="instructing-postcode" class="col-form-label text-md-left title">{{ __('Postcode') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-postcode" type="text" class="form-control" name="instructing-postcode" required autocomplete="instructing-postcode" placeholder="Input postcode" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-primary-phone" class="col-form-label text-md-left title">{{ __('Primary Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-primary-phone" type="text" class="form-control" name="instructing-primary-phone" required autocomplete="instructing-primary-phone" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-secondary-phone" class="col-form-label text-md-left title">{{ __('Secondary phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-secondary-phone" type="text" class="form-control" name="instructing-secondary-phone" required autocomplete="instructing-secondary-phone" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-3">
                                        <label for="instructing-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-fax" type="text" class="form-control" name="instructing-fax" required autocomplete="instructing-fax" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12"><hr></div>

                            <div class="col-xl-12 col-form-label text-md-left">
                                <h5>{{ __('INSTRUCTING CONTACT (CLIENT LOCATION CONTACT)') }}</h5>
                            </div>

                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-name" class="col-form-label text-md-left title">{{ __('Name') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-contact-name" type="text" class="form-control" name="instructing-account-contact-name" required autocomplete="instructing-account-contact-name" placeholder="Input given name" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-job-title" class="col-form-label text-md-left title">{{ __('Job Title') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-contact-job-title" type="text" class="form-control" name="instructing-account-contact-job-title" required autocomplete="instructing-account-contact-job-title" placeholder="Input job title" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-email" class="col-form-label text-md-left title">{{ __('Email') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-contact-email" type="email" class="form-control" name="instructing-account-contact-email" required autocomplete="instructing-account-contact-email" placeholder="Input email" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-phone" class="col-form-label text-md-left title">{{ __('Phone') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-contact-phone" type="text" class="form-control" name="instructing-account-contact-phone" required autocomplete="instructing-account-contact-phone" placeholder="Input phone number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-mobile" class="col-form-label text-md-left title">{{ __('Mobile') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-contact-mobile" type="text" class="form-control" name="instructing-account-contact-mobile" required autocomplete="instructing-account-contact-mobile" placeholder="Input mobile number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-fax" class="col-form-label text-md-left title">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <input id="instructing-account-contact-fax" type="text" class="form-control" name="instructing-account-contact-fax" required autocomplete="instructing-account-contact-fax" placeholder="Input fax number" pattern=".*\S+.*">
                                    </div>
                                </div>

                                <div class="row pb-2">
                                    <div class="col-xl-3">
                                        <label for="instructing-account-contact-note" class="col-form-label text-md-left title">{{ __('Note') }}</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <textarea rows="3" style="width:100%;" class="form-control" name="instructing-account-contact-note"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-12 col-form-label text-md-right">
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="instructing-btn-cancel-save-account-client">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="instructing-btn-save-account-client">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="col-xl-12 col-form-label text-md-left" id="instructing-show-hide-select-a-client-from-list" hidden="hidden">
                    <div class="row">
                        <div class="col-xl-12 col-form-label text-md-left">
                            <h5>{{ __('INSTRUCTING CLIENT') }}</h5>
                        </div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row pb-2">
                                <div class="col-xl-6 col-form-label text-md-left">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <label for="instructing-slt-account-client" class="col-form-label text-md-left title">{{ __('Instructing Client') }}</label>
                                        </div>
                                        <div class="col-xl-9">
                                            <select id="instructing-slt-account-client" name="instructing-slt-account-client" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Select Client" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-form-label text-md-left">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <label for="instructing-slt-billing-number" class="col-form-label text-md-left title">{{ __('Instructing Reference Number') }}</label>
                                        </div>
                                        <div class="col-xl-9">
                                            <input id="instructing-slt-billing-number" type="text" class="form-control" name="instructing-slt-billing-number" required autocomplete="instructing-slt-billing-number" placeholder="Input number" pattern=".*\S+.*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12"><hr></div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <h5>{{ __('INSTRUCTING CLIENT LOCATION') }}</h5>
                        </div>

                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row pb-2">
                                <div class="col-xl-3">
                                    <label for="instructing-slt-location" class="col-form-label text-md-left title">{{ __('Location') }}</label>
                                </div>
                                <div class="col-xl-9">
                                    <select id="instructing-slt-location" name="instructing-slt-location" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Select Location" required>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12"><hr></div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <h5>{{ __('INSTRUCTING CLIENT CONTACT (CLIENT LOCATION CONTACT)') }}</h5>
                        </div>

                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row pb-2">
                                <div class="col-xl-3">
                                    <label for="instructing-slt-account-contact" class="col-form-label text-md-left title">{{ __('Instructing Contact') }}</label>
                                </div>
                                <div class="col-xl-9">
                                    <select id="instructing-slt-account-contact" name="instructing-slt-account-contact" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Select Contact" required>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-12 col-form-label text-md-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="instructing-slt-btn-cancel-save-account-client">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="instructing-slt-btn-save-account-client">
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