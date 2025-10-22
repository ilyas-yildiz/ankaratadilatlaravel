@extends('frontend.layouts.app')

@section('title', $project->title . ' - Projelerimiz')
@section('meta_description', Str::limit(strip_tags($project->content), 160)) 

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/1.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        <h2 class="m-tb0">Proje Detayı</h2>
                        <p>{{ $project->title }}</p>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li><a href="{{ route('frontend.projects') }}">Projelerimiz</a></li>
                        <li>{{ $project->title }}</li>
                    </ul>
                </div>
                </div>
        </div>
    </div>
    <div class="section-full p-tb80 inner-page-padding stick_in_parent">
        <div class="container">
            <div class="row">

                {{-- Galeri Görselleri (Sol Kolon) --}}
                <div class="col-lg-8 col-md-12">
                    <div class="project-detail-outer row mfp-gallery"> {{-- mfp-gallery eklendi --}}
                         {{-- Controller'da eager load ile çektiğimiz galeri item'larını döngüye alıyoruz --}}
                        @if($project->gallery && $project->gallery->items->isNotEmpty())
                            @foreach($project->gallery->items as $item)
                                <div class="col-md-6">
                                    <div class="project-detail-pic m-b30">
                                        <div class="sx-media">
                                            {{-- Galeri görsellerinin yolu ve boyutu (GalleryItem modelinde accessor olabilir) --}}
                                            <a href="{{ asset('storage/gallery-images/' . $project->gallery->id . '/' . $item->image_url) }}" class="mfp-link"> {{-- Magnific Popup için link --}}
                                                 <img src="{{ asset('storage/gallery-images/' . $project->gallery->id . '/400x300/' . $item->image_url) }}" alt="{{ $project->title }}"> {{-- Küçük boyut --}}
                                             </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Ana proje görselini göster (eğer galeri yoksa) --}}
                             @if($project->image_url)
                             <div class="col-md-12">
                                <div class="project-detail-pic m-b30">
                                    <div class="sx-media">
                                          <a href="{{ asset('storage/project-images/1920x1080/' . $project->image_url) }}" class="mfp-link">
                                              <img src="{{ asset('storage/project-images/600x400/' . $project->image_url) }}" alt="{{ $project->title }}">
                                          </a>
                                    </div>
                                </div>
                            </div>
                             @else
                                <p class="col-12">Bu projeye ait görsel bulunamadı.</p>
                             @endif
                        @endif
                    </div>
                     {{-- Proje Açıklaması (Summernote içeriği) --}}
                     <div class="project-detail-containt m-t30">
                         <div class="sx-post-text">
                            <h4>Proje Açıklaması</h4>
                            {!! $project->content !!}
                        </div>
                     </div>
                </div>

                {{-- Proje Bilgileri (Sağ Kolon) --}}
                <div class="col-lg-4 col-md-12 sticky_column">
                    <div class="project-detail-containt-2">
                        <div class="bg-white text-black p-a20 shadow">
                            <div class="product-block bg-gray p-a30 m-b30">
                                <ul>
                                    <li>
                                        <h4 class="sx-title">Başlangıç Tarihi</h4>
                                        {{-- Carbon ile formatlama (eğer tarih varsa) --}}
                                        <p>{{ $project->start_date ? $project->start_date->translatedFormat('d F Y') : '-' }}</p>
                            </li>
                                     <li>
                                        <h4 class="sx-title">Bitiş Tarihi</h4>
                                       <p>{{ $project->end_date ? $project->end_date->translatedFormat('d F Y') : '-' }}</p>
                            </li>
                                    <li>
                                        <h4 class="sx-title">Müşteri</h4>
                                        <p>{{ $project->client }}</p>
                                    </li>
                                    <li>
                                        <h4 class="sx-title">Proje Yöneticisi</h4>
                                        <p>{{ $project->project_manager }}</p>
                                    </li>
                                    <li>
                                        <h4 class="sx-title">Lokasyon</h4>
                                        <p>{{ $project->location ?? '-' }}</p>
                                    </li>
                                    <li>
                                        <h4 class="sx-title">Proje Tipi</h4>
                                        <p>{{ $project->project_type ?? '-' }}</p>
                                    </li>
                                </ul>
                            </div>
                            
                            {{-- Statik Sosyal Medya İkonları --}}
                            {{-- <div class="m-b0">
                                <div class="sx-divider divider-1px bg-black"><i class="icon-dot c-square"></i></div>
                            </div>
                            <ul class="social-icons social-square social-darkest m-b0">
                                <li><a href="javascript:void(0);" class="fa fa-facebook"></a></li>
                                <li><a href="javascript:void(0);" class="fa fa-twitter"></a></li>
                                <li><a href="javascript:void(0);" class="fa fa-youtube"></a></li>
                                <li><a href="javascript:void(0);" class="fa fa-instagram"></a></li>
                            </ul> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Controller'dan gelen $otherProjects değişkeni --}}
    @if($otherProjects->isNotEmpty())
        <div class="section-full p-tb80 bg-gray inner-page-padding">
            <div class="container-fluid"> {{-- container-fluid kullanılmış --}}
                <div class="section-content">
                    <div class="section-head container"> {{-- Başlık container içinde --}}
                        <div class="sx-separator-outer separator-left">
                            <div class="sx-separator bg-white bg-moving bg-repeat-x" style="background-image:url({{ asset('assets/images/background/cross-line2.png') }})">
                                <h3 class="sep-line-one">Diğer Projeler</h3>
                            </div>
                        </div>
                    </div>
                    <div class="work-carousel-outer">
                         {{-- Owl Carousel yapısı --}}
                        <div class="owl-carousel mfp-gallery project-carousel project-carousel3 owl-btn-vertical-center p-lr80">
                            @foreach($otherProjects as $otherProject)
                                <div class="item">
                                    <div class="project-mas m-a30">
                                        <div class="image-effect-one">
                                            @if($otherProject->image_url)
                                                 {{-- Uygun boyut (örn: 600x400) --}}
                                                <img src="{{ asset('storage/project-images/600x400/' . $otherProject->image_url) }}" alt="{{ $otherProject->title }}">
                                            @else
                                                 <img src="https://placehold.co/600x400/EFEFEF/AAAAAA&text=Gorsel+Yok" alt="{{ $otherProject->title }}">
                                            @endif
                                            <div class="figcaption">
                                                {{-- Magnific Popup linki --}}
                                                 @if($otherProject->image_url)
                                                <a class="mfp-link" href="{{ asset('storage/project-images/1920x1080/' . $otherProject->image_url) }}">
                                                    <i class="fa fa-arrows-alt"></i>
                                                </a>
                                                 @endif
                                            </div>
                                        </div>
                                        <div class="project-info p-a20 bg-white">
                                            <h4 class="sx-tilte m-t0">
                                                {{-- Detay sayfasına link --}}
                                                <a href="{{ route('frontend.projects.detail', $otherProject->slug) }}">
                                                    {{ $otherProject->title }}
                                                </a>
                                            </h4>
                                             {{-- İçeriği kısalt --}}
                                            <p>{{ Str::limit(strip_tags($otherProject->content), 80) }}</p>
                                            <a href="{{ route('frontend.projects.detail', $otherProject->slug) }}"><i class="link-plus bg-primary"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endsection

{{-- Gerekli JS dosyaları (Owl Carousel, Magnific Popup, Sticky Sidebar) layout'ta veya burada yüklenmeli --}}
@push('custom-scripts')
{{-- <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/theia-sticky-sidebar.js') }}"></script> --}}
<script>
    jQuery(document).ready(function() {
        // Owl Carousel for similar projects (Genellikle custom.js'de vardır)
        if (jQuery('.project-carousel3').length) {
            jQuery('.project-carousel3').owlCarousel({
                loop:true,
                margin:30,
                nav:true,
                dots: false,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                responsive:{
                    0:{ items:1 },
                    640:{ items:2 },
                    768:{ items:2 },
                    991:{ items:3 },
                    1024:{ items:3 },
                    1200:{ items:4 },
                    1366:{ items:4 },
                    1440:{ items:5 }
                }
            });
        }

         // Magnific Popup (Genellikle custom.js'de vardır)
         if (jQuery('.mfp-gallery').length) {
            jQuery('.mfp-gallery').magnificPopup({
                delegate: '.mfp-link',
                type: 'image',
                gallery: { enabled:true }
            });
        }

        // Sticky Sidebar (Genellikle custom.js'de vardır)
        if (jQuery('.sticky_column').length && jQuery(window).width() >= 768) { // Sadece mobil olmayanlarda
            jQuery('.sticky_column').theiaStickySidebar({
                additionalMarginTop: 100 // Header yüksekliğine göre ayarla
            });
        }
    });
</script>
@endpush