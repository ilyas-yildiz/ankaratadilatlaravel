@extends('admin.layouts.app')

{{-- Başlık güncellendi --}}
@section('title', 'Projeler Yönetimi')

@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                 {{-- Başlık güncellendi --}}
                <h4 class="card-title mb-0 flex-grow-1">Projeler Listesi</h4>
                <div class="d-flex gap-2">
                    {{-- Toplu Sil Butonu --}}
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="{{ $routeName }}">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>
                    {{-- Yeni Ekle Butonu --}}
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Proje Ekle
                    </button>
                </div>
            </div><div class="card-body">
                {{-- Tabloyu partial dosyasından çağırıyoruz (yol doğru) --}}
                @include('admin.' . $viewPath . '.partials._table')
            </div>
        </div>
    </div>

    {{-- Modalları partial dosyalarından çağırıyoruz (yol doğru) --}}
    @include('admin.' . $viewPath . '.modals._create_modal')
    @include('admin.' . $viewPath . '.modals._edit_modal')
@endsection

{{-- Gerekli stiller ve scriptler aynı kalıyor --}}
@push('styles')
<style>
    .note-editor .note-toolbar .note-btn i { color: #212529 !important; }
    .note-editor .note-toolbar .note-btn:hover i { color: #000 !important; }
    .handle { cursor: move; }
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    {{-- Sıralama JS'i aktif kalıyor --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
            });
        </script>
    @endif

    {{-- Ortak JS dosyası aynı kalıyor --}}
    <script src="{{ asset('js/admin/common/resource-handler.js') }}?v={{ time() }}" defer></script>
@endpush