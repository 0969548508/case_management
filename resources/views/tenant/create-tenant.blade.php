@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-37">
    <h1 class="title mb-0">Create Tenant</h1>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('createTenant') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="sub-domain-name" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Domain name') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <input id="sub-domain-name" type="text" class="form-control" name="sub-domain-name" value="{{ old('sub-domain-name') }}" required autocomplete="sub-domain-name" autofocus placeholder="Enter sub domain name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="manager" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Manager') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <input id="manager" type="text" class="form-control" name="manager" value="{{ old('manager') }}" required autocomplete="manager" autofocus placeholder="Enter manager">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Email') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter email">
                        </div>
                    </div>

                    <div class="form-group row pt-5 pl-3 mb-0">
                        <div>
                            <button type="button" class="btn btn-primary m-auto custom-button-cancel" id="btn-cancel-save-domain">
                                {{ __('CANCEL') }}
                            </button>
                            <button type="submit" class="btn btn-primary m-auto custom-button" id="btn-save-domain">
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

