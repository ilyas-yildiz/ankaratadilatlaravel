@extends('admin.layouts.app')

@section('title', 'Slayt Listesi')

@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Slayt Listesi</h4>
                <div class="d-flex gap-2">
                    {{-- Toplu silme butonu genel yapıda kalabilir --}}
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="slides">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>

                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Ekle
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- Tabloyu partial dosyasından çağıracağız --}}
{{--                <p>Slaytlar burada listelenecek. Lütfen `_table.blade.php` dosyasını oluşturun.</p>--}}
                 @include('admin.slides.partials._table')
            </div>
        </div>
    </div>
    @include('admin.slides.modals._create_modal')
    @include('admin.slides.modals._edit_modal')
@endsection

@push('scripts')
    {{-- Bildirimler ve sıralama için gerekli kütüphaneler --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- Session'dan gelen başarı mesajını göstermek için --}}
    @if(session('success'))
        <script>
            iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
        </script>
    @endif

    {{-- Tüm modüllerin kullandığı ortak JavaScript dosyası (silme, durum güncelleme vb. için) --}}
    <script type="module" src="{{ asset('js/admin/common/resource-handler.js') }}?v={{ time() }}" defer></script>
@endpush
