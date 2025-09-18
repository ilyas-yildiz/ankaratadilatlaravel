@extends('admin.layouts.app')

@section('title', 'Genel Site Ayarları')

{{-- iziToast CSS'ini ekliyoruz --}}
@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Genel Site Ayarları</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Sitenizin genelinde kullanılacak temel bilgileri bu sayfadan yönetebilirsiniz.</p>

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>Temel Bilgiler</h5>
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Şirket Adı</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $settings['company_name'] ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adres</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">{{ $settings['address'] ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telefon Numaraları (Virgülle ayırın)</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $settings['phone'] ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="fax" class="form-label">Fax</label>
                                    <input type="text" class="form-control" id="fax" name="fax" value="{{ $settings['fax'] ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-posta Adresi</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $settings['email'] ?? '' }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <h5>Sosyal Medya & Harita</h5>
                                <div class="mb-3">
                                    <label for="social_facebook" class="form-label">Facebook URL</label>
                                    <input type="url" class="form-control" id="social_facebook" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="social_twitter" class="form-label">Twitter (X) URL</label>
                                    <input type="url" class="form-control" id="social_twitter" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="social_instagram" class="form-label">Instagram URL</label>
                                    <input type="url" class="form-control" id="social_instagram" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="google_maps" class="form-label">Google Maps Gömme (Embed) Kodu</label>
                                    <textarea class="form-control" id="google_maps" name="google_maps" rows="3">{{ $settings['google_maps'] ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="directions_info" class="form-label">Yol Tarifi</label>
                                    <textarea class="form-control" id="directions_info" name="directions_info" rows="2">{{ $settings['directions_info'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <h5>Footer Kısa Hakkımızda</h5>
                                <div class="mb-3">
{{--                                    <label for="footer_aboutus" class="form-label">Anasayfa Meta Description</label>--}}
                                    <textarea class="form-control" id="footer_aboutus" name="footer_aboutus" rows="3">{{ $settings['footer_aboutus'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <h5>Anasayfa SEO Ayarları</h5>
                                <div class="mb-3">
                                    <label for="homepage_meta_description" class="form-label">Anasayfa Meta Description</label>
                                    <textarea class="form-control" id="homepage_meta_description" name="homepage_meta_description" rows="3">{{ $settings['homepage_meta_description'] ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="homepage_meta_keywords" class="form-label">Anasayfa Meta Keywords</label>
                                    <textarea class="form-control" id="homepage_meta_keywords" name="homepage_meta_keywords" rows="2">{{ $settings['homepage_meta_keywords'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- YENİ EKLENEN BÖLÜM --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    @if(session('success'))
        <script>
            // Sayfa yüklendiğinde session'da 'success' anahtarı varsa bu bildirimi gösterir.
            document.addEventListener('DOMContentLoaded', function () {
                iziToast.success({
                    title: 'Başarılı!',
                    message: '{{ session('success') }}',
                    position: 'topRight'
                });
            });
        </script>
    @endif
@endpush
