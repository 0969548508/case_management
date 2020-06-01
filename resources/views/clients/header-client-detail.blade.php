@include('navbar')
<div class="row w-100">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
        <div class="row ml-0">
            <form id="upload-image-form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="imgUp">
                    <div class="avataPreview member-client-detail text-center float-left">
                        <span class="member-initials-client-detail">{{ substr($clientDetail['name'], 0, 1) }}</span>
                        @can('edit client and client locations information')
                            <label class="btn btn-upload-avata-client">
                                <span id="title-upload">
                                    Edit
                                </span>
                                <input id="upload-image" type="file" class="uploadFile img w-0 h-0 overflow-hidden" name="image">
                            </label>
                        @endcan
                    </div>
                </div>
            </form>
            <div class="col-sm-8 col-xl-10 m-auto ml-sm-3 p-0">
                <h1 class="content-name-client title-name-client">
                    <span id="content-name-client" class="mr-2">{{ ucwords($clientDetail['name']) }}</span>
                    @can('edit client and client locations information')
                        <img id="content-name-icon" class="img-responsive content-name cursor-pointer mr-2" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
                    @endcan
                    <div class="{{ ($clientDetail['status'] == 0) ? 'inactive-client' : 'active-client'}} status-client">{{ ($clientDetail['status'] == 0) ? 'Inactive' : 'Active' }}</div>
                </h1>
                <div id="form-edit-name-client" class="form-group row m-0">
                    <label class="col-form-label p-0 mr-5">Company Name *</label>
                    <div class="col-md-5 col-xl-3 p-0 mr-5">
                        <input id="client-name" type="text" class="form-control" name="name" value="{{ $clientDetail['name'] }}" required autocomplete="name" placeholder="Input company name" pattern=".*\S+.*">
                    </div>
                    <div class="col-xl-6 p-0">
                        <button id="cancel-edit-name-client" type="button" class="btn btn-primary custom-button-cancel mr-2 mt-2 mt-xl-0">
                            {{ __('CANCEL') }}
                        </button>
                        <button id="edit-name-client" type="submit" class="btn btn-primary custom-button mt-2 mt-xl-0">
                            {{ __('SAVE') }}
                        </button>
                    </div>
                </div>
                <h1 class="content-abn-client title-abn-client mb-0">
                    Company ABN
                    <span id="content-abn-client" class="font-weight-normal">{{ ucwords($clientDetail['abn']) }}</span>
                    @can('edit client and client locations information')
                        <img id="content-abn-icon" class="img-responsive content-abn cursor-pointer ml-2" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
                    @endcan
                </h1>
                <div id="form-edit-abn-client" class="form-group row m-0">
                    <label class="col-form-label p-0 mr-5">Company ABN *</label>
                    <div class="col-md-5 col-xl-3 p-0 mr-5">
                        <input id="client-abn" type="phone" class="form-control" name="abn" value="{{ $clientDetail['abn'] }}" required autocomplete="abn" placeholder="Input company ABN" pattern=".*\S+.*">
                    </div>
                    <div class="col-xl-6 p-0">
                        <button id="cancel-edit-abn-client" type="button" class="btn btn-primary custom-button-cancel mr-2 mt-2 mt-xl-0">
                            {{ __('CANCEL') }}
                        </button>
                        <button id="edit-abn-client" type="submit" class="btn btn-primary custom-button mt-2 mt-xl-0">
                            {{ __('SAVE') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- custom modal -->
<div class="modal fade" id="modalDeleteClient" tabindex="-1" role="dialog" aria-labelledby="deleteTitleClient" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitleClient">{{ __('Delete Client') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this client?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <form action="{{ route('deleteClient', $clientDetail['id']) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-delete-modal">{{ __('DELETE') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>