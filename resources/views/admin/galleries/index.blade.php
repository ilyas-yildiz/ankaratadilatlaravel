@extends('admin.layouts.app')

@section('title', 'Galeri Listesi')
@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Galeri Listesi</h4>
                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#creategalleryModal">
                    <i class="ri-add-line align-bottom me-1"></i> Yeni Ekle
                </button>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-nowrap mb-0 gallery-name-table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID</th>
                                <th scope="col">Başlık</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Durumu</th>
                                <th scope="col">Tarih</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody id="galleriesTable">
                            @foreach ($galleries as $gallery)
                                <tr data-id="{{ $gallery->id }}">
                                    <td class="handle-cell text-center">
                                        <i class="ri-menu-2-line handle"></i>
                                    </td>
                                    <td class="fw-medium">{{ $gallery->id }}</td>
                                    <td>{{ $gallery->name }}</td>
                                    <td>{{ $gallery->slug }}</td>
                                    <td>
                                        <div class="form-check form-switch form-switch-lg text-center" dir="ltr">
                                            <input type="checkbox"
                                                   class="form-check-input gallery-status-switch"
                                                   data-id="{{ $gallery->id }}"
                                                {{ $gallery->status ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <td>{{ $gallery->created_at }}</td>
                                    <td>
                                        <div class="hstack gap-3 fs-15">
                                            <!-- Görsel Ekleme Sayfasına Yönlendiren Buton -->
                                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="link-info add-images">
                                                <i class="ri-image-add-line"></i>
                                            </a>
                                            <a href="#"
                                               class="link-primary openEditModal"
                                               data-id="{{ $gallery->id }}"
                                               data-name="{{ $gallery->name }}"
                                               data-update-url="{{ route('admin.galleries.update', $gallery) }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#exampleModalgrid">
                                                <i class="ri-settings-4-line"></i>
                                            </a>
                                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="d-inline deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="link-danger">
                                                    <i class="ri-delete-bin-5-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div><!-- end col -->


    <!-- modal -->
    <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editgalleryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Galeri Düzenle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="galleryNameInput">Galeri Adı</label>
                            <input type="text" class="form-control" id="galleryNameInput" name="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Yeni Galeri Ekleme Modalı -->
    <div class="modal fade" id="creategalleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.galleries.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Yeni Galeri Ekle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Galeri Adı</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.querySelectorAll('.openEditModal').forEach(el => {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const name = this.dataset.name;
                const updateUrl = this.dataset.updateUrl;

                document.getElementById('galleryNameInput').value = name;
                document.getElementById('editgalleryForm').action = updateUrl;
                // data-bs-target zaten modalı açacak
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.deletegalleryForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Formun direkt submit olmasını engelle

                Swal.fire({
                    title: 'Emin misin?',
                    text: "Bu Galeri kalıcı olarak silinecek!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Onay gelirse form submit edilsin
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    @if(session('success'))
        <script>
            iziToast.success({
                title: 'Başarılı',
                message: '{{ session('success') }}',
                position: 'topCenter',
                timeout: 3000,          // 3 saniye sonra kaybolur
                progressBar: true
            });
        </script>
    @endif
    <script>
        document.querySelectorAll('.gallery-status-switch').forEach(switchEl => {
            switchEl.addEventListener('change', function() {
                let galleryId = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;
                let statusUpdateUrl = '{{ route('admin.common.updateStatus', ['model' => 'galleries', 'id' => ':id']) }}';
                statusUpdateUrl = statusUpdateUrl.replace(':id', galleryId);

                fetch(statusUpdateUrl, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            iziToast.success({
                                title: 'Başarılı',
                                message: 'Galeri durumu güncellendi',
                                position: 'topRight',
                                timeout: 2000
                            });
                        }
                    });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        Sortable.create(document.getElementById('galleriesTable'), {
            handle: '.handle-cell',
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                document.querySelectorAll('#galleriesTable tr').forEach((row, index) => {
                    order.push({ id: row.getAttribute('data-id'), position: index + 1 });
                });

                fetch('{{ route('admin.common.updateOrder', ['model' => 'galleries']) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            iziToast.success({
                                title: 'Başarılı',
                                message: 'Galeri sıralaması güncellendi',
                                position: 'topRight',
                                timeout: 2000
                            });
                        }
                    });
            }
        });

    </script>

@endpush
