<!DOCTYPE html>
<html dir="ltr" lang="tr">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D5GDFZ4RLW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-D5GDFZ4RLW');
    </script>
    <meta charset="utf-g">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Dinamik Sayfa Başlığı: Her sayfa kendi başlığını buraya yazacak --}}
    <title>@yield('title', 'Memur Hukuk Haber')</title>

    <meta name="author" content="Enderun Digital">
    {{-- Eğer bir alt sayfa 'meta_description' section'ı tanımlamazsa, varsayılan içerik kullanılır. --}}
    <meta name="description" content="@yield('meta_description', 'Memur, polis ve asker hukuku hakkında en güncel haberler ve makaleler.')">
    <meta name="keywords" content="@yield('meta_keywords', 'memur hukuk, polis hukuk, asker hukuk, gündem')">

    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('theme/img/favicon.png') }}" type="image/png">

    <!-- CSS Linkleri -->
    {{-- NOT: CSS ve JS dosyalarınızı public/frontend klasörü altına taşımanız gerekmektedir --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700">
    <link rel="stylesheet" href="{{ asset('theme/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/fontawesome-stars-o.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/style.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/responsive-style.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/colors/theme-color-1.css') }}" id="changeColorScheme">
    <link rel="stylesheet" href="{{ asset('theme/css/custom.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div id="preloader">
    <div class="preloader bg--color-1--b" data-preloader="1">
        <div class="preloader--inner"></div>
    </div>
</div>

<div class="wrapper">
    {{-- Header (Üst Kısım) buradan dahil ediliyor --}}
    @include('frontend.partials._header')

    {{-- Her sayfanın kendi özel içeriği bu alana gelecek --}}
    @yield('content')

    {{-- Footer (Alt Kısım) buradan dahil ediliyor --}}
    @include('frontend.partials._footer')
</div>

{{-- Site geneli yardımcı elementler --}}
<div id="stickySocial" class="sticky--right">
    {{-- ... (içerik aynı) ... --}}
</div>
<div id="backToTop"><a href="#"><i class="fa fa-angle-double-up"></i></a></div>

@include('frontend.partials._include_script')

</body>
</html>

