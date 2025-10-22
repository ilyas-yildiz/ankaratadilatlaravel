@extends('frontend.layouts.app')

@section('title', $post->title) 
@section('meta_keywords', $post->meta_keywords ?? '') 
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->content), 160))

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/10.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        {{-- Blog başlığı --}}
                        <h1 class="text-white m-tb0">{{ $post->title }}</h1> 
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li><a href="{{ route('frontend.blog.index') }}">Blog</a></li>
                        <li class="text-white">{{ $post->title }}</li>
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
                         @if($post->image_url)
                             {{-- Büyük görsel boyutu (örn: 1200x600 veya 1460x730) --}}
                            <img class="img-responsive" src="{{ asset('storage/blog-images/1460x730/' . $post->image_url) }}" alt="{{ $post->title }}">
                         @endif
                    </div>

                    {{-- Meta (Tarih, Kategori, Yazar) --}}
                    <div class="sx-post-meta m-t20">
                        <ul>
                            <li class="post-date"><span>{{ $post->created_at->translatedFormat('d F Y') }}</span></li>
                            {{-- Kategori (varsa) --}}
                            {{-- @if($post->category)
                            <li class="post-category"><a href="{{ route('frontend.category', $post->category->slug) }}">{{ $post->category->name }}</a></li>
                            @endif --}}
                             {{-- Yazar (varsa) --}}
                             {{-- @if($post->author)
                             <li class="post-author"><i class="fa fa-user"></i> By <a href="{{ route('frontend.author.detail', $post->author->slug) }}">{{ $post->author->title }}</a> </li>
                             @endif --}}
                        </ul>
                    </div>

                    {{-- Blog İçeriği (Summernote'tan geldiği için HTML olarak basılır) --}}
                    <div class="sx-post-text">
                        {!! $post->content !!}
                    </div>

                    {{-- Önceki / Sonraki Yazı Linkleri --}}
                    <div class="post-controls p-t30">
                        <div class="d-flex justify-content-between">
                            <div class="prev-post">
                                @if($previousPost)
                                    <a href="{{ route('frontend.blog.detail', $previousPost->slug) }}">
                                         <i class="fa fa-angle-double-left"></i> Önceki
                                     </a>
                                @endif
                            </div>
                            <div class="next-post">
                                @if($nextPost)
                                    <a href="{{ route('frontend.blog.detail', $nextPost->slug) }}">
                                        Sonraki <i class="fa fa-angle-double-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($suggestedPosts->isNotEmpty())
                    <div class="clear m-t50" id="comment-list">
                         <div class="section-head">
                            <div class="sx-separator-outer separator-left">
                                <div class="sx-separator bg-white bg-moving bg-repeat-x" style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                                    <h3 class="sep-line-one">Önerilen Blog Yazıları</h3>
                                </div>
                            </div>
                        </div>
                        <div class="section-content">
                            <div class="row">
                                @foreach($suggestedPosts as $suggestedPost)
                                    <div class="col-lg-4 col-md-6 col-sm-12"> {{-- lg-4 yapıldı --}}
                                        <div class="blog-post blog-grid date-style-2 m-b30"> {{-- Alt boşluk eklendi --}}
                                            <div class="sx-post-media sx-img-effect img-reflection">
                                                <a href="{{ route('frontend.blog.detail', $suggestedPost->slug) }}">
                                                    @if($suggestedPost->image_url)
                                                         {{-- Uygun boyut (örn: 365x182) --}}
                                                        <img src="{{ asset('storage/blog-images/365x182/' . $suggestedPost->image_url) }}" alt="{{ $suggestedPost->title }}">
                                                    @else
                                                         <img src="https://placehold.co/365x182/EFEFEF/AAAAAA&text=Gorsel+Yok" alt="{{ $suggestedPost->title }}">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="sx-post-info p-t30">
                                                <div class="sx-post-meta ">
                                                    <ul>
                                                        <li class="post-date">
                                                            <strong>{{ $suggestedPost->created_at->translatedFormat('d') }}</strong>
                                                            <span>{{ $suggestedPost->created_at->translatedFormat('M') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="sx-post-title ">
                                                    <h4 class="post-title">
                                                        <a href="{{ route('frontend.blog.detail', $suggestedPost->slug) }}">{{ Str::limit($suggestedPost->title, 50) }}</a> {{-- Başlığı kısalt --}}
                                                    </h4>
                                                </div>
                                                <div class="sx-post-readmore">
                                                    <a href="{{ route('frontend.blog.detail', $suggestedPost->slug) }}" title="Devamını Okuyun" rel="bookmark" class="site-button-link">Devamını Okuyun</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </div>
                 @endif
                </div>
        </div>
    </div>
    @endsection

{{-- Gerekli JS (varsa) --}}
@push('custom-scripts')
<script>
    // Detay sayfası için özel JS kodları buraya eklenebilir.
</script>
@endpush