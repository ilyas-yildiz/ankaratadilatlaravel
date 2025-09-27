<header class="site-header nav-wide nav-transparent mobile-sider-drawer-menu">
    <div class="sticky-header main-bar-wraper navbar-expand-lg">
        <div class="main-bar">

            <div class="container clearfix">
                <div class="logo-header">
                    <div class="logo-header-inner logo-header-one">
                        <a href="{{ route('frontend.home') }}">
                            <img src="{{ asset('storage/images/yildizlogo.png') }}" alt="">
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
                        <li class="active">
                            <a href="{{ route('frontend.home') }}">Anasayfa</a>
                        </li>

                        <li>
                            <a href="{{ route('frontend.about') }}">Hakkımızda</a>
                        </li>

                        <li>
                            <a href="{{ route('frontend.service') }}">Hizmetlerimiz</a>
                        </li>

                        <li>
                            <a href="{{ route('frontend.project') }}">Projelerimiz</a>
                        </li>

                        <li>
                            <a href="{{ route('frontend.blog') }}">Blog</a>
                        </li>

                        <li><a href="{{ route('frontend.contact') }}">İletişim</a></li>

                    </ul>
                </div>


                <div id="search">
                    <span class="close"></span>
                    <form role="search" id="searchform" class="radius-xl">
                        <div class="input-group">
                            <input value="" name="q" type="search" placeholder="Ara">
                            <span class="input-group-btn"><button type="button" class="search-btn"><i class="fa fa-search arrow-animation"></i></button></span>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</header>
