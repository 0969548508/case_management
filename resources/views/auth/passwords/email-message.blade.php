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
        <div class="container mb-sm-4">
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <h2 class="panel-heading text-left text-dark m-0 font-weight-normal">{{ __('Reset Your Password') }}</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <p class="text-left mb-1">We have sent a reset password email to <b>{{ $email }}</b>.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <p class="text-left mb-4">Please click the reset password link to set your new password.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <p class="text-left mb-1">Didn’t receice the email yet ?</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <p class="text-left mb-5">Please check your spam folder, or <span id="resend-email">resend</span> the email.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <a class="btn-back m-auto" href="{{ route('login') }}">
                        {{ __('< Login') }}
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

        $("#resend-email").click(function(){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            });
            jQuery.ajax({
                url: "{{ route('resendLinkResetPassword', $email) }}",
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
