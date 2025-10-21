{{-- Arka plan görseli için asset() --}}
<div class="footer-top overlay-wraper bg-cover"
    style="background-image:url({{ asset('assets/images/background/f-bg.jpg') }})">
    <div class="overlay-main sx-bg-secondry opacity-08"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget widget_about">
                    <div class="logo-footer clearfix p-b15">
                        <a href="{{ route('frontend.home') }}"><img
                                src="{{ asset('assets/images/logos/yildizlogo3.png') }}"
                                alt="Yıldız Mühendis Mimarlık"></a>
                    </div>
                    {{-- Bu metin statik kalabilir veya Ayarlar modülünden çekilebilir --}}
                    <p>Yüksek kalite standartları ve müşteri odaklı yaklaşımımızla her projede fark yaratıyoruz.
                        Amacımız, estetik ve fonksiyonelliği harmanlayarak, yaşam alanlarınızı daha yaşanabilir
                        kılmaktır. Güçlü ve yenilikçi çözümlerle, siz değerli müşterilerimize en iyi hizmeti sunmayı
                        hedefliyoruz.</p>
                    {{-- Sosyal medya linkleri Ayarlar modülünden çekilebilir --}}
                    <ul class="social-icons sx-social-links">
                        <li><a href="https://facebook.com/yildizmuhendislik" class="fa fa-facebook"></a></li>
                        <li><a href="https://instagram.com/yildizmuhendislik" class="fa fa-instagram"></a></li>
                        <li><a href="https://youtube.com/yildizmuhendislik" class="fa fa-youtube"></a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget recent-posts-entry-date">
                    <h5 class="widget-title">Son Yazılar</h5>
                    <div class="widget-post-bx">
                        {{-- Controller'dan $latestPosts değişkeni gelecek varsayalım --}}
                        @isset($latestPosts)
                            @forelse($latestPosts as $post)
                                <div class="widget-post clearfix">
                                    <div class="sx-post-date text-center text-uppercase text-white">
                                        {{-- Carbon tarih formatlama --}}
                                        <strong class="p-date">{{ $post->created_at->format('d') }}</strong>
                                        {{-- Türkçe ay adı için Carbon locale ayarı gerekebilir --}}
                                        <span class="p-month">{{ $post->created_at->translatedFormat('M') }}</span>
                                        <span class="p-year">{{ $post->created_at->format('Y') }}</span>
                                    </div>
                                    <div class="sx-post-info">
                                        <div class="sx-post-header">
                                            {{-- Blog detay rotası (frontend.blog.detail varsayalım) --}}
                                            <h6 class="post-title"><a
                                                    href="{{ route('frontend.blog.detail', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                                            </h6>
                                        </div>
                                        <div class="sx-post-meta">
                                            <ul>
                                                {{-- Yazar bilgisi için $post->author ilişkisi kullanılabilir --}}
                                                <li class="post-author"><i
                                                        class="fa fa-user"></i>{{ $post->author->title ?? 'Admin' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>Henüz blog yazısı yok.</p>
                            @endforelse
                        @endisset
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 footer-col-3">
                <div class="widget widget_services inline-links">
                    <h5 class="widget-title">Menü</h5>
                    {{-- route() helper'ları ile linkler --}}
                    <ul>
                        <li><a href="{{ route('frontend.about') }}">Hakkımızda</a></li>
                        <li><a href="{{ route('frontend.services') }}">Hizmetlerimiz</a></li>
                        <li><a href="{{ route('frontend.projects') }}">Projeler</a></li>
                        <li><a href="{{ route('frontend.blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('frontend.contact') }}">İletişim</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget widget_address_outer">
                    <h5 class="widget-title">İletişim</h5>
                    {{-- İletişim bilgileri Ayarlar modülünden çekilebilir --}}
                    <ul class="widget_address">
                        <li>Aşağı Öveçler Mh. Lizbon Cd. 15/8 Çankaya/ANKARA</li>
                        <li>info@yildizmuhendislik.com</li>
                        <li><a href="tel:+903124182970">+90 312 418 29 70</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-bottom overlay-wraper">
    <div class="overlay-main"></div>
    <div class="container">
        <div class="row">
            <div class="sx-footer-bot-left">
                {{-- Yıl bilgisini dinamik alalım --}}
                <span class="copyrights-text">© {{ date('Y') }} Yıldız Mühendislik Mimarlık.</span>
            </div>
        </div>
    </div>
</div>