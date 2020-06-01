@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-37">
    <h1 class="title mb-12">Change password</h1>
</div>
@php
    $total_old_pass = 0;
    $total_new_pass = 0;
    $total_confirm_pass = 0;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('changePassword') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="name" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Old password') }}</label>
                                <div class="col-md-12 col-lg-3">
                                    <i class="fas fa-eye user-icon float-right mr-md-2 show-password"></i>
                                    <i class="fas fa-eye-slash user-icon float-right mr-md-2 hide-password"></i>
                                    <input id="old-password" type="password" class="form-control pr-5" name="old-password" required autocomplete="old-password" autofocus value="{{ old('old-password') }}">
                                    @if($errors->has('old-password'))
                                        <div class="error error-notif">
                                            <span class="x-style">&times;</span>&nbsp;&nbsp;
                                            {{ $errors->first('old-password')}}
                                        </div>
                                        @php
                                            $total_old_pass= $total_old_pass + 1;
                                        @endphp
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('New password') }}</label>

                                <div class="col-md-12 col-lg-3">
                                    <i class="fas fa-eye user-icon float-right mr-md-2 show-new-password"></i>
                                    <i class="fas fa-eye-slash user-icon float-right mr-md-2 hide-new-password"></i>
                                    <input id="new-password" type="password" class="form-control pr-5" name="new-password" required autocomplete="new-password" autofocus value="{{ old('new-password') }}">

                                    <input id="new-password-1" class="form-control" name="new-password-1" hidden>
                                    <input id="new-password-2" class="form-control" name="new-password-2" hidden>
                                    <input id="new-password-3" class="form-control" name="new-password-3" hidden>
                                    <input id="password-history" class="form-control" name="password-history" hidden>
                                    @if($errors->has('new-password') || $errors->has('new-password-1') || $errors->has('new-password-2') || $errors->has('new-password-3') || $errors->has('password-history'))
                                        <div class="error error-notif">
                                            <ul style="list-style-type: none;" class="new-pass">
                                                <li class="li-style">
                                                    <span style="font-weight: 600; font-size: 13px; color: #1D1F21;">Password must meet following requirements</span>
                                                </li>
                                                <li class="li-style">
                                                    <?php
                                                    if($errors->first('password-history') != '') { echo '<span class="x-style">&times;</span>&nbsp;&nbsp;';}
                                                    ?>
                                                    {{ $errors->first('password-history') }}
                                                </li>
                                                <li class="li-style">
                                                    <?php
                                                    if($errors->first('new-password') != '') { echo '<span class="x-style">&times;</span>&nbsp;&nbsp;'; $total_new_pass = $total_new_pass + 1;}
                                                    ?>
                                                    {{ $errors->first('new-password') }}
                                                </li>
                                                <li class="li-style">
                                                    <?php
                                                    if($errors->first('new-password-1') != '') { echo '<span class="x-style">&times;</span>&nbsp;&nbsp;'; $total_new_pass = $total_new_pass + 1;}
                                                    ?>
                                                    {{ $errors->first('new-password-1') }}
                                                </li>
                                                <li class="li-style">
                                                    <?php
                                                    if($errors->first('new-password-2') != '') {  echo '<span class="x-style">&times;</span>&nbsp;&nbsp;'; $total_new_pass = $total_new_pass + 1;}
                                                    ?>
                                                    {{ $errors->first('new-password-2') }}
                                                </li>
                                                <li class="li-style">
                                                    <?php
                                                    if($errors->first('new-password-3') != '') { echo '<span class="x-style">&times;</span>&nbsp;&nbsp;'; $total_new_pass = $total_new_pass + 1;}
                                                    ?>
                                                    {{ $errors->first('new-password-3') }}
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                @if($errors->has('confirm-password'))
                                    @php
                                        $total_confirm_pass = $total_confirm_pass + 1;
                                    @endphp
                                @endif
                                @if(($total_new_pass > 0 || $total_new_pass == 0) && ($total_old_pass != 0 || $total_confirm_pass != 0))
                                    <div class="col-md-12 col-lg-2" id="progress-bar-section">
                                        <div class="progress bar" >
                                            <div class="progress-bar {{ ($total_new_pass == 0) ? 'bg-success' : 'danger-bar' }}" role="progressbar" style="width: @php
                                            if($total_new_pass > 0)
                                                echo (100 - (25*$total_new_pass));
                                            elseif($total_new_pass == 0 || $total_new_pass == 4)
                                                echo 100;
                                            @endphp%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        @if($total_new_pass != 0)
                                        <span style="color:#434444; font-size: 10px; line-height: 14px; font-style: italic;">Not compliant to policy</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-12 col-lg-2 col-form-label text-md-left">{{ __('Confirm new password') }}</label>

                                <div class="col-md-12 col-lg-3">
                                    <i class="fas fa-eye user-icon float-right mr-md-2 show-confirm-password"></i>
                                    <i class="fas fa-eye-slash user-icon float-right mr-md-2 hide-confirm-password"></i>
                                    <input id="confirm-password" type="password" class="form-control pr-5" name="confirm-password" required autocomplete="confirm-password" autofocus value="{{ old('confirm-password') }}">
                                    @if($errors->has('confirm-password'))
                                        <div class="error error-notif">
                                            <span class="x-style">&times;</span>&nbsp;&nbsp;
                                            {{ $errors->first('confirm-password') }}
                                        </div>
                                        @php
                                            $total_confirm_pass = $total_confirm_pass + 1;
                                        @endphp
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row pt-5 pl-3 mb-0">
                                <div>
                                    <button type="button" class="btn btn-primary m-auto custom-button-cancel" id="btn-cancel-save-password">
                                        {{ __('CANCEL') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary ml-1 custom-button" id="btn-save-password">
                                        {{ __('SAVE') }}
                                    </button>
                                </div>
                            </div>
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
        $("#new-password").keyup(function(){
            $("#new-password-1").val(this.value);
            $("#new-password-2").val(this.value);
            $("#new-password-3").val(this.value);
        });

        $(".show-new-password, .hide-new-password").on('click', function() {
            if ($(this).hasClass('show-new-password')) {
                $("input#new-password").attr("type", "text");
                $(this).parent().find(".show-new-password").hide();
                $(this).parent().find(".hide-new-password").show();
            } else {
                $("input#new-password").attr("type", "password");
                $(this).parent().find(".hide-new-password").hide();
                $(this).parent().find(".show-new-password").show();
            }
        });

        $(".show-confirm-password, .hide-confirm-password").on('click', function() {
            if ($(this).hasClass('show-confirm-password')) {
                $("input#confirm-password").attr("type", "text");
                $(this).parent().find(".show-confirm-password").hide();
                $(this).parent().find(".hide-confirm-password").show();
            } else {
                $("input#confirm-password").attr("type", "password");
                $(this).parent().find(".hide-confirm-password").hide();
                $(this).parent().find(".show-confirm-password").show();
            }
        });

        $(".show-password, .hide-password").on('click', function() {
            if ($(this).hasClass('show-password')) {
                $("input#old-password").attr("type", "text");
                $(this).parent().find(".show-password").hide();
                $(this).parent().find(".hide-password").show();
            } else {
                $("input#old-password").attr("type", "password");
                $(this).parent().find(".hide-password").hide();
                $(this).parent().find(".show-password").show();
            }
        });
    </script>
@stop
