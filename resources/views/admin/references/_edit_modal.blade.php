{{-- resources/views/admin/references/_edit_modal.blade.php --}}
{{-- Referans Düzenleme Modalı --}}
<div class="modal fade" id="editReferenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- Form action'ı JS ile güncellenecek --}}
        <form id="editReferenceForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Referans Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            
                            {{-- YENİ ALAN: name (JS'te edit_name olarak aktarılacak) --}}
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Referans Adı / Kurum Adı</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            
                            {{-- title (ID ve name doğru) --}}
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Başlık (Opsiyonel)</label>
                                <input type="text" name="title" id="edit_title" class="form-control">
                            </div>
                            
                            {{-- YENİ ALAN: description (JS'te edit_description olarak aktarılacak) --}}
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Açıklama (Opsiyonel)</label>
                                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                            </div>
                            
                            {{-- website_url (link yerine kullanıldı, name'i migration'a uygun) --}}
                            <div class="mb-3">
                                <label for="edit_website_url" class="form-label">Website Bağlantısı (URL)</label>
                                <input type="url" name="website_url" id="edit_website_url" class="form-control" placeholder="https://">
                            </div>

                            {{-- subtitle ve button_text ALANLARI KALDIRILDI --}}

                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                {{-- input name'i 'image' olarak doğru --}}
                                <label for="edit_image">Referans Görseli (Değiştirmek için yükleyin)</label>
                                <input type="file" name="image" id="edit_image" class="form-control-file">
                                <small class="form-text text-muted">Önerilen boyut: 600x400 piksel. Max 3MB.</small>
                                
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