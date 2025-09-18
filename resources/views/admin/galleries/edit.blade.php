{{--
Bu Blade dosyası, yönetici panelindeki tek bir galeriyi düzenleme sayfasını oluşturur.
Galerinin adını güncelleme, yeni görseller ekleme (Dropzone ile), mevcut görselleri sıralama (SortableJS ile)
ve görselleri silme/yönetme (SweetAlert ve Fetch API ile) gibi işlevleri içerir.
--}}

{{-- Ana admin layout'unu genişletiyoruz --}}
@extends('admin.layouts.app')

{{-- Sayfa başlığını ayarlıyoruz --}}
@section('title', 'Galeri Düzenle')

{{-- Gerekli CSS kütüphanelerini layout'un 'head' kısmına ekliyoruz --}}
@push('izitoastcss')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
@endpush
@push('dropzonecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
@endpush

{{-- Sayfa içeriği burada başlıyor --}}
@section('content')

    <div class="container py-4">

        {{-- Galeri Adını Güncelleme Formu --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Galeri Düzenle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST" class="row g-3 align-items-center">
                    @csrf
                    @method('PUT')
                    <div class="col-auto">
                        <label for="name" class="col-form-label">Galeri Adı:</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="name" name="name" value="{{ old('name', $gallery->name) }}" class="form-control" required>
                        @error('name')
                        <p class="text-danger small fst-italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-primary btn-animation waves-effect waves-light" data-text="Kaydet">
                            <span>Galeri Adını Güncelle</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Galeri Görsellerini Yönetme Alanı --}}
        <div class="col-xl-12 mt-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Galeri Görselleri</h4>
                </div>
                <div class="card-body">

                    {{-- Dropzone ile Yeni Görsel Yükleme Alanı --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Yeni Görsel Ekle:</label>
                        <div id="dropzone-area" class="dropzone border border-2 border-secondary rounded p-4 text-center"></div>
                    </div>

                    <hr class="my-4">

                    {{-- Mevcut Galeri Görsellerini Listeleyen Tablo --}}
                    <div class="live-preview">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">Sıra</th>
                                    <th scope="col" style="width: 5%;">ID</th>
                                    <th scope="col" style="width: 15%;">Görsel</th>
                                    <th scope="col">Görsel Adı</th>
                                    <th scope="col" style="width: 10%;">Kapak Görseli</th>
                                    <th scope="col" style="width: 10%;">Durumu</th>
                                    <th scope="col" style="width: 10%;">İşlemler</th>
                                </tr>
                                </thead>
                                {{-- Tablo gövdesi. JavaScript ile dinamik olarak güncellenecek. --}}
                                <tbody id="galleryItemsTable" data-gallery-id="{{ $gallery->id }}">
                                @foreach ($gallery->items->sortBy('order') as $item)
                                    <tr data-id="{{ $item->id }}" class="cursor-grab">
                                        {{-- Sürükle-bırak için tutamaç hücresi --}}
                                        <td class="handle-cell text-center">
                                            <i class="ri-menu-2-line"></i>
                                        </td>
                                        <td class="fw-medium">{{ $item->id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="Gallery Image" class="img-fluid rounded" style="max-width: 80px; height: auto;">
                                        </td>
                                        <td>{{ basename($item->image) }}</td>
                                        {{-- Kapak Görseli Belirleme Anahtarı --}}
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch form-switch-lg">
                                                    <input type="checkbox" class="form-check-input cover-image-switch" data-id="{{ $item->id }}" {{ $gallery->cover_image == $item->image ? 'checked' : '' }} >
                                                </div>
                                            </div>
                                        </td>
                                        {{-- Görsel Durumunu (Aktif/Pasif) Belirleme Anahtarı --}}
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch form-switch-lg">
                                                    <input type="checkbox" class="form-check-input gallery-status-switch" data-id="{{ $item->id }}" {{ $item->status ? 'checked' : '' }} >
                                                </div>
                                            </div>
                                        </td>
                                        {{-- Görsel Silme Formu --}}
                                        <td>
                                            <form action="{{ route('admin.galleries.items.destroy', $item->id) }}" method="POST" class="d-inline deleteGalleryItemForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="link-danger">
                                                    <i class="ri-delete-bin-5-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>

    </div>
@endsection

{{-- Gerekli JavaScript kütüphanelerini ve özel scriptleri sayfanın sonuna ekliyoruz --}}
@push('scripts')
    {{-- CDN'den kütüphaneleri yüklüyoruz --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script>
        /**
         * Dropzone'dan veya başka bir yerden gelen yeni bir görsel verisini alıp
         * bunu tablonun sonuna yeni bir satır (<tr>) olarak ekler.
         * @param {object} item - Sunucudan dönen görsel nesnesi (id, image_url, status içerir).
         */
        function addImageToTable(item) {
            const tableBody = document.getElementById('galleryItemsTable');
            const row = document.createElement('tr');
            row.setAttribute('data-id', item.id);
            row.className = 'cursor-grab';
            const fileName = item.image_url.split('/').pop();

            // Yeni satırın HTML içeriğini oluşturuyoruz.
            row.innerHTML = `
            <td class="handle-cell text-center"><i class="ri-menu-2-line"></i></td>
            <td class="fw-medium">${item.id}</td>
            <td><img src="${item.image_url}" alt="Gallery Image" class="img-fluid rounded" style="max-width: 80px; height: auto;"></td>
            <td>${fileName}</td>
            <td>
                <div class="d-flex justify-content-center">
                    <div class="form-check form-switch form-switch-lg">
                        <input type="checkbox" class="form-check-input cover-image-switch" data-id="${item.id}">
                    </div>
                </div>
            </td>
            <td class="text-center">
                <div class="d-flex justify-content-center">
                    <div class="form-check form-switch form-switch-lg text-center">
                        <input type="checkbox" class="form-check-input gallery-status-switch" data-id="${item.id}" checked>
                    </div>
                </div>
            </td>
            <td>
                <form action="/admin/galleries/items/${item.id}" method="POST" class="d-inline deleteGalleryItemForm">
                    @csrf
            @method('DELETE')
            <button type="submit" class="link-danger"><i class="ri-delete-bin-5-line"></i></button>
        </form>
    </td>
`;
            tableBody.appendChild(row);
            // Yeni eklenen bu satırdaki butonlara ve anahtarlara da olay dinleyicilerini atıyoruz.
            attachEventListenersToRow(row);
        }

        /**
         * Belirli bir satır (<tr>) içindeki interaktif elementlere (silme formu, durum anahtarı vb.)
         * olay dinleyicilerini (event listeners) atar. Bu fonksiyon hem sayfa yüklendiğinde
         * hem de yeni bir görsel eklendiğinde kullanılır.
         * @param {HTMLElement} row - Olay dinleyicilerinin atanacağı <tr> elementi.
         */
        function attachEventListenersToRow(row) {
            // Silme formu gönderildiğinde handleDelete fonksiyonunu çağırır.
            row.querySelector('.deleteGalleryItemForm').addEventListener('submit', function(e) {
                e.preventDefault();
                handleDelete(e.currentTarget);
            });
            // Kapak görseli anahtarı değiştiğinde handleCoverImageSwitch fonksiyonunu çağırır.
            const coverImageSwitch = row.querySelector('.cover-image-switch');
            if (coverImageSwitch) {
                coverImageSwitch.addEventListener('change', function(e) {
                    handleCoverImageSwitch(e.currentTarget);
                });
            }
            // Durum anahtarı değiştiğinde handleStatusSwitch fonksiyonunu çağırır.
            row.querySelector('.gallery-status-switch').addEventListener('change', function(e) {
                handleStatusSwitch(e.currentTarget);
            });
        }

        /**
         * Silme formu gönderildiğinde SweetAlert ile onay ister. Onay verilirse
         * Fetch API kullanarak sunucuya DELETE isteği gönderir ve görseli siler.
         * @param {HTMLFormElement} form - Tetiklenen silme formu.
         */
        function handleDelete(form) {
            Swal.fire({
                title: 'Emin misin?',
                text: "Bu görsel kalıcı olarak silinecek!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = form.getAttribute('action');
                    fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                form.closest('tr').remove(); // Satırı tablodan kaldır.
                                iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                            } else {
                                iziToast.error({ title: 'Hata!', message: data.message || 'Görsel silinirken bir hata oluştu.', position: 'topRight' });
                            }
                        })
                        .catch(error => {
                            iziToast.error({ title: 'Hata!', message: 'İşlem sırasında bir ağ hatası oluştu.', position: 'topRight' });
                        });
                }
            });
        }

        /**
         * Kapak görseli anahtarı değiştirildiğinde çalışır. Diğer tüm anahtarları kapatır
         * ve Fetch API ile sunucuya PUT isteği göndererek galerinin kapak görselini günceller.
         * @param {HTMLInputElement} element - Değiştirilen checkbox elementi.
         */
        function handleCoverImageSwitch(element) {
            const coverImageId = element.checked ? element.getAttribute('data-id') : null;

            // Sadece bir kapak görseli seçilebilmesi için diğer tüm anahtarları kapatıyoruz.
            document.querySelectorAll('.cover-image-switch').forEach(otherEl => {
                if (otherEl !== element) {
                    otherEl.checked = false;
                }
            });

            const updateUrl = "{{ route('admin.galleries.updateCoverImage', $gallery->id) }}";
            fetch(updateUrl, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ cover_image_id: coverImageId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        iziToast.success({ title: 'Başarılı!', message: data.message, position: 'topRight' });
                    } else {
                        iziToast.error({ title: 'Hata!', message: data.message || 'Kapak görseli güncellenemedi.', position: 'topRight' });
                        element.checked = !element.checked; // Başarısız olursa anahtarı eski haline döndür.
                    }
                })
                .catch(error => {
                    iziToast.error({ title: 'Hata!', message: 'Bir sunucu hatası oluştu.', position: 'topRight' });
                    element.checked = !element.checked; // Hata olursa anahtarı eski haline döndür.
                });
        }

        /**
         * Bir görselin durum anahtarı (aktif/pasif) değiştirildiğinde çalışır.
         * Fetch API ile sunucuya PATCH isteği göndererek görselin durumunu günceller.
         * @param {HTMLInputElement} element - Değiştirilen checkbox elementi.
         */
        function handleStatusSwitch(element) {
            const itemId = element.getAttribute('data-id');
            const status = element.checked ? 1 : 0;
            const url = "{{ route('admin.common.updateStatus', ['model' => 'gallery-items', 'id' => ':id']) }}".replace(':id', itemId);

            fetch(url, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        iziToast.success({ title: 'Başarılı!', message: 'Durum başarıyla güncellendi.', position: 'topRight' });
                    } else {
                        iziToast.error({ title: 'Hata!', message: (data ? data.message : 'Durum güncellenemedi.'), position: 'topRight' });
                        element.checked = !status; // Başarısız olursa anahtarı eski haline döndür.
                    }
                })
                .catch(error => {
                    iziToast.error({ title: 'Hata!', message: 'Durum güncellenirken bir hata oluştu.', position: 'topRight' });
                    element.checked = !status; // Hata olursa anahtarı eski haline döndür.
                });
        }

        // Dropzone'un otomatik olarak bir elemente bağlanmasını engelliyoruz, çünkü onu manuel olarak yapılandıracağız.
        Dropzone.autoDiscover = false;

        // Sayfa tamamen yüklendiğinde ana scriptleri çalıştırıyoruz.
        document.addEventListener('DOMContentLoaded', function() {
            // Dropzone'u #dropzone-area ID'li elemente bağlıyoruz.
            new Dropzone("#dropzone-area", {
                url: "{{ route('admin.galleries.items.store', $gallery->id) }}",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                dictDefaultMessage: "Dosyaları buraya sürükleyip bırakın veya tıklayarak yükleyin.",
                paramName: "file",
                maxFilesize: 5, // MB
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                init: function() {
                    // Bir dosya başarıyla yüklendiğinde...
                    this.on("success", function(file, response) {
                        if (response.success) {
                            iziToast.success({ title: 'Başarılı!', message: response.message, position: 'topRight' });
                            // Yeni yüklenen görseli tabloya ekliyoruz.
                            addImageToTable(response.item);
                        } else {
                            iziToast.error({ title: 'Hata!', message: response.message, position: 'topRight' });
                        }
                    });
                    // Bir dosya yüklenirken hata oluşursa...
                    this.on("error", function(file, response) {
                        iziToast.error({ title: 'Hata!', message: response.message || 'Yükleme sırasında bir hata oluştu.', position: 'topRight' });
                    });
                }
            });

            // Sayfa yüklendiğinde mevcut olan tüm satırlar için olay dinleyicilerini atıyoruz.
            document.querySelectorAll('#galleryItemsTable tr').forEach(row => {
                attachEventListenersToRow(row);
            });

            // SortableJS'i tabloya uygulayarak sürükle-bırak ile sıralamayı aktif ediyoruz.
            const galleryTableBody = document.getElementById('galleryItemsTable');
            if (galleryTableBody) {
                Sortable.create(galleryTableBody, {
                    animation: 150,
                    handle: '.handle-cell', // Sadece bu class'a sahip hücreden sürüklemeye izin ver.
                    onEnd: function(evt) {
                        // Sürükleme bittiğinde yeni sıralamayı sunucuya gönderiyoruz.
                        let order = [];
                        galleryTableBody.querySelectorAll('tr').forEach((row, index) => {
                            order.push({
                                id: row.getAttribute('data-id'),
                                position: index + 1
                            });
                        });
                        fetch('{{ route('admin.common.updateOrder', ['model' => 'gallery-items']) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order: order })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    iziToast.success({ title: 'Başarılı', message: 'Görsel sıralaması güncellendi', position: 'topRight' });
                                } else {
                                    iziToast.error({ title: 'Hata', message: data.message || 'Sıralama güncellenemedi.', position: 'topRight' });
                                }
                            })
                            .catch(error => {
                                iziToast.error({ title: 'Hata', message: 'Sıralama güncellenirken bir ağ hatası oluştu.', position: 'topRight' });
                            });
                    }
                });
            }
        });
    </script>

@endpush
