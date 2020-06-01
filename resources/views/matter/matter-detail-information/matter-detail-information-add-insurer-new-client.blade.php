@if (!empty($insurerInfo))
    <div class="col-xl-12" id="div-insurer-client-information">
        <div class="row">
            <div class="col-xl-12 mb-2">
                <div class="row m-0">
                    <p class="mb-0 custom-text-matter">Insurer</p>
                    @can('edit matters information')
                        <span class="cursor-pointer position-absolute-pen mr-0" data-toggle="collapse" data-target="#edit-insurer-client-information" id=""><img src="/images/btn_pen.png">&nbsp;<i>Edit insurer</i></span>
                    @endcan
                </div>
                <div class="row m-0">
                    <h5><b>{{ $insurerInfo['name'] }}</b></h5>
                </div>
                <div class="row m-0 mb-2">
                    <b>ABN: </b>&nbsp; {{ $insurerInfo['abn'] }}
                </div>
                <div class="row m-0">
                    <b>Insurance policy number: </b>&nbsp; {{ $insurerInfo['policy_number'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 collapse" id="edit-insurer-client-information">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="row ml-0 mb-1">
                    <b style="text-decoration: underline;"><i>Edit insurer client form</i></b>
                </div>
            </div>
        </div>

        <div class="row bg-collapse">
            <div class="col-xl-12 col-form-label text-md-left" id="show-hide-insurer-add-new-client">
                <form action="{{ route('editInsurerInformation', [ $insurerInfo['id'], $detailMatter['id'] ]) }}" method="POST" id="edit-insurer-add-new-client-form">
                @csrf
                    <div class="row">
                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label for="edit-insurer-client-name" class="col-form-label text-md-left title">{{ __('Insurer Client Name') }}</label>
                                </div>
                                <div class="col-xl-8">
                                    <input id="edit-insurer-client-name" type="text" class="form-control" name="edit-insurer-client-name" required autocomplete="edit-insurer-client-name" placeholder="Input client name" pattern=".*\S+.*" value="{{ $insurerInfo['name'] }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label for="edit-insurer-policy-number" class="col-form-label text-md-left title">{{ __('Insurance Policy Number') }}</label>
                                </div>
                                <div class="col-xl-8">
                                    <input id="edit-insurer-policy-number" type="text" class="form-control" name="edit-insurer-policy-number" required autocomplete="edit-insurer-policy-number" placeholder="Input number" pattern=".*\S+.*" value="{{ $insurerInfo['policy_number'] }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label for="edit-insurer-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                </div>
                                <div class="col-xl-8">
                                    <input id="edit-insurer-abn" type="text" class="form-control" name="edit-insurer-abn" required autocomplete="edit-insurer-abn" placeholder="Input number" pattern=".*\S+.*" value="{{ $insurerInfo['abn'] }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 col-form-label text-md-right">
                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="edit-btn-cancel-save-insurer-client">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="edit-btn-save-insurer-client">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
<div class="col-lg-12 col-sm-12 col-md-12 mb-0">
    <p class="mb-0 custom-text-matter">Insurer</p>
</div>

@can('edit matters information')
    <div class="col-lg-12 col-sm-12 col-md-12 mb-3">
        <a href="#" id="collapse-insurer" data-toggle="collapse" data-target="#add-insurer-client-information">
            <img src="/images/btn_plus.png">
            <i class="custom-font">Add information</i>
        </a>
    </div>

    <div class="col-xl-12 collapse" id="add-insurer-client-information">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="row ml-0">
                    <p class="pr-3 mb-0" hidden>
                        <input type="radio" name="new-insurer" id="select-insurer-new-client" checked="checked">
                        <label for="select-insurer-new-client" id="slt-insurer-new-client">{{ __('New client') }}</label>
                    </p>

                    <p class=" mb-0" hidden>
                        <input type="radio" name="new-insurer" id="select-insurer-client-from-client-list">
                        <label for="select-insurer-client-from-client-list" id="slt-insurer-client-from-client-list">{{ __('Select a client from client list') }}</label>
                    </p>
                </div>
            </div>
        </div>

        <div class="row bg-collapse">
            <div class="col-xl-12 col-form-label text-md-left" id="show-hide-insurer-add-new-client">
                <form action="{{ route('createInsurerInformation', $detailMatter['id']) }}" method="POST" id="insurer-add-new-client-form">
                @csrf
                    <div class="row">
                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label for="insurer-client-name" class="col-form-label text-md-left title">{{ __('Insurer Client Name') }}</label>
                                </div>
                                <div class="col-xl-8">
                                    <input id="insurer-client-name" type="text" class="form-control" name="insurer-client-name" required autocomplete="insurer-client-name" placeholder="Input client number" pattern=".*\S+.*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label for="insurer-policy-number" class="col-form-label text-md-left title">{{ __('Insurance Policy Number') }}</label>
                                </div>
                                <div class="col-xl-8">
                                    <input id="insurer-policy-number" type="text" class="form-control" name="insurer-policy-number" required autocomplete="insurer-policy-number" placeholder="Input number" pattern=".*\S+.*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-4">
                                    <label for="insurer-abn" class="col-form-label text-md-left title">{{ __('ABN') }}</label>
                                </div>
                                <div class="col-xl-8">
                                    <input id="insurer-abn" type="text" class="form-control" name="insurer-abn" required autocomplete="insurer-abn" placeholder="Input number" pattern=".*\S+.*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 col-form-label text-md-right">
                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-insurer-client">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-insurer-client">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-xl-12 col-form-label text-md-left" id="show-hide-insurer-slt-a-client" hidden>
                <div class="row">
                    <div class="col-xl-12 col-form-label text-md-left">
                        <div class="row">
                            <div class="col-xl-6 col-form-label text-md-left pb-0">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <label for="slt-insurer-client-name" class="col-form-label text-md-left title">{{ __('Insurer Client Name') }}</label>
                                    </div>
                                    <div class="col-xl-8">
                                        <select id="slt-insurer-client-name" name="slt-insurer-client-name" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose client name" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pb-2">
                            <div class="col-xl-6 col-form-label text-md-left">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <label for="slt-insurer-policy-number" class="col-form-label text-md-left title">{{ __('Insurance Policy Number') }}</label>
                                    </div>
                                    <div class="col-xl-8">
                                        <input id="slt-insurer-policy-number" type="text" class="form-control" name="slt-insurer-policy-number" required autocomplete="slt-insurer-policy-number" placeholder="Input number" pattern=".*\S+.*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-form-label text-md-left">
                            <div class="row">
                                <div class="col-xl-12 col-form-label text-md-right">
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="slt-btn-cancel-save-insurer-client">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="button" class="btn btn-primary m-auto collapse-btn-save pt-0" id="slt-btn-save-insurer-client">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
@endif