<?php
    $currentUrl = url()->current();
    $routeName = '';
    if (strpos($currentUrl, 'statitic')) {
        $routeName = 'showStatiticLocation';
    } elseif (strpos($currentUrl, 'contact-list')) {
        $routeName = 'showContactListLocation';
    } elseif (strpos($currentUrl, 'agreements')) {
        $routeName = 'showAgreementLocation';
    } elseif (strpos($currentUrl, 'price-list')) {
        $routeName = 'showPriceListLocation';
    } elseif (strpos($currentUrl, 'edit-price-list')) {
        $routeName = 'showEditPriceListLocation';
    }
?>
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h3 class="mb-0">Location Info</h3>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 text-sm-right">
                @can('edit client and client locations information')
                    <a class="edit-location-info" id="edit-location-info" href="#" style="color:#0693B1;"><i>Edit</i></a>
                @endcan
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-md-left col-form-label">
                {{ $locationDetail['description'] }}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text-md-left col-form-label">
                <b>{{ __('Primary phone: ') }}</b>{{ $locationDetail['primary_phone'] }}<br>
                <b>{{ __('Secondary phone: ') }}</b>{{ $locationDetail['secondary_phone'] }}<br>
                <b>{{ __('Fax: ') }}</b>{{ $locationDetail['fax'] }}
            </div>
        </div>
    </div>
</div>

<!-- modal edit location info -->
<div class="modal fade" id="modal-edit-location" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('editLocationInfo', ['routeName' => $routeName, 'locationId' => $locationId]) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Edit Location Information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label for="primary_phone" class="col-md-12 col-lg-4 col-form-label text-md-left">{{ __('Primary Phone') }}</label>

                        <div class="col-md-12 col-lg-8">
                            <input id="primary_phone" type="text" class="form-control" name="primary_phone" value="{{ $locationDetail['primary_phone'] }}" required autocomplete="primary_phone" autofocus placeholder="Input primary phone" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="secondary_phone" class="col-md-12 col-lg-4 col-form-label text-md-left">{{ __('Secondary Phone') }}</label>

                        <div class="col-md-12 col-lg-8">
                            <input id="secondary_phone" type="text" class="form-control" name="secondary_phone" value="{{ $locationDetail['secondary_phone'] }}" required autocomplete="secondary_phone" placeholder="Input secondary phone" pattern="\d+" oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fax" class="col-md-12 col-lg-4 col-form-label text-md-left">{{ __('Fax') }}</label>

                        <div class="col-md-12 col-lg-8">
                            <input id="fax" type="text" class="form-control" name="fax" value="{{ $locationDetail['fax'] }}" required autocomplete="fax" placeholder="Input fax">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-md-12 col-lg-4 col-form-label text-md-left">{{ __('Description') }}</label>

                        <div class="col-md-12 col-lg-8">
                            <textarea rows="3" style="width:100%;" class="form-control" name="description">{{ $locationDetail['description'] }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="submit" class="btn btn-add-modal">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
