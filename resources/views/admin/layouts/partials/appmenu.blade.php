<!-- LOGO -->
<div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="{{ url('/') }}" class="logo logo-dark">
        <span class="logo-sm">
            <img src="{{ asset('admin/images/enderunlogodisi.png') }}" alt="" height="25">
        </span>
        <span class="logo-lg">
            <img src="{{ asset('admin/images/enderunlogodisi.png') }}" alt="" height="70">
        </span>
    </a>
    <!-- Light Logo-->
    <a href="{{ url('/') }}" class="logo logo-light">
        <span class="logo-sm">
            <img src="{{ asset('admin/images/enderunlogodisi.png') }}" alt="" height="25">
        </span>
        <span class="logo-lg">
            <img src="{{ asset('admin/images/enderunlogodisi.png') }}" alt="" height="70">
        </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
        <i class="ri-record-circle-line"></i>
    </button>
</div>

<div id="scrollbar" class="position-relative">
    <div class="container-fluid">

        <div id="two-column-menu"></div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title"><span data-key="t-menu">Menu</span></li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/dashboard') }}">
                    <i class="mdi mdi-home"></i> <span data-key="t-widgets">Anasayfa</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/admin/categories') }}">
                    <i class="mdi mdi-tag-outline"></i> <span data-key="t-widgets">Blog Kategorileri</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/admin/galleries') }}">
                    <i class="mdi mdi-image"></i> <span data-key="t-widgets">Galeri</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/admin/blogs') }}">
                    <i class="mdi mdi-newspaper"></i> <span data-key="t-widgets">Blog</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('admin.abouts.index') }}">
                    <i class="mdi mdi-information-outline"></i> <span data-key="t-widgets">Hakkımızda</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('admin.services.index') }}">
                    <i class="mdi mdi-toolbox-outline"></i> <span data-key="t-widgets">Hizmetler</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('admin.projects.index') }}">
                    <i class="mdi mdi-briefcase-variant-outline"></i> <span data-key="t-widgets">Projeler</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/admin/authors') }}">
                    <i class="mdi mdi-account-tie"></i> <span data-key="t-widgets">Köşe Yazarları</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/admin/settings') }}">
                    <i class="mdi mdi-cog"></i> <span data-key="t-widgets">Ayarlar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('admin.slides.index') }}">
                    <i class="mdi mdi-image-multiple-outline"></i> <span data-key="t-widgets">Slide Yönetimi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ route('admin.references.index') }}">
                    <i class="mdi mdi-account-group"></i> <span data-key="t-widgets">Referans Yönetimi</span>
                </a>
            </li>
            {{-- <li class="nav-item">--}}
                {{-- <a class="nav-link menu-link" href="{{ url('/admin/products') }}">--}}
                    {{-- <i class="mdi mdi-view-grid-outline"></i> <span data-key="t-widgets">Ürünler</span>--}}
                    {{-- </a>--}}
                {{-- </li>--}}
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{ url('/') }}" target="_blank">
                    <i class="mdi mdi-web"></i> <span data-key="t-widgets">Siteye Git</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer text-center text-white">
        Enderun AI CMS (V.1.1)
    </div>
</div>


<div class="sidebar-background">
</div>