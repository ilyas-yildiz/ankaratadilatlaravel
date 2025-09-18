<!-- Yazar Düzenleme Modalı -->
<div class="modal fade" id="editAuthorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- Formun action'ı JavaScript ile dinamik olarak doldurulacak --}}
        <form id="editAuthorForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Güncelleme işlemleri için PUT veya PATCH metodu kullanılır --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yazar Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Yazar Adı Soyadı --}}
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Adı Soyadı</label>
                        {{-- ID'lerin çakışmaması için 'edit_' ön eki kullandık --}}
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>

                    {{-- Mevcut Yazar Görseli --}}
                    <div class="mb-3">
                        <label class="form-label">Mevcut Görsel</label>
                        <div>
                            <img id="current_image" src="" alt="Mevcut Görsel" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>

                    {{-- Yeni Yazar Görseli --}}
                    <div class="mb-3">
                        <label for="edit_img_url" class="form-label">Görseli Değiştir (İsteğe Bağlı)</label>
                        <input type="file" class="form-control" id="edit_img_url" name="img_url">
                        <small class="form-text text-muted">Yeni bir görsel seçerseniz mevcut görsel değiştirilecektir.</small>
                    </div>

                    {{-- Yazar Hakkında Bilgi --}}
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Yazar Hakkında (Biyografi)</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>
