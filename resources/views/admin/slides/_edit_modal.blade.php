{{-- Slayt Düzenleme Modalı --}}
<div class="modal fade" id="editSlideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- Form action'ı JS ile güncellenecek --}}
        <form id="editSlideForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Slayt Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Form içeriği (item değişkeni JS ile aktarılmadığı için form alanlarına ID ekledik) --}}
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Başlık (Opsiyonel)</label>
                                <input type="text" name="title" id="edit_title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit_subtitle" class="form-label">Alt Başlık (Opsiyonel)</label>
                                <input type="text" name="subtitle" id="edit_subtitle" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit_link" class="form-label">Bağlantı Adresi (URL)</label>
                                <input type="url" name="link" id="edit_link" class="form-control" placeholder="https://">
                            </div>
                            <div class="mb-3">
                                <label for="edit_button_text" class="form-label">Buton Metni (Opsiyonel)</label>
                                <input type="text" name="button_text" id="edit_button_text" class="form-control" placeholder="Daha Fazla Gör">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image">Slayt Görseli (Değiştirmek için yükleyin)</label>
                                <input type="file" name="image" id="image" class="form-control-file">
                                <small class="form-text text-muted">Önerilen boyut: 1920x600 piksel. Max 3MB.</small>
                                
                                <div class="image-preview-container mt-2" style="display: none;">
                                    <img id="current_image_preview" src="" alt="Mevcut Görsel" class="img-thumbnail" style="max-width: 100%;">
                                    <small class="d-block text-muted text-center">Mevcut Görsel</small>
                                </div>
                            </div>
                        </div>
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