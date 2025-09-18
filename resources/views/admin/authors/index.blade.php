@extends('admin.layouts.app')

@section('title', 'Yazarlar Listesi')

@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Yazarlar Listesi</h4>
                <div class="d-flex gap-2">
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="authors">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createAuthorModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Yazar Ekle
                    </button>
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-nowrap mb-0">
                            <thead>
                            <tr>
                                <th style="width: 50px;"><div class="form-check"><input class="form-check-input" type="checkbox" id="selectAllCheckbox"></div></th>
                                <th style="width: 50px;">Sıra</th>
                                <th scope="col" style="width: 50px;">ID</th>
                                <th scope="col">Görsel</th>
                                <th scope="col">Adı Soyadı</th>
                                <th scope="col">Durumu</th>
                                <th scope="col">Tarih</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody id="authorsTable">
                            @forelse ($authors as $author)
                                <tr data-id="{{ $author->id }}">
                                    <td><div class="form-check"><input class="form-check-input row-checkbox" type="checkbox" value="{{ $author->id }}"></div></td>
                                    <td class="handle-cell text-center"><i class="ri-menu-2-line handle"></i></td>
                                    <td class="fw-medium">{{ $author->id }}</td>
                                    <td>
                                        @if($author->img_url)
                                            <img src="{{ asset('storage/authors/100x100/' . $author->img_url) }}" alt="{{ $author->title }}" class="img-fluid rounded" style="max-width: 60px; height: auto;">
                                        @else
                                            <img src="https://placehold.co/60x60/EFEFEF/AAAAAA&text=Görsel+Yok" alt="Görsel Yok" class="img-fluid rounded">
                                        @endif
                                    </td>
                                    <td>{{ $author->title }}</td>
                                    <td>
                                        <div class="form-check form-switch form-switch-lg text-center" dir="ltr">
                                            <input type="checkbox" class="form-check-input status-switch" data-id="{{ $author->id }}" data-model="authors" {{ $author->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ $author->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="hstack gap-3 fs-15">
                                            {{-- EN ÖNEMLİ DEĞİŞİKLİK: Düzenle butonu yeni, basit JS mantığına göre güncellendi --}}
                                            <a href="#" class="link-primary edit-author-btn"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editAuthorModal"
                                               data-update-url="{{ route('admin.authors.update', $author->id) }}"
                                               data-title="{{ $author->title }}"
                                               data-description="{{ $author->description }}"
                                               data-image-url="{{ $author->img_url ? asset('storage/authors/263x272/' . $author->img_url) : '' }}"
                                            >
                                                <i class="ri-settings-4-line"></i>
                                            </a>
                                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="d-inline deleteAuthorForm">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="link-danger" style="background:none; border:none; padding:0;"><i class="ri-delete-bin-5-line"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Henüz hiç yazar eklenmemiş.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->

    @include('admin.authors.modals._create_modal')
    @include('admin.authors.modals._edit_modal')
@endsection

@push('scripts')
    <!-- Gerekli kütüphaneler -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.7/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @if(session('success'))
        <script>
            iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
        </script>
    @endif

    <script type="module" src="{{ asset('js/admin/authors/authors.js') }}?v={{ time() }}" defer></script>
@endpush

