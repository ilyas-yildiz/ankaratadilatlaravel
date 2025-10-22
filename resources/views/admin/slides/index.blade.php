@extends('admin.layouts.app')

{{-- Başlık güncellendi --}}
@section('title', 'Slider Yönetimi')

@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                {{-- Başlık güncellendi --}}
                <h4 class="card-title mb-0 flex-grow-1">Slider Listesi</h4>
                <div class="d-flex gap-2">
                    {{-- Toplu Sil Butonu --}}
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="{{ $routeName }}">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>
                    {{-- Yeni Ekle Butonu --}}
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                        data-bs-target="#createModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Slide Ekle
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
        /* İsteğe bağlı: Tablodaki görsel önizlemesini küçültmek için */
        .slide-thumbnail {
            max-width: 100px;
            height: auto;
        }

        .handle {
            cursor: move;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
            });
        </script>
    @endif

    {{-- Ortak JS dosyası aynı kalıyor --}}
    <script src="{{ asset('js/admin/common/resource-handler.js') }}?v={{ time() }}" defer></script>
@endpush