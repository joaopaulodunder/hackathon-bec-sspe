<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Hackathon BEC
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    {{--<link rel="shortcut icon" href="{{ asset('admin-assets/favicon.ico') }}" type="image/x-icon">--}}
    {{--<link rel="icon" href="{{ asset('admin-assets/favicon.ico') }}" type="image/x-icon">--}}

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/styles/black.css') }}" rel="stylesheet" type="text/css" id="colorscheme" />
    <link href="{{ asset('assets/css/panel.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('assets/css/my-custom.css') }}" rel="stylesheet" type="text/css"/>
    <!-- font Awesome -->

    <!-- end of global css -->
    <!--page level css-->
    @yield('header_styles')
            <!--end of page level css-->

<body class="skin-josh">
<header class="header">
    <a href="{{Route('dashboard')}}" class="logo" style="color: white;">
        <img src="{{ asset('assets/images/logo_hack.png') }}" alt="logo">
        &nbsp;
        Hackathon BEC
    </a>
    <nav class="navbar navbar-static-top" role="navigation">

        <!-- Sidebar toggle button-->

        {{--<div>--}}
            {{--<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">--}}
                {{--<div class="responsive_nav"></div>--}}
            {{--</a>--}}
        {{--</div>--}}

        <div class="navbar-left">

        </div>

        <div class="navbar-right">

        </div>

    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas collapse-left">
        <section class="sidebar">
            <div class="page-sidebar sidebar-nav">
                <br />
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR MENU -->
                @include('template._left_menu')
                <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>
    <aside class="right-side strech">

        <!-- Content -->
        @yield('content')

    </aside>
    <!-- right-side -->
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top"
   data-toggle="tooltip" data-placement="left">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a>

<script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/livicons/minified/raphael-min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/livicons/minified/livicons-1.4.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/maskMoney.js') }}"></script>
<script  src="{{ asset('assets/js/inputmask/inputmask.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/js/inputmask/jquery.inputmask.js') }}"  type="text/javascript"></script>

<!-- begin page level js -->
@yield('footer_scripts')
<!-- end page level js -->

<script src="{{ asset('assets/js/my-custom.js') }}"></script>

</body>
</html>