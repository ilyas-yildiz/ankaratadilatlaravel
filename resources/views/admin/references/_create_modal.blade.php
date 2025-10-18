{{-- Yeni Referans Ekleme Modalı --}}
<div class="modal fade" id="createReferenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- Görsel yüklediğimiz için enctype="multipart/form-data" eklenmeli --}}
        <form action="{{ route('admin.references.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Referans Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Form içeriği (BaseResourceController için uygun) --}}
                    @include('admin.references._form_fields', ['item' => new \App\Models\Reference()])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>