{{-- Ana layout'u extend ediyoruz --}}
@extends('frontend.layouts.app')

{{-- Sayfa başlığını ayarlıyoruz --}}
@section('title', 'Hakkımızda - Ankara Tadilat ve Dekorasyon')

{{-- Meta etiketlerini ayarlıyoruz (Controller'dan $about verisi geliyorsa) --}}
@section('meta_keywords', $about?->meta_keywords ?? 'Ankara Tadilat, Hakkımızda, İnşaat') {{-- Örnek --}}
@section('meta_description', $about?->short_content ? Str::limit(strip_tags($about->short_content), 160) : 'Yıldız Mühendislik Mimarlık hakkında detaylı bilgi.')
{{-- Kısa içerikten alabiliriz --}}

{{-- Sayfa içeriğini @section('content') içine yerleştiriyoruz --}}
@section('content')

    {{-- Statik görsel için asset() --}}
    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5"
        style="background-image:url({{ asset('assets/images/banner/6.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        {{-- Başlık ve kısa içerik veritabanından ($about değişkeni) --}}
                        <h2 class="m-tb0">{{ $about->title ?? 'Hakkımızda' }}</h2>
                        {{-- Kısa içerik yoksa varsayılan metin gösterilebilir --}}
                        <span class="text-white">{{ $about->short_content ?? 'Yüksek kalite standartları ve müşteri odaklı yaklaşımımızla...' }}</span>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        {{-- Anasayfa linki için route() --}}
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li>Hakkımızda</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="section-full mobile-page-padding p-t80 p-b50 bg-gray">
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="about-home-3 m-b30 bg-white">
                            {{-- Ana içerik veritabanından ($about->content). HTML olarak basmak için {!! !!} --}}
                            {{-- Başlık için belki $about->title kullanılabilir veya statik kalabilir --}}
                            <h3 class="m-t0 m-b20 sx-tilte">{{ $about->title ?? 'İç Mekan Tasarımında Uzman Bir Ekip' }}
                            </h3>
                            {{-- Summernote içeriğini HTML olarak basıyoruz --}}
                            {!! $about->content ?? '<p>İlyas Yıldız İnşaat olarak...</p>' !!}

                            {{-- Bu liste statik kalabilir veya admin panelinden yönetilebilir --}}
                            <ul class="list-angle-right anchor-line">
                                <li><a href="#">İç Mekan 3D Modelleme Hizmetleri</a></li>
                                <li><a href="#">Kişisel Danışmanlık</a></li>
                                <li><a href="#">Yenilikçi İç Mekan Tasarımları</a></li>
                                <li><a href="#">Yüksek Kaliteli İç Mekan Hizmetleri</a></li>
                            </ul>

                            <div class="text-left">
                                {{-- Projeler sayfasına link --}}
                                <a href="{{ route('frontend.projects') }}"
                                    class="site-button btn-half"><span>PROJELERİMİZ</span></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="video-section-full-v2">
                            {{-- Statik görsel için asset() --}}
                            <div class="video-section-full bg-no-repeat bg-cover bg-center overlay-wraper m-b30"
                                style="background-image:url({{ asset('assets/images/video-bg.jpg') }})">
                                <div class="overlay-main bg-black opacity-04"></div>
                                <div class="video-section-inner">
                                    <div class="video-section-content">
                                        {{-- Video linki (Magnific Popup JS'i gerektirir) --}}
                                        <a href="https://player.vimeo.com/video/34741214?color=ffffff&title=0&byline=0&portrait=0"
                                            class="mfp-video play-now">
                                            <i class="icon fa fa-play"></i>
                                            <span class="ripple"></span>
                                        </a>
                                        <div class="video-section-bottom">
                                            {{-- Bu kısım statik kalabilir --}}
                                            <h3 class="sx-title text-white">12 Yıllık<br>Deneyim</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Arka plan görseli için asset() --}}
    <div class="section-full mobile-page-padding bg-white p-t80 p-b50 bg-repeat overflow-hide"
        style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
        <div class="container">
            <div class="section-head">
                <div class="sx-separator-outer separator-center">
                    {{-- Arka plan görseli için asset() --}}
                    <div class="sx-separator bg-white bg-moving bg-repeat-x"
                        style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                        <h3 class="sep-line-one">Neler Yapıyoruz?</h3>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="row number-block-three-outer justify-content-center">
                    {{-- Controller'dan gelen $services değişkenini @foreach ile döngüye alıyoruz --}}
                    @isset($services)
                        @foreach($services as $index => $service)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b30">
                                <div class="number-block-three slide-ani-top">
                                    <div class="sx-media">
                                        {{-- Service modelinde imageFullUrl accessor'ı varsa: --}}
                                        {{-- <img src="{{ $service->image_full_url }}" alt="{{ $service->title }}"> --}}
                                        {{-- Yoksa veya farklı boyut lazımsa: --}}
                                        @if($service->image_url)
                                            <img src="{{ asset('storage/service-images/400x300/' . $service->image_url) }}"
                                                alt="{{ $service->title }}">
                                        @else
                                            <img src="https://placehold.co/400x300/EFEFEF/AAAAAA&text=Gorsel+Yok"
                                                alt="{{ $service->title }}">
                                        @endif
                                    </div>
                                    <div class="figcaption bg-gray p-a30">
                                        {{-- Hizmetler sayfasına link eklenebilir --}}
                                        <h4 class="m-tb0">
                                        <a href="{{ route('frontend.services.detail', $service->slug) }}">{{ $service->title }}</a>
                                        </h4>
                                        <div class="figcaption-number animate-top-content">
                                            {{-- Döngü index'ini kullanarak numara oluşturma ($loop->iteration 1'den başlar) --}}
                                            <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </div>
    {{-- Arka plan görseli için asset() --}}
    <div class="section-full overlay-wraper sx-bg-secondry mobile-page-padding p-t80 p-b50 bg-parallax ml-auto"
        data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/background/bg-1.jpg') }})">
        <div class="overlay-main bg-black opacity-05"></div>
        <div class="container">
            <div class="section-content">
                {{-- Counter'lar statik, JS ile çalışır --}}
                <div class="counter-blocks">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 m-b30 ">
                            <div class="sx-count text-white sx-icon-box-wraper bg-repeat p-a30"
                                style="background-image:url({{ asset('assets/images/background/bg-5.png') }})">
                                <h2 class="m-t0 sx-text-primary text-right"><span class="counter">12</span></h2>
                                <h4 class="m-b0">Yıllık Deneyim</h4>
                            </div>
                        </div>
                        {{-- Diğer counter blokları --}}
                        <div class="col-xl-3 col-md-6 m-b30">
                            <div class="sx-count text-white sx-icon-box-wraper bg-repeat p-a30"
                                style="background-image:url({{ asset('assets/images/background/bg-5.png') }})">
                                <h2 class="m-t0 sx-text-primary text-right"><span class="counter">340</span></h2>
                                <h4 class="m-b0">Biten Proje</h4>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 m-b30">
                            <div class="sx-count text-white sx-icon-box-wraper bg-repeat p-a30"
                                style="background-image:url({{ asset('assets/images/background/bg-5.png') }})">
                                <h2 class="m-t0 sx-text-primary text-right"><span class="counter">16</span></h2>
                                <h4 class="m-b0">Personel</h4>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 m-b30">
                            <div class="sx-count text-white sx-icon-box-wraper bg-repeat p-a30"
                                style="background-image:url({{ asset('assets/images/background/bg-5.png') }})">
                                <h2 class="m-t0 sx-text-primary text-right"><span class="counter">2</span></h2>
                                <h4 class="m-b0">Güncel Projeler</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-full p-t80 p-b50 mobile-page-padding">
        <div class="container">
            <div class="section-head">
                <div class="sx-separator-outer separator-left">
                    {{-- Arka plan görseli için asset() --}}
                    <div class="sx-separator bg-white bg-moving bg-repeat-x"
                        style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                        <h3 class="sep-line-one">Teknik Ekibimiz</h3>
                    </div>
                </div>
            </div>
            <div class="section-content">
                {{-- Takım üyeleri statik kalabilir veya admin panelinden yönetilebilir --}}
                <div class="row team-item-four">
                    <div class="col-lg-3 col-md-6 col-sm-6 m-b30">
                        <div class="our-team-2 ">
                            <div class="profile-image">
                                <img src="{{ asset('assets/images/our-team5/1.jpg') }}" alt="">
                                {{-- Sosyal medya linkleri --}}
                            </div>
                            <div class="figcaption text-black">
                                <h4 class="m-t0"><a href="javascript:void(0);">İlyas Yıldız</a></h4>
                                <span class="m-b0">İnşaat Mühendisi</span>
                            </div>
                        </div>
                    </div>
                    {{-- Diğer takım üyeleri --}}
                    <div class="col-lg-3 col-md-6 col-sm-6 m-b30   ">
                        <div class="our-team-2 ">
                            <div class="profile-image">
                                <img src="{{ asset('assets/images/our-team5/2.jpg') }}" alt="">
                                {{-- Icons --}}
                            </div>
                            <div class="figcaption text-black">
                                <h4 class="m-t0"><a href="javascript:void(0);">Tekin Bakır</a></h4>
                                <span class="m-b0">İç Mimar </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 m-b30">
                        <div class="our-team-2 ">
                            <div class="profile-image">
                                <img src="{{ asset('assets/images/our-team5/3.jpg') }}" alt="">
                                {{-- Icons --}}
                            </div>
                            <div class="figcaption text-black">
                                <h4 class="m-t0"><a href="javascript:void(0);">Murat Koçak</a></h4>
                                <span class="m-b0">Mimar</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 m-b30   ">
                        <div class="our-team-2">
                            <div class="profile-image">
                                <img src="{{ asset('assets/images/our-team5/4.jpg') }}" alt="">
                                {{-- Icons --}}
                            </div>
                            <div class="figcaption text-black">
                                <h4 class="m-t0"><a href="javascript:void(0);">Ömer Yıldız</a></h4>
                                <span class="m-b0">Mimar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection