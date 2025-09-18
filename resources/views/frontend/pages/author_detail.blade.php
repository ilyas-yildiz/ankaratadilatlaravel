@extends('frontend.layouts.app')

@section('title', $author->title)

@section('content')
    {{-- Breadcrumb (Sayfa Yolu) Alanı --}}
    <div class="main--breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('frontend.home') }}" class="btn-link"><i class="fa fm fa-home"></i>Anasayfa</a></li>
                <li><a href="{{ route('frontend.author') }}" class="btn-link">Yazarlar</a></li>
                <li class="active"><span>{{ $author->title }}</span></li>
            </ul>
        </div>
    </div>

    <div class="main-content--section pbottom--30">
        <div class="container">
            <div class="row">
                {{-- Yazar Bilgileri ve Yazıları (Sol Taraf) --}}
                <div class="main--content col-md-8 col-sm-7" data-sticky-content="true">
                    <div class="sticky-content-inner">

                        {{-- Yazar Bilgi Kutusu --}}
                        <div class="post--author-info clearfix">
                            <div class="img">
                                <div class="vc--parent">
                                    <div class="vc--child">
                                        <img src="{{ $author->img_url ? asset('storage/authors/263x272/' . $author->img_url) : 'https://placehold.co/300x300/EFEFEF/AAAAAA&text=Görsel+Yok' }}" alt="{{ $author->title }}">
                                        <p class="name">{{ $author->title }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="info">
                                <h2 class="h4">Yazar Hakkında</h2>
                                <div class="content">
                                    {{-- description alanı HTML içerebileceği için {!! !!} kullanıyoruz --}}
                                    {!! $author->description !!}
                                </div>
                                <ul class="social nav">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        {{-- Yazara Ait Yazılar --}}
                        <div class="post--items post--items-5 pd--30-0">
                            <ul class="nav">
                                @forelse ($blogs as $blog)
                                    <li>
                                        <div class="post--item post--title-larger">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 col-xs-4 col-xxs-12">
                                                    <div class="post--img">
                                                        <a href="{{ route('frontend.blog.detail', $blog->slug) }}" class="thumb">
                                                            <img src="{{ asset('storage/blog-images/274x183/' . $blog->image_url) }}" alt="{{ $blog->title }}">
                                                        </a>
                                                        <a href="{{ route('frontend.category', $blog->category->slug) }}" class="cat">{{ $blog->category->name }}</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-sm-12 col-xs-8 col-xxs-12">
                                                    <div class="post--info">
                                                        <ul class="nav meta">
                                                            <li><a href="#">{{ $author->title }}</a></li>
                                                            <li><a href="#">{{ $blog->created_at->translatedFormat('d F Y') }}</a></li>
                                                        </ul>
                                                        <div class="title"><h3 class="h4"><a href="{{ route('frontend.blog.detail', $blog->slug) }}" class="btn-link">{{ $blog->title }}</a></h3></div>
                                                    </div>
                                                    <div class="post--content"><p>{{ Str::limit(html_entity_decode(strip_tags($blog->content)), 150) }}</p></div>
                                                    <div class="post--action"><a href="{{ route('frontend.blog.detail', $blog->slug) }}">Devamını Oku...</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <p>Bu yazarın henüz hiç yazısı bulunmamaktadır.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Sayfalama Linkleri --}}
                        <div class="pagination--wrapper clearfix bdtop--1 bd--color-2 ptop--60 pbottom--30">
                            {{ $blogs->links('pagination::bootstrap-4') }}
                        </div>
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
