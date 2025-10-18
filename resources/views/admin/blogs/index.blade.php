@extends('admin.layouts.app')

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
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="{{ $routeName }}">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>
                    @if($routeName === 'blogs')
                    <a href="{{ route('admin.blogs.createWithAi') }}" class="btn btn-primary"><i class="ri-magic-line align-bottom me-1"></i> AI ile Oluştur</a>
                    @endif
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Ekle
                    </button>
                </div>
            </div>
            <div class="card-body">
                @include('admin.' . $viewPath . '.partials._table')
            </div>
        </div>
    </div>

    @include('admin.' . $viewPath . '.modals._create_modal')
    @include('admin.' . $viewPath . '.modals._edit_modal')
@endsection

@push('styles')
<style>
    .note-editor .note-toolbar .note-btn i { color: #212529 !important; }
    .note-editor .note-toolbar .note-btn:hover i { color: #000 !important; }
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
            });
        </script>
    @endif

    {{-- AI ile oluşturmadan gelen veriyi işleyen script --}}
    @if(session('ai_generated_data'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const aiData = {!! json_encode(session('ai_generated_data')) !!};
                const createModalEl = document.getElementById('createModal');
                if (createModalEl && aiData) {
                    const createModal = new bootstrap.Modal(createModalEl);
                    createModalEl.querySelector('[name="title"]').value = aiData.title || '';
                    createModalEl.querySelector('[name="meta_description"]').value = aiData.meta_description || '';
                    createModalEl.querySelector('[name="meta_keywords"]').value = aiData.meta_keywords || '';
                    createModalEl.addEventListener('shown.bs.modal', function () {
                        // GÜNCELLENDİ: jQuery ve Summernote metodu
                        $('#create_content').summernote('code', aiData.content || '');
                    }, { once: true });
                    createModal.show();
                }
            });
        </script>
    @endif

    {{-- Tüm modüller için TEK ortak JS dosyasını çağırıyoruz --}}
    <script src="{{ asset('js/admin/common/resource-handler.js') }}?v={{ time() }}" defer></script>
@endpush