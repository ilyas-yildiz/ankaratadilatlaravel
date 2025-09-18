<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none" data-preloader="disable">
<head>
    @include('admin.layouts.partials.head')
    @stack('izitoastcss')
    @stack('dropzonecss')
    @stack('styles')
</head>
<body>
<div id="layout-wrapper">
    <header id="page-topbar">
        @include('admin.layouts.partials.header')
    </header>
    <div class="app-menu navbar-menu">
        @include('admin.layouts.partials.appmenu')
    </div>
    <div class="vertical-overlay"></div>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
                <footer class="footer">
                    @include('admin.layouts.partials.footer')
                </footer>
            </div>
        </div>
    </div>
</div>
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
@include('admin.layouts.partials.include_script')
@stack('scripts')
</body>
</html>
