
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/icheck/all.css">
    <link rel="stylesheet" href="/css/custom.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<!-- Main Nav Top -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-full">
        <button type="button" class="btn btn-default">Home</button>
        <button type="button" class="btn btn-default btn-default--help">Help</button>


    </div>
</nav>
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>SM</b>Upsell</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">Smart Search</span>
        </a>

        <!-- Header Navbar -->
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <!-- Optionally, you can add icons to the links -->
                <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ url('/') }}"><i class="ion ion-ios-information-outline"></i><span>Dashboard</span></a></li>
                <li class="{{ Request::is('offer/list') ? 'active' : '' }}"><a href="{{ url('/offer/list') }}"><i class="ion ion-ios-analytics"></i><span>Analytics </span></a></li>
                <li class="{{ Request::is('setting') ? 'active' : '' }}"><a href="{{ url('/setting') }}"><i class="ion ion-ios-search-strong"></i><span>Settings</span></a></li>
                <!--<li class="{{ Request::is('proxy') ? 'active' : '' }}"><a href="{{ url('proxy') }}"><i class="ion ion-ios-color-filter-outline"></i><span>Proxy</span></a></li>-->
                <!--<li class="{{ Request::is('report/invoiceList') ? 'active' : '' }}"><a href="{{ url('/report/invoiceList') }}"><span>Invoices</span></a></li>-->
            <!--<li class="{{ Request::is('plan/list') ? 'active' : '' }}"><a href="{{ url('/plan/list') }}">Price Plans</span></a></li>-->
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="app">
        <div id="in-progress" style="display: none">
            <img src="{{asset('images/loading.gif')}}">
        </div>
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>

@if(config('shopify-app.esdk_enabled'))
    <script src="https://cdn.shopify.com/s/assets/external/app.js?{{ date('YmdH') }}"></script>
    <script type="text/javascript">
        ShopifyApp.init({
            apiKey: '{{ config('shopify-app.api_key') }}',
            shopOrigin: 'https://{{ ShopifyApp::shop()->shopify_domain }}',
            debug: false,
            forceRedirect: true
        });
    </script>

    @include('shopify-app::partials.flash_messages')
@endif
<!-- ./wrapper -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2019 <a href="https://www.smartosc.com/">SmartOSC</a>.</strong> All rights reserved.
</footer>
<!-- REQUIRED JS SCRIPTS -->

<script src="/js/app.js"></script>
<script src="/icheck/icheck.min.js"></script>
<script src="/js/custom.js"></script>
<script src="/js/validate.js"></script>

@yield('page-script')
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>
