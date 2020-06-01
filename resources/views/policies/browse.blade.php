@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="title mb-0">Password policy</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="@if (!isset($dataSettings['id'])) {{ route('savePolicies') }} @else {{ route('updatePolicies', $dataSettings['id']) }} @endif">
                    {{ csrf_field() }}

                    <div class="form-group row mb-4">
                        <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Password Minimum Length:') }}</label>
                        <div class="col-8 col-xl-2">
                            <input id="pass-length" type="phone" class="form-control" name="pass_length" value="@if (isset($dataSettings['id'])){{ $dataSettings['pass_length'] }}@endif" required numeric oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')" onchange="this.value = this.value.trim()" maxlength="3" placeholder="Enter Minimum Length">
                        </div>
                        <span class="col-4 col-xl-2" style="padding-top: 10px">character(s)</span>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-md-12 col-xl-2 col-form-label text-md-left pt-0">{{ __('Password Complexity:') }}</label>

                        <div class="col-md-12 col-xl-4">
                            <label class="col-md-12 mb-2">
                                <input id="special-character" class="mr-2" type="checkbox" name="special_character" @if (isset($dataSettings['id']) && $dataSettings['special_character']) checked @endif><span class="checkmark"></span> <span>Special Character</span>
                            </label>
                            <label class="col-md-12 mb-2">
                                <input id="capital-letter" class="mr-2" type="checkbox" name="capital_letter" @if (isset($dataSettings['id']) && $dataSettings['capital_letter']) checked @endif><span class="checkmark"></span> <span>Capital Letter</span>
                            </label>
                            <label class="col-md-12">
                                <input id="number" class="mr-2" type="checkbox" name="number" @if (isset($dataSettings['id']) && $dataSettings['number']) checked @endif><span class="checkmark"></span> <span>Number</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Password Change Period:') }}</label>

                        <div class="col-8 col-xl-2">
                            <input id="pass-period" type="phone" class="form-control" name="pass_period" value="@if (isset($dataSettings['id'])){{ $dataSettings['pass_period'] }}@endif" required numeric oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')" onchange="this.value = this.value.trim()" maxlength="10" placeholder="Enter Password Change Period">
                        </div>
                        <span class="col-3 col-xl-2" style="padding-top: 10px">day(s)</span>
                    </div>

                    <div class="form-group row mb-5">
                        <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Password History:') }}</label>

                        <div class="col-8 col-xl-2">
                            <input id="password-history" type="phone" class="form-control" name="password_history" value="@if (isset($dataSettings['id'])){{ $dataSettings['password_history'] }}@endif" required numeric oninput="this.value = this.value.replace(/[^0-9]/g, '').split(/\./).slice(0, 2).join('.')" onchange="this.value = this.value.trim()" maxlength="2" placeholder="Enter Password History">
                        </div>
                        <span class="col-3 col-xl-2" style="padding-top: 10px">time(s)</span>
                    </div>

                    <div class="col-md-12 form-group row mb-0">
                        <div class="col-md-12 mt-md-4 p-0">
                            <button type="button" class="btn btn-primary custom-button-cancel mr-2" onclick="window.location.reload();">
                                {{ __('CANCEL') }}
                            </button>
                            <button type="submit" class="btn btn-primary custom-button">
                                {{ __('SAVE') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
