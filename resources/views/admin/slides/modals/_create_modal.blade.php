<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> {{-- Boyut modal-xl'den lg'ye küçültülebilir --}}
        <form id="createForm" action="{{ route('admin.' . $routeName . '.store') }}" method="POST"
            enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Slide Ekle</h5> {{-- Başlık Güncellendi --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Form partial'ı çağrılıyor --}}
                    @include('admin.' . $viewPath . '.partials._form', ['item' => null, 'editorId' => 'create'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>