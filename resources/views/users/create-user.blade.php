@extends('layouts.app')

@section('content')
@include('navbar')
<div class="d-sm-flex align-items-center justify-content-between">
    <h1 class="title pb-0">Create User</h1>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h6 class="pb-0 custum-opacity">An email will be sent to the email that you provided below inviting them to set a password and logon.</h6>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('createUser') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-12 col-lg-2 col-form-label text-md-right mg-top-30">{{ __('Name') }}</label>

                        <div class="col-md-12 col-lg-3 mg-top-15">
                            <span class="custum-opacity">{{ __('Given name*') }}</span>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter Given Name" pattern=".*\S+.*">
                        </div>

                        <div class="col-md-12 col-lg-3 mg-top-15">
                            <span class="custum-opacity">{{ __('Middle name') }}</span>
                            <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}" autocomplete="middle_name" autofocus placeholder="Enter Middle Name">
                        </div>

                        <div class="col-md-12 col-lg-3 mg-top-15">
                            <span class="custum-opacity">{{ __('Family name*') }}</span>
                            <input id="family_name" type="text" class="form-control" name="family_name" value="{{ old('family_name') }}" required autocomplete="family_name" autofocus placeholder="Enter Family Name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-12 col-lg-2 col-form-label text-md-right">{{ __('Primary Email *') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="office" class="col-md-12 col-lg-2 col-form-label text-md-right">{{ __('Office*') }}</label>

                        <div class="col-md-12 col-lg-3 multi-select-office">
                            <select id="multi-select-office" name="select-office[]" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose Office" required multiple data-selected-text-format="count">
                                @foreach($listOffices as $office)
                                    <option value="{{ $office['id'] }}" data-name="{{ $office['name'] }}">{{ $office['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 col-lg-6 show-selected-office"></div>
                    </div>

                    <div class="form-group row">
                        <label for="matter" class="col-md-12 col-lg-2 col-form-label text-md-right">{{ __('Matter Expertise*') }}</label>

                        <div class="col-md-12 col-lg-3 multi-select-matter">
                            <select id="multi-select-matter" name="select-matter[]" class="selectpicker show-menu-arrow form-control" data-style="form-control" data-live-search="true" title="Choose Type-Subtype" required multiple data-selected-text-format="count">
                                @foreach ($listSpecificMatters as $type)
                                    <optgroup label="{{ $type->name }}">
                                        @if (count($type->children) > 0)
                                            @foreach ($type->children as $child)
                                                <option value="{{ $child->id }}" data-name="{{ $child->name }}" data-group="{{ $type->name }}">
                                                    {{ $child->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 col-lg-6 show-selected-matter">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-md-12 col-lg-2 col-form-label text-md-right">{{ __('Role*') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <select id="multi-select-roles" name="select-roles[]" class="selectpicker-role show-menu-arrow form-control" data-style="form-control" title="Choose Role" required multiple data-selected-text-format="count">
                                @foreach ($allRole as $role)
                                    <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 col-lg-6 show-selected-role"></div>
                    </div>

                    <div class="form-group row pt-5 pl-3 mb-0">
                        <div>
                            <button type="button" class="btn btn-primary m-auto custom-button-cancel" id="btn-cancel-save-user" onclick="goBack()">
                                {{ __('CANCEL') }}
                            </button>
                            <button type="submit" class="btn btn-primary m-auto custom-button" id="btn-save-user">
                                {{ __('SEND INVITATION') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $.fn.selectpicker.Constructor.BootstrapVersion = '4';

        $('.selectpicker').selectpicker({
            style: 'border-btn-select',
            liveSearchPlaceholder: 'Search',
            tickIcon: 'checkbox-select checkmark-select',
            size: 5
        });

        $('.selectpicker-role').selectpicker({
            style: 'border-btn-select',
            tickIcon: 'checkbox-select checkmark-select',
            size: 5
        });

        $('#multi-select-office').change(function() {
            this.arrayData = $(this).val();
            this.showSelected = '';
            var survey = this;
            $('#multi-select-office option').each(function(i){
                let val = $(this).attr('data-name');
                if ($(this).is(':selected')) {
                    survey.showSelected += ' <b><i>' + val + ',&nbsp;</i></b>';
                }
            });
            $('.show-selected-office').html('Selected offices: ' + survey.showSelected);
        });

        $('#multi-select-matter').on('change', function () {
            this.arrayData = $(this).val();
            this.showSelected = '';
            var survey = this;
            $('#multi-select-matter option').each(function(i){
                let val = $(this).attr('data-name');
                if ($(this).is(':selected')) {
                    let getClass = $(this).attr("data-group");
                    survey.showSelected += ' <b><i>' + getClass + '/' + val + ',&nbsp;</i></b>';
                }
            });
            $('.show-selected-matter').html('Selected matters: ' + survey.showSelected);
        });

        $('#multi-select-roles').change(function() {
            this.arrayData = $(this).val();
            this.showSelected = '';
            var survey = this;
            $('#multi-select-roles option').each(function(i){
                let val = $(this).attr('data-name');
                if ($(this).is(':selected')) {
                    survey.showSelected += ' <b><i>' + val + ',&nbsp;</i></b>';
                }
            });
            $('.show-selected-role').html('Selected roles: ' + survey.showSelected);
        });

        function goBack() {
            window.location.replace("{{ route('showListUser') }}");
        }
    </script>
@stop
