<?php
    $arrStatus = ["to-do"=>"to-do", "in-progress"=>"In-progress", "completed"=>"Completed", "cancelled"=>"Cancelled", "on-hold"=>"On-hold", "withdrawn"=>"Withdrawn", "invalid"=>"Invalid"];
?>
<div class="row">
    <div class="col-xl-12 mb-4">
        <div class="d-sm-flex align-items-center">
            <h1 class="title h1-location-name">{{ $detailMatter['case_number'] }}</h1>&nbsp;&nbsp;&nbsp;
            <span style="font-size:11px;cursor:pointer;" class="badge badge-pill background-status-{{ $detailMatter['last_state'] }} dropdown-toggle mb-2" data-toggle="dropdown">{{ $detailMatter['last_state'] }}</span> <br>

            <div class="dropdown-menu">
                <ul style="list-style: none;" class="pl-3">
                    @foreach ($arrStatus as $key => $status)
                        @if ($key == "completed")<li class="mt-3 mb-2"><i>&nbsp;&nbsp;&nbsp;&nbsp;Close Matter</i></li>@endif
                        <li>
                            @if ($detailMatter['last_state'] == $key)<i class="fa fa-check"></i>@else&nbsp;&nbsp;&nbsp;&nbsp;@endif
                            <span class="badge badge-pill background-status-{{ $key }} mb-2">{{ ucfirst($status) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="d-sm-flex align-items-center mb-1">
                    <b class="b-type">{{ __('Type: ') }}&nbsp;</b> {{ $detailMatter['type']['name'] }}
                    @can('edit matters information')
                        <img id="matter-type-icon" class="img-responsive content-name cursor-pointer ml-2" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
                    @endcan
                </div>
                <div class="d-sm-flex align-items-center">
                    <b class="b-office">{{ __('Client - Location: ') }}&nbsp;</b> {{ $detailMatter['clients']['name'] }} @if (!empty($detailMatter['locations']['name'])) - {{ $detailMatter['locations']['name'] }} @endif
                    @can('edit matters information')
                        <img id="matter-client-location-icon" class="img-responsive content-name cursor-pointer ml-2" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
                    @endcan
                </div>
            </div>

            <div class="col-xl-8">
                <div class="d-sm-flex align-items-center mb-1">
                    <b class="b-office">{{ __('Office: ') }}&nbsp;</b> {{ $detailMatter['office']['name'] }}
                    @can('edit matters information')
                        <img id="matter-office-icon" class="img-responsive content-name cursor-pointer ml-2" src="{{ asset('images/btn_pen.png') }}" alt="icon edit">
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-general-info" tabindex="-1" role="dialog" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="editTitle">{{ __('Edit Genaral Info') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('editGeneralInfo', $detailMatter['id']) }}" method="POST" id="edit-general-info-form">
                @csrf
                    <input hidden name="acctive-tab" value="{{ $activeTab }}">
                    <div class="form-group row mt-2">
                        <label for="edit-client" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Client*') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="edit-client" type="text" class="form-control" value="{{ $detailMatter['clients']['name'] }}" required autocomplete="edit-client" autofocus placeholder="Input name" pattern=".*\S+.*" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-location" class="col-md-12 col-lg-3 col-form-label">{{ __('Location*') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select id="slt-location" class="form-control" name="edit-location">
                                @foreach($listLocation as $location)
                                    @if(!empty($detailMatter['locations']) && $detailMatter['locations']['id'] == $location->id)
                                        <option selected value="{{ $location->id }}">{{ $location->name }}</option>
                                    @else
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-type" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Type*') }}</label>

                        <div class="col-md-12 col-lg-9">
                            @php
                                $listTypeId = array();

                            @endphp
                            <select id="slt-type" class="form-control" name="edit-type" disabled>
                                @foreach($listSpecificMatter as $specificMatter)
                                    @if(!empty($specificMatter['parent']))
                                        @if($detailMatter['type_id'] == $specificMatter['id'])
                                            <option selected value="{{ $specificMatter['id'] }}">{{ $specificMatter['name'] }} ({{ $specificMatter['parent'][0]['name'] }})</option>
                                        @else
                                            <option value="{{ $specificMatter['id'] }}">{{ $specificMatter['name'] }} ({{ $specificMatter['parent'][0]['name'] }})</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="edit-office" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Office*') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <select id="slt-office" class="form-control" name="edit-office" disabled>
                                @foreach($listOffices as $offices)
                                    @if($detailMatter['office_id'] == $offices['id'])
                                        <option selected value="{{ $offices['id'] }}">{{ $offices['name'] }}</option>
                                    @else
                                        <option value="{{ $offices['id'] }}">{{ $offices['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer edit-add-info-footer">
                <div class="form-group">
                    <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                    <button type="button" class="btn btn-add-modal" id="btn-edit-general-info-modal">{{ __('SAVE') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-sm-12 col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'information' ? 'active custom-text-matter':'custom-text-matter'}}" href="{{ route('getMatterDetail', $detailMatter['id']) }}">Information</a>
            </li>
            @can('view notations')
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'notations' ? 'active custom-text-matter':'custom-text-matter'}}" href="{{ route('getListNotations', $detailMatter['id']) }}">Notations</a>
                </li>
            @endcan
            @can('view files and folders')
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'files' ? 'active custom-text-matter':'custom-text-matter'}}" href="{{ route('getListFiles', $detailMatter['id']) }}">Files</a>
                </li>
            @endcan
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'report' ? 'active custom-text-matter':'custom-text-matter'}}" href="#">Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'expenses' ? 'active custom-text-matter':'custom-text-matter'}}" href="#">Expenses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'billing' ? 'active custom-text-matter':'custom-text-matter'}}" href="#">Invoice & Billing</a>
            </li>
        </ul>
    </div>
</div>