@extends('admin.layouts.app')

@section('title', 'Slayt Listesi')
{{-- Gerekli CSS/JS Kütüphane Yüklemeleri (Galeri'den kopyalanmıştır) --}}
@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Slayt Listesi</h4>
                {{-- Yeni Ekleme Modalı Açma Butonu --}}
              <div class="d-flex gap-2">
    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="slides">
        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
    </button>
    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createSlideModal">
        <i class="ri-add-line align-bottom me-1"></i> Yeni Slayt Ekle
    </button>
</div>
            </div><div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                        {{-- Sürükle-bırak için 'sortable-list' class'ı ve 'data-model' attribute'ü BaseResourceController yapınıza uygun --}}
                        <table class="table table-bordered align-middle table-nowrap mb-0">
                            <thead>
                            <tr>
                                <th style="width: 50px;"><div class="form-check"><input class="form-check-input" type="checkbox" id="selectAllCheckbox"></div></th>
                                <th scope="col" width="10">Sıra</th>
                                <th scope="col" width="10">ID</th>
                                <th scope="col" width="100">Görsel</th>
                                <th scope="col">Başlık / Açıklama</th>
                                <th scope="col" width="150">Bağlantı</th>
                                <th scope="col" width="100">Durumu</th>
                                <th scope="col" width="120">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody class="sortable-list" data-model="slides" id="slidesTable">
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td><div class="form-check"><input class="form-check-input row-checkbox" type="checkbox" value="{{ $item->id }}"></div></td>
                                    {{-- Sıralama Tutamacı --}}
                                    <td class="handle-cell text-center">
                                        <i class="ri-menu-2-line handle"></i>
                                    </td>
                                    <td class="fw-medium">{{ $item->id }}</td>
                                    <td>
                                        @if ($item->image_url)
                                            {{-- Controller'da tanımladığımız 600x400 boyutunu gösterelim --}}
                                            <img src="{{ asset('storage/slide-images/600x400/' . $item->image_url) }}" alt="{{ $item->title }}" class="img-thumbnail" style="max-height: 50px;">
                                        @else
                                            <i class="far fa-image fa-2x text-muted"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->title }}</strong>
                                        @if ($item->subtitle)
                                            <small class="d-block text-muted">{{ $item->subtitle }}</small>
                                        @endif
                                        @if ($item->button_text)
                                            <span class="badge bg-secondary d-block mt-1">{{ $item->button_text }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" target="_blank" class="text-sm">{{ Str::limit($item->link, 30) }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Galeri mantığındaki AJAX'lı durum anahtarı --}}
                                        <div class="form-check form-switch form-switch-lg text-center" dir="ltr">
                                            <input type="checkbox"
                                                   class="form-check-input slide-status-switch"
                                                   data-id="{{ $item->id }}"
                                                {{ $item->status ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="hstack gap-3 fs-15">
                                            {{-- Düzenleme Modalı Açma Butonu --}}
                                            <a href="#"
                                               class="link-primary openEditModal"
                                               data-id="{{ $item->id }}"
                                               data-title="{{ $item->title }}"
                                               data-subtitle="{{ $item->subtitle }}"
                                               data-link="{{ $item->link }}"
                                               data-button-text="{{ $item->button_text }}"
                                               data-order="{{ $item->order }}"
                                               data-image-url="{{ $item->image_url ? asset('storage/slide-images/600x400/' . $item->image_url) : '' }}"
                                               data-update-url="{{ route('admin.slides.update', $item->id) }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editSlideModal">
                                                <i class="ri-settings-4-line"></i>
                                            </a>
                                            {{-- Silme Formu (Galeri mantığına uygun olarak SweetAlert ile) --}}
                                            <form action="{{ route('admin.slides.destroy', $item->id) }}" method="POST" class="d-inline deleteForm">
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
            </div></div></div>{{-- Yeni Slayt Ekleme Modalı (Create) --}}
    @include('admin.slides._create_modal')

    {{-- Slayt Düzenleme Modalı (Edit) --}}
    @include('admin.slides._edit_modal')

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script type="module" src="{{ asset('js/admin/slides/slides.js') }}?v={{ time() }}" defer></script>


    @if(session('success'))
        <script>
            iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
        </script>
    @endif

    <script>
        // Yeni Slayt Ekleme Modal'ı için JS mantığı
        document.querySelectorAll('.openEditModal').forEach(el => {
            el.addEventListener('click', function (e) {
                // Modal'a veri aktarımı
                const updateUrl = this.dataset.updateUrl;

                document.getElementById('editSlideForm').action = updateUrl;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_subtitle').value = this.dataset.subtitle;
                document.getElementById('edit_link').value = this.dataset.link;
                document.getElementById('edit_button_text').value = this.dataset.buttonText;
                document.getElementById('edit_order').value = this.dataset.order;

                // Mevcut görseli gösterme
                const currentImage = document.getElementById('current_image_preview');
                const imageUrl = this.dataset.imageUrl;
                if (imageUrl) {
                    currentImage.src = imageUrl;
                    currentImage.closest('.image-preview-container').style.display = 'block';
                } else {
                    currentImage.closest('.image-preview-container').style.display = 'none';
                }
            });
        });

        // Durum Güncelleme (AJAX - Galeri mantığına uygun)
        document.querySelectorAll('.slide-status-switch').forEach(switchEl => {
            switchEl.addEventListener('change', function() {
                let slideId = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;
                let statusUpdateUrl = '{{ route('admin.common.updateStatus', ['model' => 'slides', 'id' => ':id']) }}';
                statusUpdateUrl = statusUpdateUrl.replace(':id', slideId);

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
                                message: 'Slayt durumu güncellendi',
                                position: 'topRight',
                                timeout: 2000
                            });
                        }
                    });
            });
        });

        // Silme İşlemi (SweetAlert - Galeri mantığına uygun)
        document.querySelectorAll('.deleteForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Emin misin?',
                    text: "Bu Slayt kalıcı olarak silinecek!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Sıralama (SortableJS - Galeri mantığına uygun)
        Sortable.create(document.getElementById('slidesTable'), {
            handle: '.handle-cell',
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                document.querySelectorAll('#slidesTable tr').forEach((row, index) => {
                    order.push({ id: row.getAttribute('data-id'), position: index + 1 });
                });

                fetch('{{ route('admin.common.updateOrder', ['model' => 'slides']) }}', {
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
                                message: 'Slayt sıralaması güncellendi',
                                position: 'topRight',
                                timeout: 2000
                            });
                        } else {
                            iziToast.error({ title: 'Hata', message: data.message || 'Sıralama güncellenemedi', position: 'topRight' });
                        }
                    });
            }
        });

    </script>
@endpush