<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{-- action kısmı JavaScript ile dinamik olarak doldurulacak --}}
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kaydı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Ana form alanları partial'dan geliyor --}}
                    @include('admin.' . $viewPath . '.partials._form', ['item' => null, 'editorId' => 'edit_content'])

                    {{-- YENİ SEO ALANLARI --}}
                    <hr class="my-4">
                    <h6 class="mb-3">SEO Ayarları</h6>
                    <div class="mb-3">
                        <label for="edit_meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="edit_meta_description" name="meta_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_meta_keywords" class="form-label">Meta Keywords</label>
                        <textarea class="form-control" id="edit_meta_keywords" name="meta_keywords" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-success">Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>
