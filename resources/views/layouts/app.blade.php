<!doctype html>
<!--
* CoreUI Pro - Bootstrap Admin Template
* @version v2.1.14
* @link https://coreui.io/pro/
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* License (https://coreui.io/pro/license)
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <!-- Datatable -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.js') }}" defer></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <!-- Datatable -->
    <link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet">

    <!-- Role styles -->
    <link href="{{ asset('css/role.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Coreui -->
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/free.min.css') }}">

    <!-- Main styles for coreui -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/coreui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/pace-progress/css/pace.min.css') }}">
    <!-- End -->
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <header class="app-header navbar shadow bg-white rounded">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="{{ route('home') }}">
            <img class="navbar-brand-full" src="{{ asset('images/lka_logo.png') }}" width="123" height="34" alt="lka logo">
        </a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none ml-016" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-sm-down-none" id="search-global-form">
            <li class="in">
                <form id="search-suggestion-form" role="search" class="app-search hidden-xs">
                    <input class="form-control search-box" name="search" id="search-suggestion" type="search" placeholder="Type keyword and enter" aria-label="Search">
                    <a href="javascript:void(0);" class="active">
                        <img src="{{ asset('images/search_icon.svg') }}" class="search-icon" alt="search icon">
                    </a>
                </form>
                <div class="suggestion-form">
                    <div id="filter-suggestion">
                    </div>
                </div>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown d-md-down-none">
                @include('layouts.notification')
            </li>
            <li class="d-md-down-none vertical-line">
                <span class="separator"></span>
            </li>
            <div id="userbox" class="userbox">
                <a href="#" data-toggle="dropdown" aria-expanded="false">
                    <figure class="profile-picture">
                        <?php
                            $urlAvata = App\Http\Controllers\UserController::loadImageUserLogin();
                        ?>
                        @if (!empty($urlAvata))
                            <img src="{{ $urlAvata }}" alt="avata" class="avata-sidebar float-left">
                        @else
                            <div class="member-sidebar float-left">
                                <span class="avata-sidebar member-initials-sidebar">{{ substr(auth()->user()->name, 0, 1) . '' . substr(auth()->user()->family_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </figure>
                    <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@JSOFT.com">
                        <span class="name">{{ ucwords(auth()->user()->name) . ' ' . ucwords(auth()->user()->family_name) }}</span>
                        <span class="role">{{ ucwords(App\Http\Controllers\UserController::getRoleNameByUserId(auth()->user()->id)) }}</span>
                    </div>
                    <span><img src="{{ asset('images/drop-down-arrow.svg') }}" alt="arrow-account" class="dropdown-arrow-account"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Account</strong>
                    </div>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-bell-o"></i>Updates
                        <span class="badge badge-info">42</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('showViewChangePassword') }}">
                        <i class="fa fa-bell-o"></i>Change password
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-lock"></i>{{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </ul>
    </header>
    <div class="app-body">
        <?php
            $tenant = tenancy()->all()->pluck('domains');
            $current_domain = tenancy()->getTenant('domains');

            $flag = 0;

            if (!empty($current_domain)) {
                $current_domain = $current_domain[0];
            }

            foreach ($tenant as $value) {
                if ($current_domain == $value[0]) {
                    $flag = 1;
                    break;
                }
            }
        ?>
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    @if ($flag == 0)
                        @include('layouts.menu-super-admin')
                    @else
                        @include('layouts.menu-system-admin')
                    @endif
                </ul>
            </nav>
        </div>

        <!-- Page Content -->
        <main class="main">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
        @yield('javascript')
    </div>

    <script>
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-bottom-right"
        }
        @if(Session::has('alerts'))
            let alerts = {!! json_encode(Session::get('alerts')) !!};
            helpers.displayAlerts(alerts, toastr);
        @endif

        @if(Session::has('message'))
            var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
            var alertMessage = {!! json_encode(Session::get('message')) !!};
            var alerter = toastr[alertType];

            if (alerter) {
                alerter(alertMessage);
            } else {
                toastr.error("toastr alert-type " + alertType + " is unknown");
            }
        @endif
        // rotate 90 of down-arrow
        $(document).ready(function(){
            $('.js-change-caret').click(function(){
                $('.js-change-caret i').toggleClass('fa-caret-down')
                $('.js-change-caret i').toggleClass('fa-caret-right')
            });

            $('.notification-header-mark-as-read').click(function(){
                $("span.notification-header-number").hide();
                $("span.notification-circle").hide();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('markAllAsRead') }}",
                    method: 'get',
                    data: '',
                    success: function(response) {
                        $("div.dropdown-menu-notification .notification-scroll").find(".notification-item").removeClass("notification-unread");
                        $("div#page-notifications-list").find(".page-notification-item").removeClass("page-notification-unread");
                    }
                });
            });

            //Ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                }
            });

            // Search Suggestion
            var oldVal;
            $(document).on('keyup', '#search-suggestion', function() {
                let val = this.value;
                if (val !== oldVal) {
                    oldVal = val;
                    search(val);
                }
            });

            function search(searchValue) {
                let data = {
                    'search': searchValue,
                    'type'  : 'suggestion'
                };
                jQuery.ajax({
                    url: "{{ route('fullTextSearch') }}",
                    data: data,
                    method: 'get',
                    success: function(response){
                        $('div[id=filter-suggestion]').html(response.html);
                    },
                    error: function(){
                        // toastr.error('Fail to load data.');
                    }
                });
            }
            // End Search Suggestion

            window.onclick = function(e) {
                if ($("input#search-suggestion").is(":focus")) {
                    $('#filter-suggestion').css('display', 'inline');
                } else {
                    $('#filter-suggestion').css('display', 'none');
                }
            }
        });
    </script>

    <!-- Scripts Coreui -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/coreui.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}" defer></script>
  
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
</body>
</html>
