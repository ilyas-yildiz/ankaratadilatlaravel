@csrf
<div class="mb-3">
    <label class="form-label" for="{{ $editorId ?? 'create' }}_title">Başlık</label>
    <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_title" name="title" value="{{ old('title', $item->title ?? '') }}" required>
</div>

{{-- Kısa İçerik kaldırıldı --}}

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

{{-- Kategori, Yazar, Galeri, Manşet Ayarı alanları zaten yoktu/kaldırıldı --}}

<div class="mb-3">
    {{-- Standart 'content' adı kullanılıyor --}}
    <label for="{{ $editorId ?? 'create' }}_content" class="form-label">İçerik</label>
    <textarea id="{{ $editorId ?? 'create' }}_content" class="summernote-editor" name="content">{{ old('content', $item->content ?? '') }}</textarea>
</div>