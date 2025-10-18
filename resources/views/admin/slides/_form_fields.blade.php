{{-- Bu dosya sadece _create_modal.blade.php içinde kullanılacaktır. --}}
<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="title" class="form-label">Başlık (Opsiyonel)</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $item->title ?? old('title') }}">
        </div>
        <div class="mb-3">
            <label for="subtitle" class="form-label">Alt Başlık (Opsiyonel)</label>
            <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ $item->subtitle ?? old('subtitle') }}">
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Bağlantı Adresi (URL)</label>
            <input type="url" name="link" id="link" class="form-control" value="{{ $item->link ?? old('link') }}" placeholder="https://">
        </div>
        <div class="mb-3">
            <label for="button_text" class="form-label">Buton Metni (Opsiyonel)</label>
            <input type="text" name="button_text" id="button_text" class="form-control" value="{{ $item->button_text ?? old('button_text') }}" placeholder="Daha Fazla Gör">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="image">Slayt Görseli</label>
            {{-- create formunda dosya yüklemek zorunludur --}}
            <input type="file" name="image" id="image" class="form-control-file" required> 
            <small class="form-text text-muted">Önerilen boyut: 1920x600 piksel. Max 3MB.</small>
        </div>
    </div>
</div>