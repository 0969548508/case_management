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
                <div class="col-xl-5 m-auto">
                    <h2 class="panel-heading text-center">{{ __('Reset Password') }}</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 m-auto">
                    <p class="text-center">Please tell us the email address you registed with the system then we will send you the link to reset yout password.</p>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 m-auto">
                            <div class="container mb-sm-5">
                                <div class="row">
                                    <div class="page input-otp">
                                        <label class="field a-field a-field_a1">
                                            <i class="fas fa-times user-icon d-none" id="rm-email-icon"></i>
                                            <input id="email" class="field__input a-field__input" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="padding-left: 3rem;">
                                            <span class="a-field__label-wrap" style="width: 0% !important">
                                                <div class="send-email-icon user-icon"></div>
                                            </span>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="container mb-sm-4">
                                <div class="row">
                                    <button type="submit" class="btn btn-primary m-auto" id="btn-pin-lka-login">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <div class="container">
                <div class="row">
                    <a class="btn-back m-auto" href="{{ route('login') }}">
                        {{ __('< Back') }}
                    </a>
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

        $('#email').on('input', function() {
            var value = $(this).val();
            if(value != '') {
                $('#rm-email-icon').removeClass('d-none');
            }else{
                $('#rm-email-icon').addClass('d-none');
            }
        });

        $('#rm-email-icon').click(function() {
            $('input#email').val('');
            $(this).addClass('d-none');
        });
    </script>
@endsection
