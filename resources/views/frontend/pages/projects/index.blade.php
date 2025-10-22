@extends('frontend.layouts.app')

@section('title', 'Projelerimiz - Ankara Tadilat ve Dekorasyon')
{{-- Meta etiketleri --}}

@section('content')

    <div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('assets/images/banner/3.jpg') }});">
        <div class="overlay-main bg-black opacity-07"></div>
        <div class="container">
            <div class="sx-bnr-inr-entry">
                <div class="banner-title-outer">
                    <div class="banner-title-name">
                        <h2 class="m-tb0">Projelerimiz</h2>
                    </div>
                </div>
                <div>
                    <ul class="sx-breadcrumb breadcrumb-style-2">
                        <li><a href="{{ route('frontend.home') }}">Anasayfa</a></li>
                        <li>Projelerimiz</li>
                    </ul>
                </div>
                </div>
        </div>
    </div>
    <div class="section-full p-tb80 inner-page-padding">
        <div class="container">
            {{-- Filtreleme butonları (şimdilik statik, JS ile çalışır) --}}
            {{-- Eğer Controller'dan $projectTypes gönderirsen, burayı dinamik yapabilirsin --}}
            <div class="filter-wrap p-b30 text-center">
                <ul class="filter-navigation masonry-filter clearfix">
                    <li class="active"><a class="btn from-top" data-filter="*" href="#" data-hover="All">Hepsi</a></li>
                    {{-- Örnek filtreler --}}
                    {{-- @isset($projectTypes)
                        @foreach($projectTypes as $type)
                        <li><a class="btn from-top" data-filter=".filter-{{ Str::slug($type) }}" href="#">{{ $type }}</a></li>
                        @endforeach
                    @endisset --}}
                    <li><a class=" btn from-top" data-filter=".cat-1" href="#">Mimarlık</a></li>
                    <li><a class=" btn from-top" data-filter=".cat-2" href="#">Dekorasyon</a></li>
                    <li><a class=" btn from-top" data-filter=".cat-3" href="#">Dış Mekan</a></li>
                    <li><a class=" btn from-top" data-filter=".cat-4" href="#">İç Mekan</a></li>
                    <li><a class=" btn from-top" data-filter=".cat-5" href="#">Mühendislik</a></li>
                </ul>
            </div>
            {{-- Isotope.js'in çalışması için masonry-outer gerekli --}}
            <ul class="masonry-outer mfp-gallery work-grid row clearfix list-unstyled"> 
                @forelse($projects as $project)
                    {{-- Filtreleme için class eklenir (örn: filter-tadilat) --}}
                    <li class="masonry-item {{-- filter-{{ Str::slug($project->project_type) }} --}} cat-1 col-lg-4 col-md-6 col-sm-12 m-b30"> 
                        <div class="sx-box image-hover-block">
                            <div class="sx-thum-bx">
                                @if($project->image_url)
                                     {{-- Liste için uygun boyut (örn: 512x384 veya 600x400) --}}
                                    <img src="{{ asset('storage/project-images/600x400/' . $project->image_url) }}" alt="{{ $project->title }}">
                                @else
                                    <img src="https://placehold.co/600x400/EFEFEF/AAAAAA&text=Gorsel+Yok" alt="{{ $project->title }}">
                                @endif
                            </div>
                            <div class="sx-info p-t20 text-white">
                                {{-- Detay sayfasına link --}}
                                <h4 class="sx-tilte m-t0"><a href="{{ route('frontend.projects.detail', $project->slug) }}">{{ $project->title }}</a></h4>
                                <p class="m-b0">{{ $project->location }}</p>
                            </div>
                            {{-- Magnific Popup için büyük resim linki --}}
                             @if($project->image_url)
                            <a class="mfp-link" href="{{ asset('storage/project-images/1200x800/' . $project->image_url) }}">
                                <i class="fa fa-arrows-alt"></i>
                            </a>
                             @endif
                        </div>
                    </li>
                @empty
                    <li class="col-12 text-center">Gösterilecek proje bulunamadı.</li>
                @endforelse
            </ul>
            </div>
    </div>
    @endsection

{{-- Isotope ve Magnific Popup JS dosyalarının layout'ta veya burada yüklendiğinden emin ol --}}
@push('custom-scripts')
{{-- Eğer bu scriptler ana script dosyasında yoksa buraya ekle: --}}
{{-- <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script> --}}
<script>
    jQuery(document).ready(function() {
        // Isotope filter active
        // Bu kod genellikle temanın custom.js dosyasında bulunur, kontrol et.
        // Eğer yoksa buraya ekleyebilirsin.
        function handleIsotopeFiltering() {
            if (jQuery().isotope) {
                var $container = jQuery('.masonry-outer');
                $container.isotope({
                    itemSelector: '.masonry-item',
                    // masonry: { // Eğer masonry layout kullanılıyorsa
                    //     columnWidth: '.masonry-item'
                    // },
                    // filter: '*' // Varsayılan filtre
                });

                jQuery('.filter-navigation li').on('click', function() {
                    var filterValue = jQuery(this).find('a').data('filter');
                    $container.isotope({ filter: filterValue });

                    jQuery('.filter-navigation li').removeClass('active');
                    jQuery(this).addClass('active');
                    return false; // Linkin varsayılan davranışını engelle
                });
            }
        }
        handleIsotopeFiltering();

        // Magnific Popup active (Bu da genellikle custom.js'de vardır)
        if (jQuery('.mfp-gallery').length) {
            jQuery('.mfp-gallery').magnificPopup({
                delegate: '.mfp-link',
                type: 'image',
                tLoading: 'Loading image #%curr%...',
                mainClass: 'mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                },
                image: {
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                }
            });
        }
    });
</script>
@endpush