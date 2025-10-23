@extends('frontend.layouts.app')

@section('title', 'İletişim - Ankara Tadilat ve Dekorasyon')
{{-- Meta etiketleri --}}

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/9.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        <h2 class="m-tb0">İletişim</h2>
                        <span class="text-white">Hayalinizdeki yaşam alanı için ilk adımı birlikte atalım.</span>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li>İletişim</li>
                    </ul>
                </div>
                </div>
        </div>
    </div>
    <div class="section-full p-tb80 inner-page-padding">
        <div class="container">
            <div class="section-content">
                <div class="row">
                    {{-- İletişim Formu (Sol Kolon) --}}
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        {{-- Form action ve method güncellendi, @csrf eklendi --}}
                        <form class="contact-form bg-gray p-a30" method="POST" action="{{ route('frontend.contact.submit') }}">
                            @csrf {{-- CSRF koruması --}}
                            <div class="contact-one">
                                <div class="section-head">
                                    <div class="sx-separator-outer separator-left">
                                        <div class="sx-separator bg-white bg-moving bg-repeat-x" style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                                            <h3 class="sep-line-one">İletişim Formu</h3>
                                        </div>
                                    </div>
                                </div>
                                {{-- Başarı/Hata Mesajları --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                {{-- Laravel Validation Hataları --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif


                                <div class="form-group">
                                    <input name="username" type="text" required class="form-control @error('username') is-invalid @enderror" placeholder="Adınız Soyadınız" value="{{ old('username') }}">
                                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" required placeholder="E-posta Adresiniz" value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" required placeholder="Mesajınız">{{ old('message') }}</textarea>
                                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="text-right">
                                    <button name="submit" type="submit" value="Submit" class="site-button btn-half">
                                        <span> GÖNDER</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- İletişim Bilgileri (Sağ Kolon) --}}
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="contact-info block-shadow bg-white bg-center p-a40" style="background-image:url({{ asset('assets/images/background/bg-map.png') }})">
                            <div>
                                <div class="section-head">
                                    <div class="sx-separator-outer separator-left">
                                        <div class="sx-separator bg-white bg-moving bg-repeat-x" style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                                            <h3 class="sep-line-one">İletişim Bilgileri</h3>
                                        </div>
                                    </div>
                                </div>
                                {{-- Ayarlar Controller'dan $settings olarak geldi (View::share ile) --}}
                                <div class="sx-icon-box-wraper left p-b30">
                                    <div class="icon-xs"><i class="fa fa-phone"></i></div>
                                    <div class="icon-content">
                                        <h5 class="m-t0">Telefon</h5>
                                        {{-- Ayarlardan 'phone' anahtarını al, yoksa '-' yaz --}}
                                        <p>{{ $settings['phone'] ?? '-' }}</p> 
                                        {{-- İkinci telefon varsa --}}
                                        {{-- <p>{{ $settings['phone_2'] ?? '' }}</p> --}}
                                    </div>
                                </div>
                                <div class="sx-icon-box-wraper left p-b30">
                                    <div class="icon-xs"><i class="fa fa-envelope"></i></div>
                                    <div class="icon-content">
                                        <h5 class="m-t0">E-posta</h5>
                                        <p>{{ $settings['email'] ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="sx-icon-box-wraper left">
                                    <div class="icon-xs"><i class="fa fa-map-marker"></i></div>
                                    <div class="icon-content">
                                        <h5 class="m-t0">Adres</h5>
                                        <p>{{ $settings['address'] ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- Google Harita Alanı (Boş) --}}
    <div class="gmap-outline">
        {{-- Buraya Google Maps iframe kodu veya JS API entegrasyonu eklenebilir --}}
        {{-- Örnek iframe (Ayarlardan 'google_map_iframe' anahtarıyla çekilebilir): --}}
        {{-- {!! $settings['google_map_iframe'] ?? '' !!} --}}
    </div>

@endsection

@push('custom-scripts')
<script>
    // İletişim sayfası için özel JS kodları (varsa) buraya eklenebilir.
</script>
@endpush