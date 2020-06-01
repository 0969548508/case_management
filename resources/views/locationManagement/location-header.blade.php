<div class="row">
    <div class="col-xl-12 mb-4">
        <div class="d-sm-flex align-items-center">
            <h1 class="title h1-location-name">{{ $locationDetail['name'] }}</h1>
            @can('edit client and client locations information')
                <img id="content-name-icon" class="img-responsive content-name cursor-pointer ml-2 pr-3" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
            @endcan

            <!-- input edit location name -->
            <div id="form-edit-name-location" class="form-group row m-0 mb-3">
                <div class="col-xl-6 p-0">
                    <input id="location-name" type="text" class="form-control" name="name" value="{{ $locationDetail['name'] }}" required autocomplete="name" placeholder="Input company name" pattern=".*\S+.*">
                </div>
                <div class="col-xl-6 col-form-label">
                    <button id="cancel-edit-name-location" type="button" class="btn btn-primary collapse-btn-cancel m-auto pt-0 pl-2">
                        {{ __('CANCEL') }}
                    </button>
                    <button id="edit-name-location" type="button" class="btn btn-primary collapse-btn-save m-auto pt-0">
                        {{ __('SAVE') }}
                    </button>
                </div>
            </div>
            <!-- end input edit location name -->

            @if ($locationDetail['is_primary'])
                <div class="custom-status-primary text-md-left"><b>Primary</b></div>&nbsp;
            @endif

            <div class="text-md-left {{ ($locationDetail['status'] == 0) ? 'custom-inactive' : 'custom-active'}} div-status-location">{{ ($locationDetail['status'] == 0) ? 'Inactive' : 'Active' }}</div>
        </div>

        <div class="d-sm-flex align-items-center">
            <b class="b-abn">{{ __('ABN: ') }}&nbsp;{{ $locationDetail['abn'] }}</b>
            @can('edit client and client locations information')
                <img id="content-abn-icon" class="img-responsive content-name cursor-pointer ml-2" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
            @endcan

            <!-- input edit location abn -->
            <div id="form-edit-abn-location" class="form-group row m-0">
                <div class="col-xl-6 p-0">
                    <input id="abn-name" type="text" class="form-control" name="name" value="{{ $locationDetail['abn'] }}" required autocomplete="name" placeholder="Input company name" pattern=".*\S+.*">
                </div>
                <div class="col-xl-6 col-form-label">
                    <button id="cancel-abn-location" type="button" class="btn btn-primary collapse-btn-cancel m-auto pt-0 pl-2">
                        {{ __('CANCEL') }}
                    </button>
                    <button id="edit-abn-location" type="button" class="btn btn-primary collapse-btn-save m-auto pt-0">
                        {{ __('SAVE') }}
                    </button>
                </div>
            </div>
            <!-- end input edit location abn -->
        </div>
    </div>
</div>

<!-- modal delete location -->
<div class="modal fade" id="modal-move-location-to-trash" tabindex="-1" role="dialog" aria-labelledby="moveLocationTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moveLocationTitle">{{ __('Delete location') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this location?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('NO') }}</button>
                <form action="{{ route('moveLocationToTrash', ['clientId'=>$clientDetail['id'], 'locationId'=>$locationDetail['id']]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-delete-modal" id="btn-move-location-to-trash">{{ __('YES') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>