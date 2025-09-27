{{-- En dıştaki div'in "id='editModal'" attribute'una sahip olduğundan emin oluyoruz --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {{-- action kısmı JavaScript ile dinamik olarak doldurulacak --}}
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Slaytı Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Ortak form alanlarını buraya dahil ediyoruz --}}
                    @include('admin.slides.partials._form', ['slide' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-success">Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>
