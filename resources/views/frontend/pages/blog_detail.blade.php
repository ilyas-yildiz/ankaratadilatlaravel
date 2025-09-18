@extends('frontend.layouts.app')

{{-- Sayfa başlığını dinamik olarak haber başlığıyla dolduruyoruz --}}
@section('title', $blog->title)
{{-- 2. Meta description alanını dolduruyoruz. --}}
@section('meta_description')
    @php
        // Önce gösterilecek metni belirliyoruz: ya veritabanındaki özel açıklama ya da içerikten oluşturulan özet.
        $descriptionText = $blog->meta_description
            ? html_entity_decode($blog->meta_description)
            : Str::limit(strip_tags(html_entity_decode($blog->content)), 160);

        // HTML attribute'ünü bozabilecek çift tırnakları güvenli hale getirirken, diğerlerini olduğu gibi bırakıyoruz.
        $safeDescription = str_replace('"', '&quot;', $descriptionText);
    @endphp
    {!! $safeDescription !!}
@endsection
@section('meta_keywords', $blog->meta_keywords ?? 'memur hukuk, makale, blog')
@section('content')
    {{-- Breadcrumb (Sayfa Yolu) Alanı --}}
    <div class="main--breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('frontend.home') }}" class="btn-link"><i class="fa fm fa-home"></i>Anasayfa</a></li>
                <li><a href="{{ route('frontend.category', $blog->category->slug) }}" class="btn-link">{{ $blog->category->name }}</a></li>
                <li class="active"><span>{{ Str::limit($blog->title, 40) }}</span></li>
            </ul>
        </div>
    </div>

    <div class="main-content--section pbottom--30">
        <div class="container">
            <div class="row">
                {{-- Ana Haber İçeriği (Sol Taraf) --}}
                <div class="main--content col-md-8" data-sticky-content="true">
                    <div class="sticky-content-inner">
                        <div class="post--item post--single post--title-largest pd--30-0">
                            {{-- Kategori Bilgisi --}}
                            <div class="post--cats">
                                <ul class="nav">
                                    <li><span><i class="fa fa-folder-open-o"></i></span></li>
                                    <li><a href="{{ route('frontend.category', $blog->category->slug) }}">{{ $blog->category->name }}</a></li>
                                </ul>
                            </div>

                            {{-- Meta Bilgiler (Yazar, Tarih vb.) --}}
                            <div class="post--info">
                                <ul class="nav meta">
                                    {{-- Eğer bir yazar atanmışsa yazarın linkini, atanmamışsa admin kullanıcısının adını göster --}}
                                    <li><a href="{{ $blog->author ? route('frontend.author.detail', $blog->author->slug) : '#' }}">{{ $blog->author?->title ?? $blog->user?->name ?? 'Editör' }}</a></li>
                                    <li><a href="#">{{ $blog->created_at->translatedFormat('d F Y') }}</a></li>
                                    {{-- Okunma ve yorum sayıları için placeholder --}}
                                    <li><span><i class="fa fm fa-eye"></i>-</span></li>
                                    <li><a href="#"><i class="fa fm fa-comments-o"></i>-</a></li>
                                </ul>
                                <div class="title">
                                    <h2 class="h4">{{ $blog->title }}</h2>
                                </div>
                            </div>

                            {{-- Ana Haber Görseli --}}
                            <div class="post--img">
                                <a href="{{ asset('storage/blog-images/1124x790/' . $blog->image_url) }}" class="thumb">
                                    <img src="{{ asset('storage/blog-images/1124x790/' . $blog->image_url) }}" alt="{{ $blog->title }}">
                                </a>
                            </div>

                            {{-- Haber İçeriği --}}
                            <div class="post--content">
                                {{-- İçerik, TinyMCE'den gelen HTML kodunu barındırdığı için {!! !!} ile yazdırılmalıdır --}}
                                {!! $blog->content !!}
                            </div>
                        </div>

                        {{-- Yazar Bilgi Kutusu --}}
                        @if ($blog->author)
                            <div class="post--author-info clearfix">
                                <div class="img">
                                    <div class="vc--parent">
                                        <div class="vc--child">
                                            <a href="{{ route('frontend.author.detail', $blog->author->slug) }}" class="btn-link">
                                                <img src="{{ $blog->author->img_url ? asset('storage/authors/263x272/' . $blog->author->img_url) : 'https://placehold.co/90x90/EFEFEF/AAAAAA&text=Yazar' }}" alt="{{ $blog->author->title }}">
                                                <p class="name">{{ $blog->author->title }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="info">
                                    <h2 class="h4">Yazar Hakkında</h2>
                                    <div class="content">
                                        {!! Str::limit(strip_tags($blog->author->description, '<p><br>'), 250) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Benzer Haberler Bölümü --}}
                        @if ($relatedBlogs->isNotEmpty())
                            <div class="post--related ptop--30">
                                <div class="post--items-title"><h2 class="h4">Bunları da Beğenebilirsiniz</h2></div>
                                <div class="post--items post--items-2">
                                    <ul class="nav row">
                                        @foreach ($relatedBlogs as $related)
                                            <li class="col-sm-6 pbottom--30">
                                                <div class="post--item post--layout-1">
                                                    <div class="post--img">
                                                        <a href="{{ route('frontend.blog.detail', $related->slug) }}" class="thumb">
                                                            <img src="{{ asset('storage/blog-images/562x395/' . $related->image_url) }}" alt="{{ $related->title }}">
                                                        </a>
                                                        <a href="{{ route('frontend.category', $related->category->slug) }}" class="cat">{{ $related->category->name }}</a>
                                                        <div class="post--info">
                                                            <ul class="nav meta">
                                                                <li><a href="#">{{ $related->author?->title ?? 'Editör' }}</a></li>
                                                                <li><a href="#">{{ $related->created_at->translatedFormat('d F Y') }}</a></li>
                                                            </ul>
                                                            <div class="title">
                                                                <h3 class="h4"><a href="{{ route('frontend.blog.detail', $related->slug) }}" class="btn-link">{{ $related->title }}</a></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Yan Bar (Sağ Taraf) --}}
                <div class="main--sidebar col-md-4 ptop--30 pbottom--30" data-sticky-content="true">
                    <div class="sticky-content-inner">
                        {{-- Bu alan temanın widget'larını içerir. Şimdilik statik bırakılmıştır. --}}
                        <div class="widget mb-20">
                            <div class="widget--title">
                                <h2 class="h4">Kategoriler</h2>
                                <i class="icon fa fa-folder-open-o"></i>
                            </div>
                            <div class="nav--widget">
                                <ul class="nav">
                                    {{-- Controller'dan gelen her kategori için bir döngü oluştur --}}
                                    @foreach ($sidebarCategories as $sidebarCategory)
                                        <li>
                                            <a href="{{ route('frontend.category', $sidebarCategory->slug) }}">
                                                <span>{{ $sidebarCategory->name }}</span>
                                                {{-- withCount ile gelen 'blogs_count' özelliğini kullanıyoruz --}}
                                                <span>{{ $sidebarCategory->blogs_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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
