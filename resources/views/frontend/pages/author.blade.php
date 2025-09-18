@extends('frontend.layouts.app')

@section('title', 'Yazarlarımız')

@section('content')
    {{-- Breadcrumb (Sayfa Yolu) Alanı --}}
    <div class="main--breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('frontend.home') }}" class="btn-link"><i class="fa fm fa-home"></i>Anasayfa</a></li>
                <li class="active"><span>Yazarlar</span></li>
            </ul>
        </div>
    </div>

    <div class="main-content--section pbottom--30">
        <div class="container">
            <div class="main--content">
                {{-- Sayfa Başlığı --}}
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="page--title pd--30-0 text-center">
                            <h2 class="h2">Yazarlarımız</h2>
                            <div class="content">
                                <p>Sitemizde yer alan değerli köşe yazarlarımızın yazılarına buradan ulaşabilirsiniz.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Veritabanından gelen yazarlar listesi --}}
                @if($authors->isNotEmpty())
                    <div class="contributor--items ptop--30">
                        <ul class="nav row AdjustRow">
                            {{-- Controller'dan gelen her yazar için bir döngü oluştur --}}
                            @foreach ($authors as $author)
                                <li class="col-md-3 col-xs-6 col-xxs-12 pbottom--30">
                                    <div class="contributor--item style--4">
                                        <div class="img">
                                            {{-- Yazarın 300x300 boyutundaki görselini göster --}}
                                            <img src="{{ $author->img_url ? asset('storage/authors/263x272/' . $author->img_url) : 'https://placehold.co/263x272/EFEFEF/AAAAAA&text=Görsel+Yok' }}" alt="{{ $author->title }}">
                                        </div>
                                        <div class="info">
                                            <div class="vc--parent">
                                                <div class="vc--child">
                                                    {{-- Yazarın Adı Soyadı --}}
                                                    <div class="name"><h3 class="h4">{{ $author->title }}</h3></div>
                                                    {{-- Yazarın Biyografisinden kısa bir bölüm --}}
                                                    <div class="desc"><p>{{ Str::limit(html_entity_decode(strip_tags($author->description)), 50) }}</p></div>
                                                    {{-- Sosyal Medya Linkleri (Şimdilik statik) --}}
                                                    <ul class="social nav">
                                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                    </ul>
                                                    {{-- Yazarın Detay Sayfasına Giden Link --}}
                                                    <div class="action">
                                                        <a href="{{ route('frontend.author.detail', $author->slug) }}" class="btn btn-default">Yazarın Yazıları</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    {{-- Eğer hiç yazar bulunamazsa bu mesajı göster --}}
                    <div class="text-center ptop--30">
                        <p>Henüz sisteme eklenmiş bir yazar bulunmamaktadır.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
