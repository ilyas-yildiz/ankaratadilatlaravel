{{-- resources/views/admin/references/_form_fields.blade.php --}}
{{-- Bu dosya sadece _create_modal.blade.php içinde kullanılacaktır. --}}
<div class="row">
    <div class="col-md-8">
        {{-- YENİ ALAN: name (ZORUNLU) --}}
        <div class="mb-3">
            <label for="name" class="form-label">Referans Adı / Kurum Adı</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $item->name ?? old('name') }}" required>
        </div>
        
        {{-- title (VAR) --}}
        <div class="mb-3">
            <label for="title" class="form-label">Başlık (Opsiyonel)</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $item->title ?? old('title') }}">
        </div>

        {{-- YENİ ALAN: description (Opsiyonel) --}}
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama (Opsiyonel)</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $item->description ?? old('description') }}</textarea>
        </div>

        {{-- website_url (link yerine kullanıldı) --}}
        <div class="mb-3">
            <label for="website_url" class="form-label">Website Bağlantısı (URL)</label>
            {{-- name="link" yerine name="website_url" kullandık --}}
            <input type="url" name="website_url" id="website_url" class="form-control" value="{{ $item->website_url ?? old('website_url') }}" placeholder="https://">
        </div>
        
        {{-- subtitle ve button_text ALANLARI KALDIRILDI --}}
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="image">Referans Görseli (Logo)</label>
            {{-- create formunda dosya yüklemek zorunludur --}}
            <input type="file" name="image" id="image" class="form-control-file" required> 
            {{-- Önerilen boyutlar referans logosuna göre güncellendi (Slayt boyutları mantıksız olur) --}}
            <small class="form-text text-muted">Önerilen boyut: 600x400 piksel. Max 3MB.</small> 
        </div>
    </div>
</div>