<div class="sticky-header main-bar-wraper navbar-expand-lg">
    <div class="main-bar">
        <div class="container clearfix">
            <div class="logo-header">
                <div class="logo-header-inner logo-header-one">
                    {{-- Anasayfa linki için route() helper'ı --}}
                    <a href="{{ route('frontend.home') }}"> 
                        <img src="{{ asset('assets/images/logos/yildizlogo3.png') }}" alt="">
                    </a>
                </div>
            </div>
            <button id="mobile-side-drawer" data-target=".header-nav" data-toggle="collapse" type="button" class="navbar-toggler collapsed">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar icon-bar-first"></span>
                <span class="icon-bar icon-bar-two"></span>
                <span class="icon-bar icon-bar-three"></span>
            </button>

            <div class="extra-nav">
                <div class="extra-cell">
                    <a href="#search">
                        <i class="fa fa-search"></i>
                    </a>
                </div>
            </div>
            <div class="header-nav nav-dark navbar-collapse collapse justify-content-center collapse">
                <ul class=" nav navbar-nav">
                    {{-- Linkleri route() helper'ları ile değiştirelim --}}
                    {{-- Request::routeIs() ile aktif menüyü belirleyebiliriz --}}
                    <li class="{{ Request::routeIs('frontend.home') ? 'active' : '' }}">
                        <a href="{{ route('frontend.home') }}">Anasayfa</a>
                    </li>
                    {{-- Hakkımızda için rota adı 'frontend.about' varsayalım --}}
                    <li class="{{ Request::routeIs('frontend.about') ? 'active' : '' }}"> 
                        <a href="{{ route('frontend.about') }}">Hakkımızda</a>
                    </li>
                     {{-- Hizmetler için rota adı 'frontend.services' varsayalım --}}
                    <li class="{{ Request::routeIs('frontend.services') ? 'active' : '' }}">
                        <a href="{{ route('frontend.services') }}">Hizmetlerimiz</a>
                    </li>
                     {{-- Projeler için rota adı 'frontend.projects' varsayalım --}}
                    <li class="{{ Request::routeIs('frontend.projects') ? 'active' : '' }}">
                        <a href="{{ route('frontend.projects') }}">Projelerimiz</a>
                    </li>
                     {{-- Blog için rota adı 'frontend.blog.index' varsayalım --}}
                    <li class="{{ Request::routeIs('frontend.blog.index') ? 'active' : '' }}">
                        <a href="{{ route('frontend.blog.index') }}">Blog</a>
                    </li>
                    {{-- İletişim için rota adı 'frontend.contact' varsayalım --}}
                    <li class="{{ Request::routeIs('frontend.contact') ? 'active' : '' }}"><a href="{{ route('frontend.contact') }}">İletişim</a></li>
                </ul>
            </div>

            <div id="search">
                <span class="close"></span>
                 {{-- Arama formu için bir rota (örn: frontend.search) tanımlanabilir --}}
                <form role="search" id="searchform" class="radius-xl" action="{{-- route('frontend.search') --}}" method="GET">
                    <div class="input-group">
                        <input value="{{ request('q') }}" name="q" type="search" placeholder="Ara">
                        <span class="input-group-btn"><button type="submit" class="search-btn"><i class="fa fa-search arrow-animation"></i></button></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>