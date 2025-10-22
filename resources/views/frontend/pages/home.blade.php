{{-- Ana layout'u extend ediyoruz --}}
@extends('frontend.layouts.app')

{{-- Sayfa başlığını ayarlıyoruz --}}
@section('title', 'Ankara Tadilat ve Dekorasyon - Anasayfa') 

{{-- Slider için gerekli CSS dosyalarını layout'a push ediyoruz --}}
@push('slider-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/revolution/revolution/css/settings.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/revolution/revolution/css/navigation.css') }}">
    <link rel='stylesheet' href='{{ asset('assets/plugins/revolution-addons/beforeafter/css/revolution.addon.beforeafter.css') }}' type='text/css' media='all'>
@endpush

{{-- Sayfa içeriği --}}
@section('content')

    {{-- Revolution Slider HTML yapısı (Görsel yolları asset() ile güncellendi) --}}
    {{-- Şimdilik SLIDE 2'yi kaldırdım, istersen geri ekleyebilirsin veya dinamik hale getirebiliriz --}}
    <div id="rev_slider_346_1_wrapper" class="rev_slider_wrapper fullscreen-container" data-alias="beforeafterslider1" data-source="gallery" style="background:#252525;padding:0px;">
        <div id="rev_slider_346_1" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.4.3.3">
            <ul>
               {{-- === DİNAMİK SLIDER DÖNGÜSÜ BAŞLANGICI === --}}
                    @isset($slides)
                        @forelse($slides as $index => $slide)
                            {{-- Her slide için bir <li> elementi --}}
                            <li data-index="rs-{{ $slide->id }}"
                                data-transition="fade"
                                data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="" data-rotate="0" data-saveperformance="off"
                                data-title="{{ $slide->title ?? 'Slide' }}" data-description=""
                                {{-- data-beforeafter: Ana (renkli) görseli tanımlar --}}
                                data-beforeafter='{"moveto":"50%|50%|50%|50%",
                                                   "bgColor":"transparent",
                                                   "bgType":"image",
                                                   "bgImage":"{{ $slide->image_url ? asset('storage/slide-images/1920x1080/' . $slide->image_url) : asset('assets/images/main-slider/slider5/slide1.jpg') }}",
                                                   "bgFit":"cover",
                                                   "bgPos":"center center",
                                                   "bgRepeat":"no-repeat",
                                                   "direction":"horizontal",
                                                   "easing":"Power2.easeInOut",
                                                   "delay":"500",
                                                   "time":"750",
                                                   "out":"fade",
                                                   "carousel":false}'>

                                {{-- Buraya SKETCH görselini yerleştiriyoruz --}}
                                <img src="{{ $slide->image_sketch_url ? asset('storage/slide-images-sketch/1920x1080/' . $slide->image_sketch_url) : asset('assets/images/main-slider/slider5/slide1-sk.jpg') }}"
                                     data-beforeafter="after" {{-- Bu görselin 'after' olduğunu belirtir --}}
                                     data-bgcolor='transparent' alt="{{ $slide->title ?? '' }}"
                                     data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" class="rev-slidebg" data-no-retina="">

                                {{-- LAYER NR. 1 (Başlık - BEFORE) --}}
                                <div class="tp-caption tp-resizeme rs-parallaxlevel-5"
                                     id="slide-{{ $slide->id }}-layer-1-before" {{-- ID güncellendi --}}
                                     data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                     data-y="['middle','middle','middle','middle']" data-voffset="['-40','-40','-40','-20']"
                                     data-fontsize="['80','70','60','40']" data-lineheight="['80','70','60','40']"
                                     data-width="['auto']" data-height="auto" data-whitespace="nowrap" data-type="text"
                                     data-beforeafter="before" {{-- Eklendi --}}
                                     data-responsive_offset="on"
                                     data-frames='[{"delay":600,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                     data-textalign="['center','center','center','center']"
                                     style="z-index: 5; white-space: nowrap; font-weight: 900; 
                                            color: #2e313b; {{-- Before Rengi --}}
                                            letter-spacing: 0px; font-family: 'Poppins', sans-serif; text-transform:uppercase;">
                                     {{ $slide->title }}
                                </div>

                                {{-- LAYER NR. 4 (Başlık - AFTER) --}}
                                <div class="tp-caption tp-resizeme rs-parallaxlevel-5 tp-blackshadow" {{-- tp-blackshadow eklendi (orijinaldeki gibi) --}}
                                     id="slide-{{ $slide->id }}-layer-4-after" {{-- ID güncellendi --}}
                                     data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                     data-y="['middle','middle','middle','middle']" data-voffset="['-40','-40','-40','-20']"
                                     data-fontsize="['80','70','60','40']" data-lineheight="['80','70','60','40']"
                                     data-width="['auto']" data-height="auto" data-whitespace="nowrap" data-type="text"
                                     data-beforeafter="after" {{-- Eklendi --}}
                                     data-responsive_offset="on"
                                      {{-- After için farklı animasyon başlangıcı (orijinaldeki gibi) --}}
                                     data-frames='[{"delay":2000,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]' 
                                     data-textalign="['center','center','center','center']"
                                     style="z-index: 16; white-space: nowrap; font-weight: 900; 
                                            color: #ffffff; {{-- After Rengi --}}
                                            letter-spacing: 0px; font-family: 'Poppins', sans-serif; text-transform:uppercase;">
                                     {{ $slide->title }}
                                </div>


                                {{-- LAYER NR. 2 (Alt Başlık - BEFORE - Eğer varsa) --}}
                                @if($slide->subtitle)
                                <div class="tp-caption tp-resizeme rs-parallaxlevel-5"
                                     id="slide-{{ $slide->id }}-layer-2-before" {{-- ID güncellendi --}}
                                     data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                     data-y="['middle','middle','middle','middle']" data-voffset="['40','40','20','20']"
                                     data-fontsize="['24','22','20','18']" data-lineheight="['30','28','26','24']"
                                     data-width="['800','700','600','400']" data-height="auto" data-whitespace="normal" data-type="text"
                                     data-beforeafter="before" {{-- Eklendi --}}
                                     data-responsive_offset="on"
                                     data-frames='[{"delay":900,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                     data-textalign="['center','center','center','center']"
                                     style="z-index: 6; white-space: normal; font-weight: 500; 
                                            color: #2e313b; {{-- Before Rengi --}}
                                            letter-spacing: 0px; font-family: 'Poppins', sans-serif;">
                                     {{ $slide->subtitle }}
                                </div>
                                @endif

                                {{-- LAYER NR. 5 (Alt Başlık - AFTER - Eğer varsa) --}}
                                @if($slide->subtitle)
                                <div class="tp-caption tp-resizeme rs-parallaxlevel-5"
                                     id="slide-{{ $slide->id }}-layer-5-after" {{-- ID güncellendi --}}
                                     data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                     data-y="['middle','middle','middle','middle']" data-voffset="['40','40','20','20']"
                                     data-fontsize="['24','22','20','18']" data-lineheight="['30','28','26','24']"
                                     data-width="['800','700','600','400']" data-height="auto" data-whitespace="normal" data-type="text"
                                     data-beforeafter="after" {{-- Eklendi --}}
                                     data-responsive_offset="on"
                                      {{-- After için farklı animasyon başlangıcı --}}
                                     data-frames='[{"delay":2100,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                     data-textalign="['center','center','center','center']"
                                     style="z-index: 17; white-space: normal; font-weight: 500; 
                                            color: #ffffff; {{-- After Rengi --}}
                                            letter-spacing: 0px; font-family: 'Poppins', sans-serif;">
                                     {{ $slide->subtitle }}
                                </div>
                                @endif


                                {{-- LAYER NR. 3 (Buton - BEFORE - Eğer link ve metin varsa) --}}
                                @if($slide->link && $slide->button_text)
                                <a class="tp-caption rev-btn site-button btn-half rs-parallaxlevel-4" {{-- rs-parallaxlevel-4 eklendi --}}
                                   href="{{ $slide->link }}" target="_blank" rel="noopener noreferrer"
                                   id="slide-{{ $slide->id }}-layer-3-before" {{-- ID güncellendi --}}
                                   data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                   data-y="['middle','middle','middle','middle']" data-voffset="['120','120','90','80']"
                                   data-width="auto" data-height="auto" data-whitespace="nowrap" data-type="button" data-actions=''
                                   data-beforeafter="before" {{-- Eklendi --}}
                                   data-responsive_offset="on"
                                   data-frames='[{"delay":1200,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"300","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(0,0,0,1);bg:rgba(255,255,255,1);bc:rgba(255,255,255,1);bw:2px 2px 2px 2px;"}]' {{-- Hover stili güncellendi --}}
                                   data-textAlign="['center','center','center','center']"
                                   data-paddingtop="[0,0,0,0]" data-paddingright="[30,30,30,25]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[30,30,30,25]"
                                   style="z-index: 7; white-space: nowrap; font-size: 16px; line-height: 50px; font-weight: 600; 
                                          color: #fff; {{-- Before Rengi --}}
                                          font-family: 'Poppins', sans-serif; 
                                          background-color:#2e313b; {{-- Before Arka Plan --}}
                                          border-color:rgba(0,0,0,1); border-style:solid; border-width:2px; border-radius:3px; outline:none; box-shadow:none; box-sizing:border-box; cursor:pointer; text-transform:uppercase;">
                                   <span>{{ $slide->button_text }}</span>
                                </a>
                                @endif

                                {{-- LAYER NR. 6 (Buton - AFTER - Eğer link ve metin varsa) --}}
                                @if($slide->link && $slide->button_text)
                                <a class="tp-caption rev-btn site-button btn-half rs-parallaxlevel-4" {{-- rs-parallaxlevel-4 eklendi --}}
                                   href="{{ $slide->link }}" target="_blank" rel="noopener noreferrer"
                                   id="slide-{{ $slide->id }}-layer-6-after" {{-- ID güncellendi --}}
                                   data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                   data-y="['middle','middle','middle','middle']" data-voffset="['120','120','90','80']"
                                   data-width="auto" data-height="auto" data-whitespace="nowrap" data-type="button" data-actions=''
                                   data-beforeafter="after" {{-- Eklendi --}}
                                   data-responsive_offset="on"
                                    {{-- After için farklı animasyon başlangıcı --}}
                                   data-frames='[{"delay":2200,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"300","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(255,255,255,1);bg:rgba(0,0,0,0.8);bc:rgba(0,0,0,1);bw:2px 2px 2px 2px;"}]' {{-- Hover stili güncellendi --}}
                                   data-textAlign="['center','center','center','center']"
                                   data-paddingtop="[0,0,0,0]" data-paddingright="[30,30,30,25]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[30,30,30,25]"
                                   style="z-index: 18; white-space: nowrap; font-size: 16px; line-height: 50px; font-weight: 600; 
                                          color: #2e313b; {{-- After Rengi --}}
                                          font-family: 'Poppins', sans-serif; 
                                          background-color:rgb(255,255,255); {{-- After Arka Plan --}}
                                          border-color:rgba(255,255,255,1); border-style:solid; border-width:2px; border-radius:3px; outline:none; box-shadow:none; box-sizing:border-box; cursor:pointer; text-transform:uppercase;">
                                   <span>{{ $slide->button_text }}</span>
                                </a>
                                @endif

                            </li>
                    @empty
                        {{-- Hiç aktif slide yoksa gösterilecek varsayılan slide --}}
                        {{-- ... (varsayılan slide kodu aynı kalabilir) ... --}}
                    @endforelse
               @else
                     {{-- Controller'dan $slides değişkeni gelmezse --}}
                     <li data-index="rs-error" data-transition="fade" data-masterspeed="default" data-title="Error Slide">
                         <img src="{{ asset('assets/images/main-slider/slider5/slide1.jpg') }}" alt="">
                         <div class="tp-caption" data-x="center" data-y="center" data-type="text" style="color:red; font-size: 30px;">Slider verileri yüklenemedi!</div>
                     </li>
                @endisset
                {{-- === DİNAMİK SLIDER DÖNGÜSÜ SONU === --}}
            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
        </div>
    </div>
    <div class="section-full p-t80 p-b50 bg-white inner-page-padding">
        <div class="container">
            <div class="section-content ">
                <div class="our-history text-black">
                    {{-- Controller'dan gelen $projects değişkenini döngüye alıyoruz --}}
                    @isset($projects)
                        @forelse($projects as $project)
                            <div class="row">
                                <div class="col-12 pic-bg-border">
                                    {{-- Proje görseli (uygun boyutu seçmelisin, örn: 600x400) --}}
                                     @if($project->image_url)
                                        <div class="our-history-pic bg-no-repeat bg-center bg-cover" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('storage/project-images/600x400/' . $project->image_url) }});"></div>
                                     @else
                                        {{-- Varsayılan görsel --}}
                                        <div class="our-history-pic bg-no-repeat bg-center bg-cover" data-stellar-background-ratio="0.5" style="background-image:url(https://placehold.co/600x400/EFEFEF/AAAAAA&text=Proje+Gorseli);height: 500px;"></div> {{-- Boyut ekledim --}}
                                     @endif
                                </div>
                                <div class="col-12">
                                    <div class="our-history-content m-b30">
                                        <div class="large-title">
                                            <h2 class="m-t0">{{ $project->title }}</h2>
                                             {{-- Proje yöneticisi alanı --}}
                                            <h4>{{ $project->project_manager }}</h4>
                                        </div>
                                         {{-- Proje içeriği (content), Str::limit ile kısaltılabilir --}}
                                         {{-- Blade içinde strip_tags gerekmez, {!! !!} kullanılmadıkça HTML yorumlanır --}}
                                        <p>{{ Str::limit(strip_tags($project->content), 250) }}</p> {{-- 250 karakter limiti örneği --}}
                                        
                                        {{-- Proje detay sayfasına link (rota adı 'frontend.project.detail' varsayalım) --}}
                                        <a href="{{-- route('frontend.project.detail', $project->slug) --}}" class="site-button-secondry btn-half"><span> Detaylar</span></a>
                                    </div>
                                </div>
                            </div>
                         @empty
                             <p class="text-center">Gösterilecek proje bulunamadı.</p>
                         @endforelse
                     @endisset

                    <div class="text-center load-more-btn-outer" style="background-image:url({{ asset('assets/images/background/cross-line.png') }})">
                        {{-- Tüm projeler sayfasına link --}}
                        <a href="{{ route('frontend.projects') }}" id="loadMorebtn-5" class="site-button-secondry btn-half"><span>DİĞER PROJELER</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

{{-- Slider için gerekli JS dosyalarını layout'a push ediyoruz --}}
@push('slider-scripts')
    <script src="{{ asset('assets/plugins/revolution/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/revolution/revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/revolution/revolution/js/extensions/revolution-plugin.js') }}"></script>
    <script src="{{ asset('assets/plugins/revolution-addons/beforeafter/js/revolution.addon.beforeafter.min.js') }}"></script>
    <script src="{{ asset('assets/js/rev-script-5.js') }}"></script>
@endpush