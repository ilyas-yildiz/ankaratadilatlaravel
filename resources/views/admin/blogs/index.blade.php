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

    {{-- GÜNCELLENEN BÖLÜM BAŞLANGICI: TinyMCE init --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            tinymce.init({
                selector: '.tinymce-editor',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table directionality emoticons template paste textpattern',
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link image media | code preview',
                autosave_ask_before_unload: false,
                menubar: false,
                branding: false,

                relative_urls: false,

                file_picker_callback: function (callback, value, meta) {
                    let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    // NİHAİ DÜZELTME: Bu parametreler, dosya yöneticisine TinyMCE 5 ile konuştuğunu söyler
                    // ve doğru iletişim yöntemini (postMessage) kullanmasını sağlar.
                    let filemanager_url = '/admin/laravel-filemanager?editor=tinymce5&type=' + meta.filetype;

                    tinymce.activeEditor.windowManager.openUrl({
                        url : filemanager_url,
                        title : 'Dosya Yöneticisi',
                        width : x * 0.8,
                        height : y * 0.8,
                        onMessage: (api, message) => {
                            callback(message.content);
                        }
                    });
                },

                paste_data_images: true,
                images_upload_handler: function (blobInfo, success, failure) {
                    let xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', "{{ route('admin.common.uploadImage') }}");
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    xhr.onload = function() {
                        let json;
                        if (xhr.status != 200) {
                            failure('HTTP Hatası: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Geçersiz JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };

                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    xhr.send(formData);
                }
            });
        });
    </script>
    {{-- GÜNCELLENEN BÖLÜM SONU --}}

    @if(session('success'))
        <script>iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });</script>
    @endif

    {{-- AI ile oluşturmadan gelen veriyi işleyen ve modalı açan script --}}
    @if(session('ai_generated_data'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const aiData = {!! json_encode(session('ai_generated_data')) !!};
                const createModalEl = document.getElementById('createModal');

                if (createModalEl && aiData) {
                    const createModal = new bootstrap.Modal(createModalEl);

                    const titleInput = createModalEl.querySelector('[name="title"]');
                    if (titleInput) {
                        titleInput.value = aiData.title || '';
                    }

                    // --- YENİ: SEO Alanlarını Doldurma ---
                    const descInput = createModalEl.querySelector('[name="meta_description"]');
                    if (descInput) {
                        descInput.value = aiData.meta_description || '';
                    }
                    const keywordsInput = createModalEl.querySelector('[name="meta_keywords"]');
                    if (keywordsInput) {
                        keywordsInput.value = aiData.meta_keywords || '';
                    }

                    function populateTinyMCE(retryCount = 0) {
                        if (retryCount > 50) {
                            console.error("TinyMCE editörü 5 saniye içinde yüklenemedi.");
                            return;
                        }
                        const editor = tinymce.get('create_content');
                        if (editor && editor.initialized) {
                            editor.setContent(aiData.content || '');
                        } else {
                            setTimeout(() => populateTinyMCE(retryCount + 1), 100);
                        }
                    }

                    const listenerOptions = { once: true };
                    createModalEl.addEventListener('shown.bs.modal', populateTinyMCE, listenerOptions);

                    createModal.show();
                }
            });
        </script>
    @endif


    {{-- Artık modüle özel JS dosyası yerine, tüm modüllerin kullanacağı TEK BİR JS dosyası olacak --}}
    <script type="module" src="{{ asset('js/admin/common/resource-handler.js') }}?v={{ time() }}" defer></script>
@endpush

