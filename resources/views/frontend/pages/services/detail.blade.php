@extends('frontend.layouts.app')

{{-- Sayfa başlığını hizmet başlığı ile ayarlıyoruz --}}
@section('title', $service->title . ' - Hizmetlerimiz') 

{{-- Meta açıklamasını hizmet içeriğinden alalım (kısaltılmış ve HTML temizlenmiş) --}}
@section('meta_description', Str::limit(strip_tags($service->content), 160)) 

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/10.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                         {{-- Hizmet başlığı --}}
                        <h2 class="m-tb0">{{ $service->title }}</h2>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li><a href="{{ route('frontend.services') }}">Hizmetlerimiz</a></li>
                        <li>{{ $service->title }}</li> {{-- Aktif sayfa adı --}}
                    </ul>
                </div>
                </div>
        </div>
    </div>
    <div class="section-full p-t80 p-b50 inner-page-padding">
        <div class="container">
            {{-- Kenar boşlukları için (max-w900 ml-auto mr-auto) --}}
            <div class="blog-single-space max-w900 ml-auto mr-auto"> 
                <div class="blog-post blog-detail text-black">
                    {{-- Ana Görsel --}}
                    <div class="sx-post-media m-b30"> 
                         @if($service->image_url)
                             {{-- Büyük görsel boyutu (örn: 800x600) --}}
                            <img class="img-responsive" src="{{ asset('storage/service-images/800x600/' . $service->image_url) }}" alt="{{ $service->title }}">
                         @else
                            {{-- İsteğe bağlı: Görsel yoksa placeholder gösterme --}}
                         @endif
                    </div>

                    {{-- Meta (Tarih - İsteğe bağlı) --}}
                    {{-- <div class="sx-post-meta m-t20">
                        <ul>
                            <li class="post-date"><span>{{ $service->created_at->translatedFormat('d F Y') }}</span></li>
                        </ul>
                    </div> --}}

                    {{-- Hizmet İçeriği (Summernote'tan geldiği için HTML olarak basılır) --}}
                    <div class="sx-post-text">
                        {!! $service->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection