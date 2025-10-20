@csrf
<div class="mb-3">
    <label class="form-label" for="{{ $editorId ?? 'create' }}_title">Başlık</label> {{-- Dinamik ID --}}
    <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_title" name="title" value="{{ old('title', $item->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="{{ $editorId ?? 'create' }}_short_content" class="form-label">Kısa İçerik</label>
    <textarea class="form-control" id="{{ $editorId ?? 'create' }}_short_content" name="short_content" rows="3">{{ old('short_content', $item->short_content ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="{{ $editorId ?? 'create' }}_image" class="form-label">Görsel</label>
    <input type="file" class="form-control" name="image" id="{{ $editorId ?? 'create' }}_image">

    <div id="image-preview-container" class="mt-2" style="display: none;">
        <label class="form-label">Mevcut Görsel</label>
        <div>
            <img id="image-preview" src="" alt="Mevcut Görsel" class="img-thumbnail" style="max-width: 150px;">
        </div>
    </div>
</div>

{{-- Kategori, Yazar, Galeri kaldırıldı --}}
{{-- Manşet Ayarı kaldırıldı --}}

<div class="mb-3">
    <label for="{{ $editorId ?? 'create' }}_content" class="form-label">Uzun İçerik</label>
    {{-- Summernote için ID dinamik hale getirildi --}}
    <textarea id="{{ $editorId ?? 'create' }}_content" class="summernote-editor" name="content">{{ old('content', $item->content ?? '') }}</textarea>
</div>