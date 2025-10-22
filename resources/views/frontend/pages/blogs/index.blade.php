@extends('frontend.layouts.app')

@section('title', 'Blog - Ankara Tadilat ve Dekorasyon')

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/8.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        <h2 class="m-tb0">Blog Yazıları</h2>
                        <span class="text-white">İç tasarımın özü her zaman insanlar ve onların nasıl yaşadıklarıyla ilgili olacaktır...</span>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li>Blog Yazıları</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="section-full p-tb80 inner-page-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="news-listing">
                        @forelse ($posts as $post)
                            <div class="blog-post blog-lg date-style-3 block-shadow m-b30">
                                <div class="sx-post-media sx-img-effect zoom-slow">
                                    <a href="{{ route('frontend.blog.detail', $post->slug) }}">
                                        @if($post->image_url)
                                            <img src="{{ asset('storage/blog-images/730x365/' . $post->image_url) }}" alt="{{ $post->title }}">
                                        @else
                                             <img src="https://placehold.co/730x365/EFEFEF/AAAAAA&text=Gorsel+Yok" alt="{{ $post->title }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="sx-post-info bg-white p-a30">
                                    <div class="sx-post-meta">
                                        <ul>
                                            <li class="post-date"> <strong>{{ $post->created_at->translatedFormat('d F Y') }}</strong> </li>
                                        </ul>
                                    </div>
                                    <div class="sx-post-title">
                                        <h3 class="post-title"><a href="{{ route('frontend.blog.detail', $post->slug) }}">{{ $post->title }}</a></h3>
                                    </div>
                                    <div class="sx-post-text">
                                        <p>{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                    </div>
                                    <div class="clearfix">
                                        <div class="sx-post-readmore pull-left">
                                            <a href="{{ route('frontend.blog.detail', $post->slug) }}" title="Devamını Oku" rel="bookmark" class="site-button-link">Devamını Oku</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Yayınlanmış blog yazısı bulunamadı.</p>
                        @endforelse
                    </div>

                    <div class="pagination-outer">
                         {{-- DÜZELTİLDİ: Bootstrap 4 pagination view'ı kullanıldı --}}
                        {{ $posts->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 sticky_column">
                    <div class="side-bar p-a30 bg-gray">

                        <div class="widget">
                            <h4 class="widget-title ">Ara</h4>
                            <div class="search-bx p-a10 bg-white">
                                <form role="search" action="{{-- route('frontend.search') --}}" method="GET">
                                    <div class="input-group">
                                        <input name="q" type="text" class="form-control bg-gray" placeholder="Metninizi yazın" value="{{ request('q') }}">
                                        <span class="input-group-btn bg-gray">
                                            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="widget recent-posts-entry">
                            <h4 class="widget-title">Son Gönderiler</h4>
                            <div class="section-content p-a10 bg-white">
                                <div class="widget-post-bx">
                                    @forelse ($latestPostsSidebar as $latestPost)
                                        <div class="widget-post clearfix">
                                            <div class="sx-post-media">
                                                 @if($latestPost->image_url)
                                                    <img src="{{ asset('storage/blog-images/128x128/' . $latestPost->image_url) }}" alt="{{ $latestPost->title }}">
                                                 @else
                                                     <img src="https://placehold.co/128/EFEFEF/AAAAAA&text=Gorsel" alt="{{ $latestPost->title }}">
                                                 @endif
                                            </div>
                                            <div class="sx-post-info">
                                                <div class="sx-post-header">
                                                    <a href="{{ route('frontend.blog.detail', $latestPost->slug) }}">
                                                        <h6 class="post-title">{{ Str::limit($latestPost->title, 40) }}</h6>
                                                    </a>
                                                </div>
                                                <div class="sx-post-meta">
                                                    <ul>
                                                        <li class="post-author">{{ $latestPost->created_at->translatedFormat('d M Y') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>Yazı bulunamadı.</p>
                                    @endforelse {{-- Bu @endforelse doğru kapatıyor --}}
                                </div> {{-- widget-post-bx sonu --}}
                            </div> {{-- section-content sonu --}}
                        </div> {{-- widget recent-posts-entry sonu --}}

                    </div> {{-- side-bar sonu --}}
                </div> {{-- Sidebar Kolon sonu --}}

            </div> {{-- Row sonu --}}
        </div> {{-- Container sonu --}}
    </div> {{-- Section sonu --}}

@endsection

@push('custom-scripts')
<script>
    jQuery(document).ready(function() {
        if (jQuery('.sticky_column').length && jQuery(window).width() >= 768) {
            jQuery('.sticky_column').theiaStickySidebar({
                additionalMarginTop: 100
            });
        }
    });
</script>
@endpush