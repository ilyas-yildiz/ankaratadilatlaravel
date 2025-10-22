<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- GÜNCELLENDİ: Partial yolu ve dosya adı --}}
    @include('frontend.partials._head')
    @stack('slider-styles')
</head>

<body>
    <div class="page-wraper">

        <header class="site-header nav-wide nav-transparent mobile-sider-drawer-menu">
            {{-- GÜNCELLENDİ: Partial yolu ve dosya adı --}}
            @include('frontend.partials._header')
        </header>
        <div class="page-content">
            @yield('content')
        </div>
        <footer class="site-footer footer-large footer-dark footer-wide">
            {{-- GÜNCELLENDİ: Partial yolu ve dosya adı --}}
            @include('frontend.partials._footer')
        </footer>
        <button class="scroltop"><span class="fa fa-angle-up relative" id="btn-vibrate"></span></button>

    </div>
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="cssload-spinner">
                <div class="cssload-cube cssload-cube0"></div>
                <div class="cssload-cube cssload-cube1"></div>
                <div class="cssload-cube cssload-cube2"></div>
                <div class="cssload-cube cssload-cube3"></div>
                <div class="cssload-cube cssload-cube4"></div>
                <div class="cssload-cube cssload-cube5"></div>
                <div class="cssload-cube cssload-cube6"></div>
                <div class="cssload-cube cssload-cube7"></div>
                <div class="cssload-cube cssload-cube8"></div>
                <div class="cssload-cube cssload-cube9"></div>
                <div class="cssload-cube cssload-cube10"></div>
                <div class="cssload-cube cssload-cube11"></div>
                <div class="cssload-cube cssload-cube12"></div>
                <div class="cssload-cube cssload-cube13"></div>
                <div class="cssload-cube cssload-cube14"></div>
                <div class="cssload-cube cssload-cube15"></div>
            </div>
        </div>
    </div>
    {{-- GÜNCELLENDİ: Partial yolu ve dosya adı --}}
    @include('frontend.partials._scripts')
    @stack('slider-scripts')
    @stack('custom-scripts')

</body>

</html>