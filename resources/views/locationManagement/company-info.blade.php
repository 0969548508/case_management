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
                <h3 class="mb-0">Company Info</h3>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 text-sm-right">
                @can('edit client and client locations information')
                    <a id="edit-company-info" class="edit-company-info" href="#" style="color:#0693B1;"><i>Edit</i></a>
                @endcan
            </div>
        </div>
        <hr>
        <div class="row ml-0">
            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-left">
                <div class="row">
                    @if (!empty($clientDetail['image']))
                        <img src="{{ $clientDetail['image'] }}" width="53.33" height="53.33" style="border-radius: 10%;" class="mr-3" alt="avata {{ $clientDetail['name'] }}">
                    @else
                        <button class="mr-3 custom-img-company">{{ strtoupper(substr($clientDetail['name'], 0, 1)) }}</button>
                    @endif
                    <div class="col-lg-9 col-md-9 pt-2 pl-0">
                        <span style="color: #333333; font-weight: bold;">{{ ucwords($clientDetail['name']) }}</span>
                        <div class="position-user"><b>ABN: </b>{{ $clientDetail['abn'] }}</div>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal edit location info -->
<div class="modal fade" id="modal-edit-company-info" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('updateCompanyInfo',['routeName'=>$routeName ,'clientId'=>$clientDetail['id'], 'locationId'=>$locationId]) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Edit Company Information') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label for="company-name" class="col-md-12 col-lg-4 col-form-label text-md-left">{{ __('company Name') }}</label>

                        <div class="col-md-12 col-lg-8">
                            <input id="company-name" type="text" class="form-control" name="company-name" value="{{ $clientDetail['name'] }}" required autocomplete="company-name" autofocus placeholder="Input company name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="abn-company" class="col-md-12 col-lg-4 col-form-label text-md-left">{{ __('ABN') }}</label>

                        <div class="col-md-12 col-lg-8">
                            <input id="abn-company" type="text" class="form-control" name="abn-company" value="{{ $clientDetail['abn'] }}" required autocomplete="abn-company" placeholder="Input abn">
                        </div>
                    </div>
                </div>

                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="submit" class="btn btn-add-modal" id="btn-edit-company-info">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
