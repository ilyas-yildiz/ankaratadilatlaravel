<footer class="footer--section">
    <div class="footer--widgets pd--30-0 bg--color-2">
        <div class="container">
            <div class="row AdjustRow">
                <div class="col-md-3 col-xs-6 col-xxs-12 ptop--30 pbottom--30">
                    <div class="widget">
                        <div class="widget--title">
                            <h2 class="h4">Hakkımızda</h2>
                            <i class="icon fa fa-exclamation"></i>
                        </div>
                        <div class="about--widget">
                            <div class="content mb-10">
                                <p>{{ $settings['footer_aboutus'] ?? 'Site hakkındaki kısa açıklama metni buraya gelecek.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6 col-xxs-12 ptop--30 pbottom--30">
                    <div class="widget">
                        <div class="widget--title">
                            <h2 class="h4">Faydalı Linkler</h2>
                            <i class="icon fa fa-expand"></i>
                        </div>
                        <div class="links--widget">
                            <ul class="nav">
                                {{-- Bu linkler dinamik olarak veya manuel olarak eklenebilir --}}
                                <li><a href="#" class="fa-angle-right">Kullanım Koşulları</a></li>
                                <li><a href="#" class="fa-angle-right">Gizlilik Politikası</a></li>
                                <li><a href="#" class="fa-angle-right">İletişim</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6 col-xxs-12 ptop--30 pbottom--30">
                    <div class="widget">
                        <div class="widget--title">
                            <h2 class="h4">Kategoriler</h2>
                            <i class="icon fa fa-folder-open-o"></i>
                        </div>
                        <div class="links--widget">
                            <ul class="nav">
                                {{-- Kategorileri dinamik olarak çekiyoruz --}}
                                @forelse($categories as $category)
                                    <li><a href="{{ route('frontend.category', $category->slug) }}" class="fa-angle-right">{{ $category->name }}</a></li>
                                @empty
                                    <li><a href="javascript:void(0);" class="fa-angle-right">Kategori bulunamadı.</a></li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6 col-xxs-12 ptop--30 pbottom--30">
                    <div class="widget">
                        <div class="widget--title">
                            <h2 class="h4">Bize Ulaşın</h2>
                            <i class="icon fa fa-user-o"></i>
                        </div>
                        <div class="links--widget">
                            <ul class="nav">
                                <li><i class="fa fa-map"></i> <span class="pl-10">{{ $settings['address'] }}</span></li>
                                <li><i class="fa fa-envelope-o"></i> <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></li>
                                <li><i class="fa fa-phone"></i> <a href="tel:{{ $settings['phone'] }}">{{ $settings['phone'] }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer--copyright bg--color-3">
        <div class="social--bg bg--color-1"></div>
        <div class="container">
            <p class="text float--left">&copy; {{ date('Y') }} {{ $settings['company_name'] }} - Tüm Hakları Saklıdır.</p>
            <ul class="nav social float--right">
                <li><a href="{{ $settings['social_facebook'] }}"><i class="fa fa-facebook"></i></a></li>
                <li><a href="{{ $settings['social_twitter'] }}"><i class="fa fa-twitter"></i></a></li>
                <li><a href="{{ $settings['social_instagram'] }}"><i class="fa fa-instagram"></i></a></li>
            </ul>

        </div>
    </div>
</footer>

