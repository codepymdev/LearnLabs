
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <title>Verify Your Email Address</title>

    <link rel="icon" type="image/png" href="{{ asset("assets/images/fav.png") }}" />

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,500" rel='stylesheet'>
    <link href="{{ asset("assets/vendor/unicons-2.0.1/css/unicons.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/css/vertical-responsive-menu.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/css/style.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/css/responsive.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/css/night-mode.css") }}" rel="stylesheet" />

    <link href="{{ asset("assets/vendor/fontawesome-free/css/all.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/vendor/OwlCarousel/assets/owl.carousel.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/vendor/OwlCarousel/assets/owl.theme.default.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/vendor/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/vendor/semantic/semantic.min.css") }}" />
</head>
<body class="sign_in_up_bg">
    <div class="container">
        <div class="row justify-content-lg-center justify-content-md-center">
            <div class="col-lg-12">
                <div class="main_logo25" id="logo">
                    <a href="/"><img src="{{ asset("assets/images/logo.png") }}" style="width:100px;" alt="" /></a>
                    <a href="/"><img class="logo-inverse" style="width:100px;"  src="{{ asset("assets/images/logo-dark.png") }}" alt="" /></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-8">
                <div class="sign_form">
                    @if (session('resent'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button class="login-btn" type="submit">click here to request another</button>
                    </form>
                </div>
                <div class="sign_footer"><img src="{{ asset("assets/images/logo1.png") }}" alt="" />© {{ date('Y') }} <strong>{{ config("app.name") }}</strong>. All Rights Reserved.</div>
            </div>
        </div>
    </div>
    <script src="{{ asset("assets/js/jquery-3.3.1.min.js") }}"></script>
    <script src="{{ asset("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/vendor/OwlCarousel/owl.carousel.js") }}"></script>
    <script src="{{ asset("assets/vendor/semantic/semantic.min.js") }}"></script>
    <script src="{{ asset("assets/js/custom.js") }}"></script>
    <script src="{{ asset("assets/js/night-mode.js") }}"></script>
</body>

</html>
