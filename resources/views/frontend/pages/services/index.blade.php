@extends('frontend.layouts.app')

@section('title', 'Hizmetlerimiz - Ankara Tadilat ve Dekorasyon')
{{-- Meta etiketleri için varsayılanlar kullanılabilir veya statik yazılabilir --}}

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/6.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        <h2 class="m-tb0">Hizmetlerimiz</h2>
                        {{-- Bu metin statik kalabilir veya Ayarlar'dan çekilebilir --}}
                        <span class="text-white">Yüksek kalite standartları ve müşteri odaklı yaklaşımımızla her projede fark yaratıyoruz...</span>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li>Hizmetlerimiz</li>
                    </ul>
                </div>
                </div>
        </div>
    </div>
    <div class="section-full mobile-page-padding bg-white p-t80 p-b50 bg-repeat overflow-hide" style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
        <div class="container">
            <div class="section-head">
                <div class="sx-separator-outer separator-center">
                    <div class="sx-separator bg-white bg-moving bg-repeat-x" style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                        <h3 class="sep-line-one">Neler Yapıyoruz?</h3>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="row number-block-three-outer justify-content-center">
                    {{-- Controller'dan gelen $services değişkeni --}}
                    @forelse($services as $service)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 m-b30">
                            <div class="number-block-three slide-ani-top">
                                <div class="sx-media">
                                    {{-- Detay sayfasına link --}}
                                    <a href="{{ route('frontend.services.detail', $service->slug) }}">
                                        @if($service->image_url)
                                            {{-- Uygun görsel boyutu (örn: 400x300) --}}
                                            <img src="{{ asset('storage/service-images/400x300/' . $service->image_url) }}" alt="{{ $service->title }}">
                                        @else
                                            <img src="https://placehold.co/400x300/EFEFEF/AAAAAA&text=Gorsel+Yok" alt="{{ $service->title }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="figcaption bg-gray p-a30">
                                    <h4 class="m-tb0">
                                        <a href="{{ route('frontend.services.detail', $service->slug) }}">
                                            {{ $service->title }}
                                        </a>
                                    </h4>
                                    <div class="figcaption-number animate-top-content">
                                        {{-- Numara için $loop->iteration --}}
                                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">Gösterilecek hizmet bulunamadı.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endsection