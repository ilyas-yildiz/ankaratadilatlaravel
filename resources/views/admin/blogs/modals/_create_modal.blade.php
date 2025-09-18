<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form id="createForm" action="{{ route('admin.' . $routeName . '.store') }}" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Kayıt Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.' . $viewPath . '.partials._form', ['item' => null, 'editorId' => 'create_content'])

                    {{-- YENİ SEO ALANLARI --}}
                    <hr class="my-4">
                    <h6 class="mb-3">SEO Ayarları (AI Tarafından Oluşturuldu)</h6>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3" placeholder="Yapay zeka tarafından oluşturulan meta açıklaması buraya gelecek..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="2" placeholder="Yapay zeka tarafından oluşturulan anahtar kelimeler buraya gelecek..."></textarea>
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
