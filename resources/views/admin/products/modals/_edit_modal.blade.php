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
                    {{-- 'editorId' parametresini ekliyoruz --}}
                    @include('admin.' . $viewPath . '.partials._form', ['item' => null, 'editorId' => 'edit_content'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>
