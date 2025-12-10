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
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 50px;">#</th>
                            <th scope="col" style="width: 80px;">Görsel</th>
                            <th scope="col">Kategori Adı</th>
                            <th scope="col">Üst Kategori</th>
                            <th scope="col">Slug / Renk</th>
                            <th scope="col">Durumu</th>
                            <th scope="col" style="width: 150px;">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody id="categoriesTable">
                        @forelse ($categories as $category)
                            <tr data-id="{{ $category->id }}">
                                <td class="handle-cell text-center" style="cursor: move;">
                                    <i class="ri-drag-move-2-line fs-20 text-muted"></i>
                                </td>
                                <td>
                                    @if($category->image_url)
                                        <img src="{{ asset('storage/category-images/100x100/' . $category->image_url) }}" alt="" class="avatar-xs rounded-circle">
                                    @else
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                {{ strtoupper(substr($category->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $category->name }}</span>
                                    @if($category->description)
                                        <i class="ri-information-fill text-muted" title="{{ Str::limit($category->description, 50) }}"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge bg-soft-info text-info">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge" style="background-color: {{ $category->color }};">{{ $category->color }}</span>
                                        <small class="text-muted">{{ $category->slug }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch form-switch-lg text-center" dir="ltr">
                                        <input type="checkbox" class="form-check-input category-status-switch" data-id="{{ $category->id }}" {{ $category->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="hstack gap-3 fs-15">
                                        {{-- Edit butonu artık AJAX ile modal içeriğini dolduracak --}}
                                        <a href="javascript:void(0);" 
                                           class="link-primary openEditModal" 
                                           data-url="{{ route('admin.categories.edit', $category->id) }}"
                                           data-update-url="{{ route('admin.categories.update', $category->id) }}">
                                            <i class="ri-settings-4-line"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline deleteCategoryForm">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="link-danger border-0 bg-transparent p-0">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Kayıt bulunamadı.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Yeni Kategori Ekle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kategori Rengi</label>
                                    <input type="color" class="form-control form-control-color w-100" name="color" value="#405189" title="Kategori Rengi Seç">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Üst Kategori (Opsiyonel)</label>
                            <select class="form-control" name="parent_id">
                                <option value="">Yok (Ana Kategori)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kapak Görseli</label>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-muted">SEO Ayarları</h6>
                        <div class="mb-3">
                            <label class="form-label">Meta Başlık (Title)</label>
                            <input type="text" class="form-control" name="meta_title" maxlength="70">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Açıklama (Description)</label>
                            <textarea class="form-control" name="meta_description" maxlength="255" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            {{-- Form action JS ile dolacak --}}
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kategori Düzenle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div id="editModalBody" class="modal-body">
                        {{-- AJAX ile resources/views/admin/categories/modals/_form.blade.php buraya yüklenecek --}}
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Yükleniyor...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert ve IziToast scriptleri aynen kalsın --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        // EDIT MODAL MANTIĞI (GÜNCELLENDİ)
        document.querySelectorAll('.openEditModal').forEach(el => {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.dataset.url; // Controller edit rotası
                const updateUrl = this.dataset.updateUrl; // Controller update rotası
                const modalEl = document.getElementById('editCategoryModal');
                const formEl = document.getElementById('editCategoryForm');
                const bodyEl = document.getElementById('editModalBody');

                // Form action güncelle
                formEl.action = updateUrl;

                // Modal içeriğini temizle ve loader koy
                bodyEl.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
                
                // Modalı aç
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                // AJAX ile formu çek
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    bodyEl.innerHTML = html;
                })
                .catch(err => {
                    bodyEl.innerHTML = '<p class="text-danger text-center">Form yüklenirken hata oluştu.</p>';
                    console.error(err);
                });
            });
        });

        // Status switch ve Delete scriptleri önceki kodundaki gibi kalabilir.
        // Sortable script'i de önceki gibi kalabilir.
        // (Buraya kopyalamadım ama senin mevcut kodundaki scriptler çalışacaktır, 
        // sadece selectorların ID'lerinin tuttuğundan emin ol)
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