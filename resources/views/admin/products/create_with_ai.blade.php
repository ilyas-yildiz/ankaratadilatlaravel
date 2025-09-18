@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Yapay Zeka ile Blog İçeriği Oluştur</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <p class="mb-3">
                            Oluşturmak istediğiniz blog yazısının konusunu, anahtar kelimelerini ve temel hatlarını aşağıdaki metin alanına girin.
                            Yapay zeka, bu girdiyi kullanarak bir başlık ve içerik taslağı oluşturacaktır. Oluşturulan taslak, son kontrolleriniz ve düzenlemeleriniz için blog ekleme formuna aktarılacaktır.
                        </p>

                        @if ($errors->any() || session('error'))
                            <div class="alert alert-danger">
                                <ul>
                                    @if (session('error'))
                                        <li>{{ session('error') }}</li>
                                    @endif
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="generateAiContentForm" action="{{ route('admin.blogs.generateWithAi') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="prompt">Blog Yazısı Konusu ve Ana Hatları</label>
                                <textarea name="prompt" id="prompt" class="form-control" rows="15" placeholder="Örn: Türk hukukunda çekişmeli boşanma davasının aşamalarını ve avukatın rolünü anlatan detaylı bir blog yazısı hazırla. Yazı, dava dilekçesi, ön inceleme, tahkikat ve karar aşamalarını içermeli.">{{ old('prompt') }}</textarea>
                            </div>

                            <button type="submit" id="generateButton" class="btn btn-primary mt-3">
                                <i class="fas fa-magic mr-2"></i> İçerik Oluştur ve Düzenlemeye Geç
                            </button>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('generateAiContentForm');
            if (form) {
                form.addEventListener('submit', function(event) {
                    // Tarayıcının varsayılan gönderme işlemini anlık olarak durdur
                    event.preventDefault();

                    const button = document.getElementById('generateButton');
                    if (button) {
                        button.disabled = true;
                        button.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Oluşturuluyor... Lütfen bekleyin.
                    `;
                    }

                    // Tarayıcının butonu güncellemesine zaman tanımak için
                    // formu küçük bir gecikmeyle programatik olarak gönder.
                    setTimeout(() => {
                        form.submit();
                    }, 100);
                });
            }
        });
    </script>
@endpush

