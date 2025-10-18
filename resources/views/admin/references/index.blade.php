@extends('admin.layouts.app')

@section('title', 'Referans Listesi')
@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Referans Listesi</h4>
                <div class="d-flex gap-2">
                    {{-- bulkDeleteBtn'deki data-model zaten doğru: references --}}
                    <button id="bulkDeleteBtn" type="button" class="btn btn-danger d-none" data-model="references">
                        <i class="ri-delete-bin-2-line"></i> Seçilenleri Sil
                    </button>
                    {{-- Modaldaki ID ismini Referans'a göre güncelledik --}}
                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#createReferenceModal">
                        <i class="ri-add-line align-bottom me-1"></i> Yeni Referans Ekle
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-nowrap mb-0">
                            <thead>
                            <tr>
                                <th style="width: 50px;"><div class="form-check"><input class="form-check-input" type="checkbox" id="selectAllCheckbox"></div></th>
                                <th scope="col" width="10">Sıra</th>
                                <th scope="col" width="10">ID</th>
                                <th scope="col" width="100">Görsel</th>
                                {{-- Migration'a göre güncellendi --}}
                                <th scope="col">İsim / Başlık / Açıklama</th> 
                                <th scope="col" width="150">Website Bağlantısı</th> 
                                <th scope="col" width="100">Durumu</th>
                                <th scope="col" width="120">İşlemler</th>
                            </tr>
                            </thead>
                            {{-- data-model ve id doğru: references --}}
                            <tbody class="sortable-list" data-model="references" id="referencesTable">
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td><div class="form-check"><input class="form-check-input row-checkbox" type="checkbox" value="{{ $item->id }}"></div></td>
                                    <td class="handle-cell text-center">
                                        <i class="ri-menu-2-line handle"></i>
                                    </td>
                                    <td class="fw-medium">{{ $item->id }}</td>
                                    <td>
                                        @if ($item->image_url)
                                            {{-- GÖRSEL YOLU GÜNCELLENDİ (reference-images) --}}
                                            <img src="{{ asset('storage/reference-images/600x400/' . $item->image_url) }}" alt="{{ $item->name }}" class="img-thumbnail" style="max-height: 50px;">
                                        @else
                                            <i class="far fa-image fa-2x text-muted"></i>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- SÜTUN İÇERİKLERİ MIGRATION'A GÖRE GÜNCELLENDİ --}}
                                        <strong class="d-block">{{ $item->name }}</strong> 
                                        @if ($item->title)
                                             <small class="d-block text-muted">{{ $item->title }}</small>
                                        @endif
                                        @if ($item->description)
                                            <p class="text-sm mb-0">{{ Str::limit($item->description, 50) }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Bağlantı sütunu adı website_url olarak değiştirildi --}}
                                        @if ($item->website_url)
                                            <a href="{{ $item->website_url }}" target="_blank" class="text-sm">{{ Str::limit($item->website_url, 30) }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch form-switch-lg text-center" dir="ltr">
                                            {{-- class ismi referans'a göre güncellendi --}}
                                            <input type="checkbox"
                                                 class="form-check-input reference-status-switch"
                                                 data-id="{{ $item->id }}"
                                               {{ $item->status ? 'checked' : '' }}
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="hstack gap-3 fs-15">
                                            {{-- Düzenleme Modalındaki data-* değerleri migration'a göre güncellendi --}}
                                            <a href="#"
                                                class="link-primary openEditModal"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}" 
                                                data-title="{{ $item->title }}"
                                                data-description="{{ $item->description }}" 
                                                data-website-url="{{ $item->website_url }}" 
                                                data-order="{{ $item->order }}"
                                                data-image-url="{{ $item->image_url ? asset('storage/reference-images/600x400/' . $item->image_url) : '' }}"
                                                data-update-url="{{ route('admin.references.update', $item->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editReferenceModal">
                                                <i class="ri-settings-4-line"></i>
                                            </a>
                                            <form action="{{ route('admin.references.destroy', $item->id) }}" method="POST" class="d-inline deleteForm">
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
            </div>
        </div>
    </div>

    {{-- Modal isimleri Referans'a göre güncellendi --}}
    @include('admin.references._create_modal')
    @include('admin.references._edit_modal')

@endsection

@push('scripts')
    {{-- Kütüphaneler --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    {{-- JS dosya yolu ve adı doğru: references.js --}}
    <script type="module" src="{{ asset('js/admin/references/references.js') }}?v={{ time() }}" defer></script> 

    @if(session('success'))
        <script>
            iziToast.success({ title: 'Başarılı!', message: '{{ session('success') }}', position: 'topRight' });
        </script>
    @endif

    <script>
        // Düzenleme Modalı için JS mantığı
        document.querySelectorAll('.openEditModal').forEach(el => {
            el.addEventListener('click', function (e) {
                // Modal'a veri aktarımı (Migration'a göre güncellendi)
                const updateUrl = this.dataset.updateUrl;

                document.getElementById('editReferenceForm').action = updateUrl;
                document.getElementById('edit_name').value = this.dataset.name; // name eklendi
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_description').value = this.dataset.description; // description eklendi
                document.getElementById('edit_website_url').value = this.dataset.websiteUrl; // website_url güncellendi
                
                
                // Slide modülünden kalan edit_subtitle ve edit_button_text kaldırılmalıdır. (Bunlar _edit_modal içinde düzeltilmelidir)

                // Mevcut görseli gösterme (ID'nin _edit_modal içinde 'current_image_preview' olduğunu varsayıyoruz)
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

        // Durum Güncelleme (AJAX)
        document.querySelectorAll('.reference-status-switch').forEach(switchEl => {
            switchEl.addEventListener('change', function() {
                let referenceId = this.getAttribute('data-id');
                let status = this.checked ? 1 : 0;
                let statusUpdateUrl = '{{ route('admin.common.updateStatus', ['model' => 'references', 'id' => ':id']) }}';
                statusUpdateUrl = statusUpdateUrl.replace(':id', referenceId);

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
                                message: 'Referans durumu güncellendi',
                                position: 'topRight',
                                timeout: 2000
                            });
                        }
                    });
            });
        });

        // Silme İşlemi (SweetAlert) - Değişiklik Yok
        document.querySelectorAll('.deleteForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Emin misin?',
                    text: "Bu Referans kalıcı olarak silinecek!",
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

        // Sıralama (SortableJS) - Değişiklik Yok (Model adı zaten references olarak doğru)
        Sortable.create(document.getElementById('referencesTable'), {
            handle: '.handle-cell',
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                document.querySelectorAll('#referencesTable tr').forEach((row, index) => {
                    order.push({ id: row.getAttribute('data-id'), position: index + 1 });
                });

                fetch('{{ route('admin.common.updateOrder', ['model' => 'references']) }}', {
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
                                message: 'Referans sıralaması güncellendi',
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