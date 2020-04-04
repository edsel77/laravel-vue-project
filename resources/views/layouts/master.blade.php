<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel + Vue</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index3.html" class="brand-link">
            <span class="brand-text font-weight-light">Laravel + Vue (SPA)</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="user-name">
                        <i class="fas fa-user-circle mr-1"></i>
                        <span>{{ Auth::user()->firstname.' '.Auth::user()->lastname }}</span>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <router-link to="/user-management" class="nav-link">
                                <i class="fas fa-users mr-1"></i>
                                <p>User Management</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/profile/{{ Auth::user()->id }}" class="nav-link">
                                <i class="fas fa-user-shield mr-1"></i>
                                <p>My Profile</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/account-setting/{{ Auth::user()->id }}" class="nav-link">
                                <i class="fas fa-user-cog mr-1"></i>
                                <p>Account Setting</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                <p>{{ __('Logout') }}</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
