<header class="header--section header--style-1">
    {{-- En Üst Bar --}}
    <div class="header--topbar bg--color-2">
        <div class="container">
            <div class="float--left float--xs-none text-xs-center">
                <ul class="header--topbar-info nav">
                    {{-- Tarihi dinamik olarak Türkçe formatında gösteriyoruz --}}
                    <li><i class="fa fm fa-calendar"></i>Bugün ({{ now()->translatedFormat('l, d F Y') }})</li>
                </ul>
            </div>
            <div class="float--right float--xs-none text-xs-center">
                {{-- Kullanıcı giriş yapmışsa Admin Paneli, yapmamışsa Giriş Yap linki gösterilecek --}}
{{--                <ul class="header--topbar-action nav">--}}
{{--                    @guest--}}
{{--                        <li><a href="{{ route('login') }}"><i class="fa fm fa-user-o"></i>Giriş Yap / Kayıt Ol</a></li>--}}
{{--                    @else--}}
{{--                        <li><a href="{{ route('dashboard') }}"><i class="fa fm fa-dashboard"></i>Admin Paneli</a></li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('logout') }}"--}}
{{--                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                                <i class="fa fm fa-sign-out"></i>Çıkış Yap--}}
{{--                            </a>--}}
{{--                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
{{--                                @csrf--}}
{{--                            </form>--}}
{{--                        </li>--}}
{{--                    @endguest--}}
{{--                </ul>--}}
                <ul class="header--topbar-social nav hidden-sm hidden-xxs">
                    <li><a href="{{ $settings['social_facebook'] }}"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="{{ $settings['social_twitter'] }}"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="{{ $settings['social_instagram'] }}"><i class="fa fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Logo ve Reklam Alanı --}}
    <div class="header--mainbar">
        <div class="container">
            <div class="header--logo float--left float--sm-none text-sm-center">
                <h1 class="h1">
                    <a href="/" class="btn-link">
                        <img src="{{ asset('assets/img/memurhukuklogo1.png') }}" alt="{{ $settings['company_name'] ?? 'Site Logosu' }}" width="200">
                    </a>
                </h1>
            </div>

        </div>
    </div>

    {{-- Ana Navigasyon Menüsü --}}
    <div class="header--navbar style--1 navbar bd--color-1 bg--color-1" data-trigger="sticky">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#headerNav" aria-expanded="false" aria-controls="headerNav">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="headerNav" class="navbar-collapse collapse float--left">
                <ul class="header--menu-links nav navbar-nav" data-trigger="hoverIntent">
                    <li><a href="/">Anasayfa</a></li>

                    @foreach($categories as $category)
                        <li><a href="/kategori/{{ $category->slug }}">{{ $category->name }}</a></li>
                    @endforeach

                    {{-- YAZARLAR SAYFASI LİNKİ --}}
                    {{-- Bu rota da bir sonraki adımda tanımlanacak --}}
                    <li><a href="/yazarlar">Yazarlar</a></li>
                </ul>
            </div>
            <form action="#" class="header--search-form float--right" data-form="validate">
                <input type="search" name="search" placeholder="Ara..." class="header--search-control form-control" required>
                <button type="submit" class="header--search-btn btn"><i class="header--search-icon fa fa-search"></i></button>
            </form>
        </div>
    </div>
</header>

