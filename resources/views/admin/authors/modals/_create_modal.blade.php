<!-- Yeni Yazar Ekleme Modalı -->
<div class="modal fade" id="createAuthorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> {{-- Blog modalı gibi büyük (modal-xl) yerine normal (modal-lg) daha uygun olabilir --}}
        <form id="createAuthorForm" action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Yazar Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Yazar Adı Soyadı --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Adı Soyadı</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    {{-- Yazar Görseli --}}
                    <div class="mb-3">
                        <label for="img_url" class="form-label">Görsel</label>
                        <input type="file" class="form-control" id="img_url" name="img_url">
                    </div>

                    {{-- Yazar Hakkında Bilgi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Yazar Hakkında (Biyografi)</label>
                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
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
