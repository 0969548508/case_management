@extends('layouts.layout_login')

@section('login_page')
<div class="row">
    <div class="col-xl-4 p-0">
        <div class="logo_login">
            <img class="img-responsive background-img-responsive w-100" src="{{ asset('images/background_login.png') }}" alt="background">
            <div class="centered">
                <img class="img-responsive w-25" src="{{ asset('images/lka_white_logo.png') }}" alt="">
                <hr class="hr-rectangle">
                <p class="content-login">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque aliquam dui in pretium ornare. Etiam in turpis ac quam suscipit fringilla.</p>
            </div>
        </div>
    </div>

    <div class="col-xl-8 login-form m-auto p-5">
        <div class="container mb-sm-5">
            <div class="row">
                <div class="col-xl-12 m-auto">
                    <h2 class="panel-heading text-center">2-Step Verification</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 m-auto">
                    <p class="text-center">You have received an email which contains two factor login code. Please input that code below.</p>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('verifyToken') }}">
                {{ csrf_field() }}
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 m-auto">
                            <a id="resend-code" class="btn-resend-code m-auto float-right" href="javascript:void(0);">
                                {{ __('Resend code') }}
                            </a>
                            <div class="container mb-sm-5">
                                <div class="row">
                                    <div class="page input-otp">
                                        <label class="field a-field a-field_a1">
                                            <input id="one_time_password" class="field__input a-field__input" placeholder="Enter OTP" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" required autofocus>
                                            @if($errors->has('token'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('token') }}</strong>
                                                </span>
                                           @endif
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="container mb-sm-4">
                                <div class="row">
                                    <button type="submit" class="btn btn-primary m-auto" id="btn-pin-lka-login">
                                        {{ __('Verify') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <div class="container">
                <div class="row">
                    <a class="btn-back m-auto" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('< Back') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script>
        var heightBackground = $(window).height();
        $('.logo_login .background-img-responsive').css({'height' : heightBackground});

        $(window).resize(function(){
            heightBackground = $(window).height();
            $('.logo_login .background-img-responsive').css({'height' : heightBackground});
        })

        $("a#resend-code").click(function(){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            });
            jQuery.ajax({
                url: "{{ route('resendCode') }}",
                data: "",
                method: 'get',
                success: function(response){
                    if (typeof (response.errors) !== 'undefined') {
                        toastr.error(response.errors);
                    } else {
                        toastr.success(response.success);
                    }
            }});
        })
    </script>
@endsection
