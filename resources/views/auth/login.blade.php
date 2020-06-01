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
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-xl-6 m-auto">
                        <div class="container">
                            <div class="row">
                                <img class="img-responsive m-auto mb-sm-5" src="{{ asset('images/lka_logo.png') }}" alt="">
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="page">
                                    <label class="field a-field a-field_a1">
                                        <i class="fas fa-times user-icon float-right mr-md-2 d-none" id="rm-user-icon"></i>
                                        <input id="email" class="field__input a-field__input" placeholder="User Name" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="on" autofocus pattern="[^\s]+">
                                        <span class="a-field__label-wrap">
                                            <i class="fas fa-user fa-lg user-icon" aria-hidden="true"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="container mb-sm-2">
                            <div class="row">
                                <div class="page">
                                    <label class="field a-field a-field_a1">
                                        <i class="fas fa-times user-icon float-right mr-md-3 d-none" id="rm-password-icon"></i>
                                        <i class="fas fa-eye user-icon float-right mr-md-2 show-password"></i>
                                        <i class="fas fa-eye-slash user-icon float-right mr-md-2 hide-password"></i>
                                        <input type="password" id="password" class="field__input a-field__input" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        <span class="a-field__label-wrap">
                                            <i class="fas fa-lock fa-lg user-icon"></i>
                                        </span>
                                    </label>
                                    @error('email')
                                        <span class="invalid-feedback d-flex" role="alert">
                                            <i class="warning-txt w-100 text-right">{{ $message }}</i>
                                        </span>
                                    @else
                                        <span class="invalid-feedback d-flex" role="alert">
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="container mb-4">
                            <div class="row">
                                <div class="col-xl-12 p-0">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link forgot-password" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <button type="submit" class="btn btn-primary m-auto" id="btn-lka-login">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                $('#rm-user-icon').removeClass('d-none');
            }else{
                $('#rm-user-icon').addClass('d-none');
            }
        });

        $('#password').on('input', function() {
            var value = $(this).val();
            if(value != '') {
                $('#rm-password-icon').removeClass('d-none');
            }else{
                $('#rm-password-icon').addClass('d-none');
            }
        });

        $('#rm-user-icon').click(function() {
            $('input#email').val('');
            $(this).addClass('d-none');
        });

        $('#rm-password-icon').click(function() {
            $('input#password').val('');
            $(this).addClass('d-none');
        });

        $(".show-password, .hide-password").on('click', function() {
            if ($(this).hasClass('show-password')) {
                $("input#password").attr("type", "text");
                $(this).parent().find(".show-password").hide();
                $(this).parent().find(".hide-password").show();
            } else {
                $("input#password").attr("type", "password");
                $(this).parent().find(".hide-password").hide();
                $(this).parent().find(".show-password").show();
            }
        });
    </script>
@endsection
