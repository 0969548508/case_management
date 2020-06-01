@extends('layouts.app')

@section('content')
@include('navbar')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="title pb-0">@if (!isset($rateId)) Create Rate @else Rate Detail @endif</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ (!isset($rateId)) ? route('createRate') : route('updateRate', $rateId) }}">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Invoice Item') }}</label>

                            <div class="col-md-12 col-lg-4">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" @if(isset($rateId)) value="{{ $rateDetail->name }}" @else value="{{ old('name') }}" @endif required placeholder="Enter Invoice Item" pattern=".*\S+.*" @if(!auth()->user()->hasAnyPermission('edit rates')) readonly @endif>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Description') }}</label>

                            <div class="col-md-12 col-lg-4">
                                <textarea class="form-control" id="description" rows="3" placeholder="Enter Description" name="description" @if(!auth()->user()->hasAnyPermission('edit rates')) readonly @endif>{{ (isset($rateId)) ? $rateDetail->description : old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Default Price') }}</label>

                            <div class="col-md-12 col-lg-2">
                                <input id="default_price" type="text" numeric oninput="this.value = this.value.replace(/[^0-9-.,]/g, '').split(/\-/).slice(0, 2).join('-')" onchange="this.value = this.value.trim()" class="form-control @error('default_price') is-invalid @enderror" name="default_price" @if(isset($rateId)) value="{{ $rateDetail->default_price }}" @else value="{{ old('default_price') }}" @endif required placeholder="Enter Default Price" @if(!auth()->user()->hasAnyPermission('edit rates')) readonly @endif>
                            </div>
                        </div>

                        <div class="form-group row pt-5 pl-3 mb-0">
                            <div>
                                <button type="button" class="btn btn-primary m-auto custom-button-cancel" id="btn-cancel-save-rate" onclick="goBack()">
                                    {{ __('CANCEL') }}
                                </button>
                                @can('edit rates')
                                    <button type="submit" class="btn btn-primary m-auto custom-button" id="btn-save-rate">
                                        @if (!isset($rateId)) {{ __('SAVE') }} @else {{ __('UPDATE') }} @endif
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($rateId))
        <!-- custom modal -->
        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTitle">{{ __('Delete Rate') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('Are you sure you want to delete this rate?') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <form action="{{ route('deleteRate', $rateId) }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-delete-modal">{{ __('DELETE') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('javascript')
    <script>
        function deleteRate() {
            $('#modalDelete').modal('show');
        }

        function goBack() {
            window.location.replace("{{ route('showListRate') }}");
        }
    </script>
@endsection

