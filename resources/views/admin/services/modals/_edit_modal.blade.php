<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hizmet Düzenle</h5> {{-- Başlık Güncellendi --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Doğru form partial'ı ve editorId çağrılıyor --}}
                    @include('admin.' . $viewPath . '.partials._form', ['item' => null, 'editorId' => 'edit_content']) 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-success">Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>