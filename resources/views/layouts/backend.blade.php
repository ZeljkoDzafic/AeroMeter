<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aero Backend</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skins/skin-blue.min.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a href="/backend" class="logo">
            <span class="logo-mini"><b>A</b>B</span>
            <span class="logo-lg"><b>Aero</b> Backend</span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="/"><i class="fa fa-home"></i> Frontend</a>
                    </li>
                    <li>
                        <a href="/logout"><i class="fa fa-arrow-circle-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li @if(request()->is('backend/stations*')) class="active" @endif>
                    <a href="/backend/stations">
                        <i class="fa fa-tachometer"></i> <span>Stations</span>
                    </a>
                </li>
                @if(request()->user()->isAdmin())
                <li @if(request()->is('backend/tags*')) class="active" @endif>
                    <a href="/backend/tags">
                        <i class="fa fa-tags"></i> <span>Tags</span>
                    </a>
                </li>
                <li @if(request()->is('backend/users*')) class="active" @endif>
                    <a href="/backend/users">
                        <i class="fa fa-users"></i> <span>Users</span>
                    </a>
                </li>
                <li @if(request()->is('backend/configs*')) class="active" @endif>
                    <a href="/backend/configs">
                        <i class="fa fa-cogs"></i> <span>Configs</span>
                    </a>
                </li>
                @endif
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Something went wrong!.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @yield('content')
        </section>
    </div>
</div>

<script src="{{ asset('js/jquery-2.2.0.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>
@yield('scripts')
</body>
</html>
