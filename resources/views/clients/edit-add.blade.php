@extends('layouts.app')

@section('content')
    @include('navbar')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="title mb-0">Create Client</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="create-client-form" method="POST" action="{{ route('createClient') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Client -->
                        <div class="form-group row">
                            <label class="col-md-12 col-xl-2 col-form-label text-md-left text-color-8E8E93">{{ __('Company Info') }}</label>
                        </div>
                        <div class="col-xl-12 mb-5 p-0">
                            <div class="row ml-0">
                                <div class="imgUp mr-3">
                                    <div class="avata-create-client">
                                        <label class="btn btn-upload-avata">
                                            <span id="title-upload">
                                                Upload
                                            </span>
                                            <input type="file" class="uploadFile img w-0 h-0 overflow-hidden" name="image">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-7 col-xl-9 m-auto ml-md-3 p-0">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Company name *') }}</label>
                                        <div class="col-md-12 col-xl-3">
                                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Input company Name" pattern=".*\S+.*">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Company ABN *') }}</label>
                                        <div class="col-md-12 col-xl-3">
                                            <input id="abn" type="phone" class="form-control" name="abn" value="{{ old('abn') }}" required autocomplete="abn" placeholder="Input company ABN" pattern=".*\S+.*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-group row">
                            <label class="col-md-12 col-xl-2 col-form-label text-md-left text-color-8E8E93">{{ __('Location Info') }}</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Location name *') }}</label>
                            <div class="col-md-8 col-xl-3">
                                <input id="location-name" type="text" class="form-control" name="location_name" value="{{ old('location_name') }}" required autocomplete="name" placeholder="Input location Name" pattern=".*\S+.*">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-md-12 col-xl-2 col-form-label text-md-left">{{ __('Location ABN *') }}</label>
                            <div class="col-md-8 col-xl-3">
                                <input id="location-abn" type="phone" class="form-control" name="location_abn" value="{{ old('location_abn') }}" required autocomplete="abn" placeholder="Input location ABN" pattern=".*\S+.*">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 col-xl-3">
                                <label class="col-md-12 mb-2">
                                    <input id="is-primary" class="mr-2" type="checkbox" name="is_primary" checked><span class="checkmark"></span> <span>Primary Location</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row pt-5 pl-3 mb-0">
                            <button type="button" class="btn btn-primary custom-button-cancel mb-2 mb-md-0" id="btn-cancel-save-client" onclick="goBack()">
                                {{ __('CANCEL') }}
                            </button>
                            <button type="submit" class="btn btn-primary custom-button mb-2 mb-md-0" id="btn-save-add-more-info" style="width: 198px;">
                                {{ __('SAVE & ADD MORE INFO') }}
                            </button>
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
    <script>
        // upload images
        $(function() {
            $(document).on("change", ".uploadFile", function() {
                var uploadFile = $(this);
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader) return;

                if (/^image/.test( files[0].type)) {
                    var reader = new FileReader();
                    reader.readAsDataURL(files[0]);

                    reader.onloadend = function(){
                        uploadFile.closest(".imgUp").find('.avata-create-client').css("background-image", "url(" + this.result + ")");
                    }

                    $('.avata-create-client').css({'background-size': 'cover'});
                    $('#title-upload').text('Edit');
                }
            });
        });

        $('#btn-save-add-more-info').click(function (e) {
            $('#create-client-form').attr("action", "{{ route('createAndAddMoreInfo') }}");
        });

        function goBack() {
            window.location.replace("{{ route('showListClient') }}");
        }
    </script>
@stop