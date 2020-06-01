@can('view matters')
@extends('layouts.app')
@section('content')
@include('navbar')
@include('matter.matter-detail-information.matter-detail-header')
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-3">
                <div class="card-body tab-content recent-cases">
                    <div class="row mb-2">
                        @include('matter.matter-detail-information.matter-detail-information-add-new-client')
                    </div> <hr>

                    <div class="row mb-2">
                        @include('matter.matter-detail-information.matter-detail-information-add-insurer-new-client')
                    </div> <hr>

                    <div class="row">
                        @include('matter.matter-detail-information.matter-detail-information-add-instructing-contact')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            @include('matter.matter-detail-information.matter-detail-information-assignees')
            @include('matter.matter-detail-information.matter-detail-information-milestone')
        </div>
    </div>
@endsection
@endcan

@section('javascript')
    @include('matter.matter-detail-information.script-matter-detail-information')
@stop