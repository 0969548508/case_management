@can('view matters')
@can('view files and folders')
@extends('layouts.app')
@section('content')
@include('navbar')
@include('matter.matter-detail-information.matter-detail-header')
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-3">
                <div class="card-body tab-content recent-cases">
                    <div id="file-manager-table">
                        @include('matter.matter-detail-files.files-datatable')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            @include('matter.matter-detail-information.matter-detail-information-assignees')
            @include('matter.matter-detail-information.matter-detail-information-milestone')
        </div>
    </div>
    @include('matter.matter-detail-files.modals-file')
@endsection
@endcan
@endcan

@section('javascript')
    @include('matter.matter-detail-information.script-matter-detail-information')
@stop