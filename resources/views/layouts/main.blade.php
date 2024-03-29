<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, shrink-to-fit=9" />

        <meta name="author" content="Obi Nnaekeka" />
        <!-- Primary Meta Tags -->
        <title>@yield('title')</title>
        <meta name="title" content="@yield('title')">
        <meta name="description" content="@yield('description')">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="@yield('url')">
        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="@yield('description')">
        <meta property="og:image" content="@yield('image')">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="@yield('url')">
        <meta property="twitter:title" content="@yield('title')">
        <meta property="twitter:description" content="@yield('description')">
        <meta property="twitter:image" content="@yield('image')">

        <link rel="icon" type="image/png" href="{{ asset("assets/images/fav.png") }} " />

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,500" rel="stylesheet" />
        <link href="{{ asset("assets/vendor/unicons-2.0.1/css/unicons.css") }}" rel="stylesheet" />
        <link href="{{ asset("assets/css/vertical-responsive-menu.min.css") }}" rel="stylesheet" />
        <link href="{{ asset("assets/css/style.css") }}" rel="stylesheet" />
        <link href="{{ asset("assets/css/responsive.css") }}" rel="stylesheet" />
        {{-- <link href="{{  asset('assets/css/vertical-responsive-menu1.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('assets/css/instructor-dashboard.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/instructor-responsive.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/jquery-steps.css') }}" rel="stylesheet" />

        <link href="{{ asset("assets/css/night-mode.css") }}" rel="stylesheet" />

        <link href="{{ asset("assets/vendor/fontawesome-free/css/all.min.css") }}" rel="stylesheet" />
        <link href="{{ asset("assets/vendor/OwlCarousel/assets/owl.carousel.css") }}" rel="stylesheet" />
        <link href="{{ asset("assets/vendor/OwlCarousel/assets/owl.theme.default.min.css") }}" rel="stylesheet" />
        <link href="{{ asset("assets/vendor/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/vendor/semantic/semantic.min.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/css/reset.css") }}">
        <link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet" />
        <link href="{{ asset("assets/css/jquery.mCustomScrollbar.min.css") }}" rel="stylesheet" />
        <x-head.tinymce-config/>
    </head>

    <header class="header clearfix xheader">
        @auth
            <button type="button" id="toggleMenu" class="toggle_menu">
                <i class="uil uil-bars"></i>
            </button>
            <button id="collapse_menu" class="collapse_menu">
                <i class="uil uil-bars collapse_menu--icon"></i>
                <span class="collapse_menu--label"></span>
            </button>
        @endauth

        <div class="main_logo" id="logo">
            <a href="/"><img src="{{ asset("assets/images/logo.png") }}" alt="" style="width:100px;"/></a>
            <a href="/"><img class="logo-inverse" src="{{ asset("assets/images/logo-dark.png") }}" style="width:100px;" alt="" /></a>
        </div>

            <div class="top-category">
                <div class="ui compact menu cate-dpdwn">
                    <div class="ui simple dropdown item">
                        <a href="#" class="option_links p-0" title="categories"><i class="uil uil-apps"></i></a>
                        <div class="menu dropdown_category5">
                            @foreach (appcategories() as $row)
                            <a href="/category/cat/{{ $row->id }}" class="item channel_item">{{ $row->name }}</a>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

        <div class="search120">
            <div class="ui search">
                <div class="ui left icon input swdh10">
                    <input class="prompt srch10" type="text" placeholder="Search for Videos, Tutors, and lectures.." />
                    <i class="uil uil-search-alt icon icon1"></i>
                </div>
            </div>
        </div>
        <div class="header_right">
            <ul>
                @guest
                <li class="nav__other nav__last">
                    <a href="/register?tutor=true"><strong>Start Teaching</strong></a>
                </li>
                @endguest

                @auth
                    <li>
                       @if(auth()->user()->type == "student")
                        @if(auth()->user()->instructor_status == 'pending')
                            <a href="#" class="upload_btn" title="Create New Course">Instructor Application Pending</a>
                        @endif
                        @if(auth()->user()->instructor_status == 'unapplied')
                            <a href="{{ route('instructor.application') }}" class="upload_btn" title="Create New Course">Start Teaching</a>
                        @endif
                        @if(auth()->user()->instructor_status == 'declined')
                            <a href="{{ route('instructor.application') }}" class="upload_btn" title="Create New Course">Instructor Declined</a>
                        @endif

                       @else
                       <a href="{{ route('create.course') }}" class="upload_btn" title="Create New Course">Create New Course</a>
                       @endif
                    </li>
                @endauth
                @guest
                    <li>
                        <a href="{{ route("login") }}" class="upload_btn" title="Login">Login</a>
                    </li>
                    <li>
                        <a href="{{ route("register") }}" class="upload_btn" title="Register">Register</a>
                    </li>
                @endguest
                    <li>
                        <a href="{{ route('cart') }}" class="option_links" title="cart"><i class="uil uil-shopping-cart-alt"></i><span class="noti_count">{{ countInCart() }}</span></a>
                    </li>
                    <li class="nav__other">
                        <a href="{{ route('contact') }}" class="option_links" title="contact"><i class="uil uil-phone"></i></a>
                    </li>
                @auth
                    <li class="ui dropdown">
                        <a href="#" class="opts_account" title="Account">
                            <img src="@if(auth()->user()->photo) {{ asset(auth()->user()->photo) }} @else {{ asset("assets/images/hd_dp.jpg") }} @endif" alt="" />
                        </a>
                        <div class="menu dropdown_account">
                            <div class="channel_my">
                                <div class="profile_link">
                                    <img src="@if(auth()->user()->photo) {{ asset(auth()->user()->photo) }} @else {{ asset("assets/images/hd_dp.jpg") }} @endif" alt="{{ auth()->user()->fullname }}" />
                                    <div class="pd_content">
                                        <div class="rhte85">
                                            <h6>{{ auth()->user()->fullname }}</h6>
                                            <div class="mef78" title="Verify">
                                                <i class="uil uil-check-circle"></i>
                                            </div>
                                        </div>
                                        <span><a href="" class="__cf_email__">{{ auth()->user()->email }}</a></span>
                                    </div>
                                </div>
                                <a href="#l" class="dp_link_12">View Profile</a>
                            </div>
                            <div class="night_mode_switch__btn">
                                <a href="#" id="night-mode" class="btn-night-mode">
                                    <i class="uil uil-moon"></i> Night mode
                                    <span class="btn-night-mode-switch">
                                        <span class="uk-switch-button"></span>
                                    </span>
                                </a>
                            </div>
                            <a href="{{ route('settings') }}" class="item channel_item">Settings</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="item channel_item">Sign Out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endauth

            </ul>
        </div>
    </header>

    <header class="mobile_header header" style="display:none;">
        <a href="javascript:void(0)" class="option_links text-center @guest click_menu @endguest @auth authClickMenu @endauth" title="menu">
            <div class="d-flex flex-column">
                <i class="uil uil-bars"></i>
                <span style="font-size:11px;">Menu</span>
            </div>
        </a>
        <a href="{{ route('contact') }}" class="option_links text-center" title="cart">
            <div class="d-flex flex-column">
                <i class="uil uil-phone"></i>
                <span style="font-size:11px;">Contact</span>
            </div>
        </a>
        <div class="main_logo" id="logo">
            <a href="/"><img src="{{ asset("assets/images/logo.png") }}" alt="" style="width:110px;"/></a>
            <a href="/"><img class="logo-inverse" src="{{ asset("assets/images/logo-dark.png") }}" style="width:110px;" alt="" /></a>

        </div>
        <a href="{{ route('cart') }}" class="option_links text-center" title="cart">
            <div class="d-flex flex-column">
                <i class="uil uil-shopping-cart-alt"></i><span class="noti_count">{{ countInCart() }}</span>
                <span style="font-size:11px;">Cart</span>
            </div>
        </a>
        @auth
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form2').submit();"  class="option_links text-center" title="cart">
            <div class="d-flex flex-column">
                <i class="uil uil-unlock"></i>
                <span style="font-size:11px;">Logout</span>
            </div>
        </a>
        <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        @endauth

        @guest
        <a href="{{ route('login') }}" class="option_links text-center" title="cart">
            <div class="d-flex flex-column">
                <i class="uil uil-user"></i>
                <span style="font-size:11px;">Login</span>
            </div>
        </a>
        @endguest
    </header>

    @include("layouts.drawer")

    @include("layouts.vertical_nav")


    @yield('content')

    <script data-cfasync="false" src="{{ asset("assets/cloudflare-static/email-decode.min.js") }}"></script>
    <script src="{{ asset("assets/js/vertical-responsive-menu.min.js") }}"></script>
    <script src="{{ asset("assets/js/jquery-3.3.1.min.js") }}"></script>
    <script src="{{ asset("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/vendor/OwlCarousel/owl.carousel.js") }}"></script>
    <script src="{{ asset("assets/vendor/semantic/semantic.min.js") }}"></script>
    <script src="{{ asset("assets/js/custom.js") }}"></script>
    <script src="{{ asset('assets/js/custom1.js') }}"></script>
    <script src="{{ asset("assets/js/night-mode.js") }}"></script>
    <script src="{{ asset('assets/js/jquery-steps.min.js') }}"></script>
    <script src="https://vjs.zencdn.net/8.0.4/video.min.js"></script>
    <script src="{{ asset("assets/js/jquery.mCustomScrollbar.js") }}"></script>
    <script>
        $(".add-instructor-tab").steps({
            onFinish: function () {
                $('#instructor-application').submit();
            },


        });

        $("#add-course-tab").steps({
            onFinish: function () {
                $('#course-submittion').submit();
            },
        });
    </script>
    <script>
            $('.click_menu').on("click", function() {
                $('.drawer').css("display", "block");
            });

            $('.authClickMenu').on("click", function() {
                $('.vertical_nav').hasClass("vertical_nav__opened");
                if ($( ".vertical_nav" ).hasClass('vertical_nav__opened')) {
                    $( ".vertical_nav" ).removeClass( 'vertical_nav__opened');
                } else {
                $( ".vertical_nav" ).addClass( 'vertical_nav__opened');
                }
            })

            $('.drawer__close').on("click", function() {
                $('.drawer').css("display", "none");
            });
            $(function () {
                if (document.querySelector('.sortable') !== null) {
                    $(".sortable").sortable();
                    $(".sortable").disableSelection();
                }
            });
    </script>
</body>

</html>
