@extends('admin.layouts.app')

{{-- Başlığı $routeName değişkeni ile dinamik hale getirdik --}}
@section('title', Str::ucfirst(Str::plural($routeName)) . ' Listesi')

@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">{{ Str::ucfirst(Str::plural($routeName)) }} Listesi</h4>
                <div class="d-flex gap-2">
                    {{-- Butonları jenerik hale getiriyoruz --}}
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="{{ $routeName }}">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>
                    @if($routeName === 'blogs') {{-- AI butonu sadece bloglar için görünsün --}}
                    <a href="{{ route('admin.blogs.createWithAi') }}" class="btn btn-primary"><i class="ri-magic-line align-bottom me-1"></i> AI ile Oluştur</a>
                    @endif
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Ekle
                    </button>
                </div>
            </div><div class="card-body">
                {{-- Tabloyu partial dosyasından çağırıyoruz --}}
                @include('admin.' . $viewPath . '.partials._table')
            </div>
        </div>
    </div>

    {{-- Modalları partial dosyalarından çağırıyoruz --}}
    @include('admin.' . $viewPath . '.modals._create_modal')
    @include('admin.' . $viewPath . '.modals._edit_modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@5/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- YENİ EKLENECEK KOD BAŞLANGICI --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            tinymce.init({
                selector: '.tinymce-editor', // Bu class'a sahip tüm textarea'ları seç
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                toolbar_mode: 'floating',
                // Diğer TinyMCE ayarlarınızı buraya ekleyebilirsiniz.
            });
        });
    </script>
    {{-- YENİ EKLENECEK KOD BİTİŞİ --}}
    @if(session('success'))
        <script>iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });</script>
    @endif

    {{-- AI ile oluşturmadan gelen veriyi işleyen ve modalı açan script --}}
    @if(session('ai_generated_data'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    const aiData = @json(session('ai_generated_data'));
                    const createModalEl = document.getElementById('createModal'); // <-- Doğru Modal ID'si

                    if (createModalEl) {
                        const createModal = new bootstrap.Modal(createModalEl);

                        // Form elemanlarını modal içinden bularak daha güvenli hale getiriyoruz.
                        const titleInput = createModalEl.querySelector('[name="title"]');
                        if (titleInput) {
                            titleInput.value = aiData.title || '';
                        }

                        const editor = tinymce.get('create_content'); // <-- Doğru TinyMCE ID'si
                        if (editor) {
                            editor.setContent(aiData.content || '');
                        } else {
                            // Editör henüz hazır değilse, küçük bir gecikmeyle tekrar dene
                            setTimeout(() => {
                                tinymce.get('create_content')?.setContent(aiData.content || '');
                            }, 500);
                        }

                        createModal.show();
                    }
                }, 500);
            });
        </script>
    @endif

    {{-- Artık modüle özel JS dosyası yerine, tüm modüllerin kullanacağı TEK BİR JS dosyası olacak --}}
    <script type="module" src="{{ asset('js/admin/common/resource-handler.js') }}?v={{ time() }}" defer></script>
@endpush

