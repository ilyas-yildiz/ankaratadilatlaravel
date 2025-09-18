@extends('admin.layouts.app')

@section('title', 'Kategori Listesi')
@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Kategori Listesi</h4>
                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="ri-add-line align-bottom me-1"></i> Yeni Ekle
                </button>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-nowrap mb-0">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID</th>
                                <th scope="col">Başlık</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Tarih</th>
                                <th scope="col">Durumu</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody id="categoriesTable">
                            @foreach ($categories as $category)
                            <tr data-id="{{ $category->id }}">
                                <td class="handle-cell text-center">
                                    <i class="ri-menu-2-line handle"></i>
                                </td>
                                <td class="fw-medium">{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->created_at }}</td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg text-center" dir="ltr">
                                        <input type="checkbox"
                                               class="form-check-input category-status-switch"
                                               data-id="{{ $category->id }}"
                                            {{ $category->status ? 'checked' : '' }}
                                        >
                                    </div>
                                </td>
                                <td>
                                    <div class="hstack gap-3 fs-15">
                                        <a href="#"
                                           class="link-primary openEditModal"
                                           data-id="{{ $category->id }}"
                                           data-name="{{ $category->name }}"
                                           data-update-url="{{ route('admin.categories.update', $category) }}"
                                           data-bs-toggle="modal"
                                           data-bs-target="#exampleModalgrid">
                                            <i class="ri-settings-4-line"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline deleteForm">
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
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kategori Düzenle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="categoryNameInput">Kategori Adı</label>
                            <input type="text" class="form-control" id="categoryNameInput" name="name">
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
    <!-- Yeni Kategori Ekleme Modalı -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Yeni Kategori Ekle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Kategori Adı</label>
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

                document.getElementById('categoryNameInput').value = name;
                document.getElementById('editCategoryForm').action = updateUrl;
                // data-bs-target zaten modalı açacak
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.deleteCategoryForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Formun direkt submit olmasını engelle

                Swal.fire({
                    title: 'Emin misin?',
                    text: "Bu kategori kalıcı olarak silinecek!",
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
        document.querySelectorAll('.category-status-switch').forEach(switchEl => {
            switchEl.addEventListener('change', function() {
                let categoryId = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;
                let statusUpdateUrl = '{{ route('admin.common.updateStatus', ['model' => 'categories', 'id' => ':id']) }}';
                statusUpdateUrl = statusUpdateUrl.replace(':id', categoryId);

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
                                message: 'Kategori durumu güncellendi',
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
        Sortable.create(document.getElementById('categoriesTable'), {
            handle: '.handle-cell', // artık tüm hücreyi handle olarak alıyoruz
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                document.querySelectorAll('#categoriesTable tr').forEach((row, index) => {
                    order.push({ id: row.getAttribute('data-id'), position: index + 1 });
                });

                fetch('{{ route('admin.common.updateOrder', ['model' => 'categories']) }}', {
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
                                message: 'Kategori sıralaması güncellendi',
                                position: 'topRight',
                                timeout: 2000
                            });
                        }
                    });
            }
        });

    </script>

@endpush
