<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        {{-- Route adını dinamik olarak $routeName değişkeninden alıyoruz --}}
        <form id="createForm" action="{{ route('admin.' . $routeName . '.store') }}" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- Başlığı da dinamik yapabiliriz ama şimdilik böyle kalsın --}}
                    <h5 class="modal-title">Yeni Kayıt Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- 'editorId' parametresini ekliyoruz --}}
                    @include('admin.' . $viewPath . '.partials._form', ['item' => null, 'editorId' => 'create_content'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-light" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>
