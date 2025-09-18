@extends('frontend.layouts.app')

@section('title', 'Anasayfa')

@section('content')

    {{-- Haber Kaydırma Bandı --}}
    <div class="news--ticker">
        <div class="container">
            <div class="title"><h2>Son Dakika</h2></div>
            <div class="news-updates--list" data-marquee="true">
                <ul class="nav">
                    {{-- Controller'dan gelen her son dakika haberi için bir döngü oluştur --}}
                    @forelse ($tickerBlogs as $tickerBlog)
                        <li>
                            <h3 class="h3">
                                <a href="{{ route('frontend.blog.detail', $tickerBlog->slug) }}">
                                    {{ $tickerBlog->title }}
                                </a>
                            </h3>
                        </li>
                    @empty
                        {{-- Eğer hiç haber bulunamazsa bu mesajı göster --}}
                        <li>
                            <h3 class="h3"><a>Şu anda gösterilecek son dakika haberi bulunmamaktadır.</a></h3>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Ana İçerik Alanı --}}
    <div class="main-content--section pbottom--30">
        <div class="container">
            <div class="main--content">
                {{-- MANŞET ALANI (1 BÜYÜK, 4 KÜÇÜK HABER) --}}
                <div class="post--items post--items-1 pd--30-0">
                    <div class="row gutter--15">

                        {{-- Veritabanında manşet haberi varsa bu bölümü göster --}}
                        @if ($mainFeatured)
                            {{-- BÜYÜK MANŞET (SOL TARAF) --}}
                            <div class="col-md-6">
                                <div class="post--item post--layout-1 post--title-larger">
                                    <div class="post--img">
                                        <a href="{{ route('frontend.blog.detail', $mainFeatured->slug) }}" class="thumb">
                                            {{-- Büyük manşet için büyük boyutlu resmi kullanıyoruz --}}
                                            <img src="{{ asset('storage/blog-images/562x395/' . $mainFeatured->image_url) }}" alt="{{ $mainFeatured->title }}">
                                        </a>
                                        <a href="{{ route('frontend.category', $mainFeatured->category->slug) }}" class="cat">{{ $mainFeatured->category->name }}</a>
                                        <div class="post--info">
                                            <ul class="nav meta">
                                                <li><a href="#">{{ $mainFeatured->user?->name ?? 'Editör' }}</a></li>
                                                <li><a href="#">{{ $mainFeatured->created_at->translatedFormat('d F Y') }}</a></li>
                                            </ul>
                                            <div class="title">
                                                <h2 class="h4"><a href="{{ route('frontend.blog.detail', $mainFeatured->slug) }}" class="btn-link">{{ $mainFeatured->title }}</a></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KÜÇÜK MANŞETLER (SAĞ TARAF) --}}
                            <div class="col-md-6">
                                <div class="row gutter--15">
                                    {{-- Veritabanından gelen her küçük manşet için bir döngü oluştur --}}
                                    @if ($subFeatured->isNotEmpty())
                                        @foreach ($subFeatured as $subBlog)
                                            <div class="col-xs-6 col-xss-12">
                                                <div class="post--item post--layout-1 post--title-large">
                                                    <div class="post--img">
                                                        <a href="{{ route('frontend.blog.detail', $subBlog->slug) }}" class="thumb">
                                                            {{-- Küçük manşetler için daha küçük boyutlu resmi kullanıyoruz --}}
                                                            <img src="{{ asset('storage/blog-images/274x183/' . $subBlog->image_url) }}" alt="{{ $subBlog->title }}">
                                                        </a>
                                                        <div class="post--info">
                                                            <ul class="nav meta">
                                                                <li><a href="#">{{ $subBlog->created_at->translatedFormat('d F Y') }}</a></li>
                                                            </ul>
                                                            <div class="title">
                                                                <h2 class="h4"><a href="{{ route('frontend.blog.detail', $subBlog->slug) }}" class="btn-link">{{ Str::limit($subBlog->title, 55) }}</a></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- Eğer hiç manşet haberi bulunamazsa --}}
                            <div class="col-md-12">
                                <p class="text-center">Şu anda gösterilecek manşet haberi bulunmamaktadır.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="row">
                {{-- ANA HABER AKIŞI (SOL TARAF) --}}
                {{-- ANA HABER AKIŞI (SOL TARAF) --}}
                <div class="main--content col-md-8 col-sm-7" data-sticky-content="true">
                    <div class="sticky-content-inner">

                        {{-- Controller'dan gelen ve 2'li gruplara ayrılmış kategoriler için bir döngü başlat --}}
                        @if ($categoryChunks->isNotEmpty())
                            @foreach ($categoryChunks as $chunk)
                                <div class="row">
                                    {{-- Her bir 2'li grup içindeki kategoriler için bir döngü daha başlat --}}
                                    @foreach ($chunk as $category)
                                        <div class="col-md-6 ptop--30 pbottom--30">
                                            <div class="post--items-title" data-ajax="tab">
                                                <h2 class="h4">{{ $category->name }}</h2>
                                                <div class="nav">
                                                    <a href="{{ route('frontend.category', $category->slug) }}" class="prev btn-link">
                                                        Tümünü Gör <i class="fa fa-long-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="post--items post--items-2" data-ajax-content="outer">
                                                <ul class="nav row gutter--15" data-ajax-content="inner">
                                                    @php
                                                        // Kategoriye ait 5 haberden ilkini ana haber, kalanları alt haberler olarak ayır
                                                        $firstBlog = $category->blogs->first();
                                                        $otherBlogs = $category->blogs->slice(1);
                                                    @endphp

                                                    {{-- KATEGORİNİN ANA (BÜYÜK) HABERİ --}}
                                                    @if ($firstBlog)
                                                        <li class="col-xs-12">
                                                            <div class="post--item post--layout-1">
                                                                <div class="post--img">
                                                                    <a href="{{ route('frontend.blog.detail', $firstBlog->slug) }}" class="thumb">
                                                                        <img src="{{ asset('storage/blog-images/562x395/' . $firstBlog->image_url) }}" alt="{{ $firstBlog->title }}">
                                                                    </a>
                                                                    <a href="{{ route('frontend.category', $firstBlog->category->slug) }}" class="cat">{{ $firstBlog->category->name }}</a>
                                                                    <div class="post--info">
                                                                        <ul class="nav meta">
                                                                            <li><a href="#">{{ $firstBlog->user?->name ?? 'Editör' }}</a></li>
                                                                            <li><a href="#">{{ $firstBlog->created_at->translatedFormat('d F Y') }}</a></li>
                                                                        </ul>
                                                                        <div class="title">
                                                                            <h3 class="h4"><a href="{{ route('frontend.blog.detail', $firstBlog->slug) }}" class="btn-link">{{ $firstBlog->title }}</a></h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="col-xs-12"><hr class="divider"></li>
                                                    @endif

                                                    {{-- KATEGORİNİN DİĞER (KÜÇÜK) HABERLERİ --}}
                                                    @if ($otherBlogs->isNotEmpty())
                                                        @foreach ($otherBlogs as $blog)
                                                            <li class="col-xs-6">
                                                                <div class="post--item post--layout-2">
                                                                    <div class="post--img">
                                                                        <a href="{{ route('frontend.blog.detail', $blog->slug) }}" class="thumb">
                                                                            <img src="{{ asset('storage/blog-images/274x183/' . $blog->image_url) }}" alt="{{ $blog->title }}">
                                                                        </a>
                                                                        <div class="post--info">
                                                                            <ul class="nav meta">
                                                                                <li><a href="#">{{ $blog->created_at->translatedFormat('d F') }}</a></li>
                                                                            </ul>
                                                                            <div class="title">
                                                                                <h3 class="h4"><a href="{{ route('frontend.blog.detail', $blog->slug) }}" class="btn-link">{{ Str::limit($blog->title, 45) }}</a></h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>


                {{-- YAN BAR (SAĞ TARAF) --}}
                <div class="main--sidebar col-md-4 col-sm-5 ptop--30 pbottom--30" data-sticky-content="true">
                    <div class="sticky-content-inner">
                        {{-- Bu alan temanın widget'larını içerir. Şimdilik statik bırakılmıştır. --}}
                        <div class="widget" style="margin-top: 0!important;">
                            <div class="widget--title">
                                <h2 class="h4">Sosyal Medyada Biz</h2>
                                <i class="icon fa fa-share-alt"></i>
                            </div>
                            <div class="social--widget style--1">
                                <ul class="nav">
                                    <li class="facebook">
                                        <a href="{{ $settings['social_facebook'] }}">
                                            <span class="icon"><i class="fa fa-facebook-f"></i></span>
                                        </a>
                                    </li>
                                    <li class="twitter">
                                        <a href="{{ $settings['social_twitter'] }}">
                                            <span class="icon"><i class="fa fa-twitter"></i></span>
                                        </a>
                                    </li>
                                    <li class="youtube">
                                        <a href="{{ $settings['social_instagram'] }}">
                                            <span class="icon"><i class="fa fa-instagram"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget--title">
                                <h2 class="h4">E-Bülten Üyeliği</h2>
                                <i class="icon fa fa-envelope-open-o"></i>
                            </div>
                            <div class="subscribe--widget">
                                <div class="content">
                                    <p>Gündeme ilişkin haberlerimizden haberdar olmak için kayıt olabilirsiniz.</p>
                                </div>
                                <form action="https://themelooks.us13.list-manage.com/subscribe/post?u=79f0b132ec25ee223bb41835f&amp;id=f4e0e93d1d" method="post" name="mc-embedded-subscribe-form" target="_blank" data-form="mailchimpAjax" novalidate="novalidate">
                                    <div class="input-group">
                                        <input type="email" name="EMAIL" placeholder="E-Mail Adresiniz" class="form-control" autocomplete="off" required="" aria-required="true">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-lg btn-default active"><i class="fa fa-paper-plane-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="status"></div>
                                </form>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget--title">
                                <h2 class="h4">Köşe Yazarları</h2>
                                <i class="icon fa fa-pencil"></i>
                            </div>
                            <div class="list--widget list--widget-2">
                                <div class="post--items post--items-3">
                                    <ul class="nav">
                                        {{-- Controller'dan gelen her yazar için bir döngü oluştur --}}
                                        @forelse ($sidebarAuthors as $author)
                                            <li>
                                                <a href="{{ route('frontend.author.detail', $author->slug) }}">
                                                    <div class="post--item post--layout-3">
                                                        <div class="post--img">
                                            <span class="thumb">
                                                {{-- Yazarın küçük boyutlu görselini göster --}}
                                                <img src="{{ $author->img_url ? asset('storage/authors/100x100/' . $author->img_url) : 'https://placehold.co/90x90/EFEFEF/AAAAAA&text=Yazar' }}" alt="{{ $author->title }}">
                                            </span>
                                                            <div class="post--info">
                                                                <div class="title">
                                                                    <h3 class="h4">{{ $author->title }}</h3>
                                                                </div>
                                                                <ul class="nav meta">
                                                                    {{-- Açıklamadan kısa bir bölüm göster --}}
                                                                    <li><span>{{ Str::limit(html_entity_decode(strip_tags($author->description)), 50) }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @empty
                                            {{-- Eğer hiç yazar bulunamazsa bu mesajı göster --}}
                                            <li>
                                                <p class="text-center">Henüz yazar eklenmemiş.</p>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

