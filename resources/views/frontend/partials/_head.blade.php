<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

{{-- META Keywords & Description (Bunları Controller'dan dinamik olarak alacağız) --}}
{{-- Şimdilik placeholder olarak bırakalım veya statik yazalım --}}
<meta name="keywords" content="@yield('meta_keywords', 'Ankara Tadilat, Dekorasyon, İnşaat, İç Mimarlık')">
<meta name="description"
    content="@yield('meta_description', 'Ankara Tadilat ve Dekorasyon hizmetleri. Yıldız Mühendislik Mimarlık.')">

{{-- asset() helper'ı public klasörüne işaret eder --}}
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
<link rel="me" href="https://sosyal.teknofest.app/@ankaratadilat">
{{-- Sayfa başlığını dinamik hale getirelim --}}
<title>@yield('title', 'Ankara Tadilat ve Dekorasyon')</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- CSS Dosyaları (base_url yerine asset()) --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/magnific-popup.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/loader.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flaticon.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-slider.min.css') }}">
<link rel="stylesheet" class="skin" type="text/css" href="{{ asset('assets/css/skin/skin-1.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/switcher.css') }}">

{{-- Font URL'si doğrudan kullanılabilir --}}
<link
    href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
    rel="stylesheet">

{{-- Her sayfaya özel ek CSS dosyaları için alan --}}
@stack('custom-styles')