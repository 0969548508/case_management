@extends('layouts.app')

@section('content')
@include('navbar')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
        <div class="row ml-0">
            <img src="{{ asset('/images/background_login.png') }}" alt="Generic placeholder image" width="40" height="40" style="border-radius: 50%;margin-top:10px;">
            <div class="col-lg-9 col-md-9">
                <div class="d-sm-flex align-items-center">
                    <h1 class="title pr-3">{{ ucfirst($clientDetail['name']) }}</h1>
                </div>

                <div class="d-sm-flex align-items-center">
                    <b>{{ __('ABN Company* ') }}</b>&nbsp;{{ $clientDetail['abn'] }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" id="create-new-location-form" action="{{ route('createNewLocation', $clientDetail['id']) }}">
                @csrf
                    <div class="form-group row">
                        <label for="location-name" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Location Name*') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Input location name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="abn" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('ABN Company*') }}</label>

                        <div class="col-md-12 col-lg-3">
                            <input id="abn" type="text" class="form-control" name="abn" value="{{ old('abn') }}" required autocomplete="abn" autofocus placeholder="48 123 123 124" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-left">
                            <label class="col-lg-12">
                                <input id="is_primary" class="role-checkbox" type="checkbox" name="is_primary"><span class="checkmark"></span> <span>Set as primary</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row ml-0">
                        <button type="button" class="btn btn-primary custom-button-cancel" id="btn-cancel-save-location" onclick="goBack()">
                            {{ __('CANCEL') }}
                        </button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary custom-button" id="btn-save-info-location" width="auto">
                            {{ __('SAVE & ADD MORE INFO') }}
                        </button>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary custom-button" id="btn-finish">
                            {{ __('FINISH') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script type="text/javascript">
        $('#btn-save-info-location').click(function (e) {
            $('#create-new-location-form').attr("action", "{{ route('createAndAddMoreInfoLocation', $clientDetail['id']) }}");
        });

        function goBack() {
            window.location.replace("{{ route('showDetailClient', $clientDetail['id']) }}");
        }
    </script>
@stop