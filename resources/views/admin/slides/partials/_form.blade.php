@csrf
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_title">Başlık</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_title" name="title"
                value="{{ old('title', $item->title ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_subtitle">Alt Başlık</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_subtitle" name="subtitle"
                value="{{ old('subtitle', $item->subtitle ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ $editorId ?? 'create' }}_image" class="form-label">Ana Görsel (Örn: 1920x1080)</label>
            <input type="file" class="form-control" name="image" id="{{ $editorId ?? 'create' }}_image" {{ isset($item) ? '' : 'required' }}>
            {{-- Ana Görsel önizleme --}}
            <div id="image-preview-container" class="mt-2" style="display: none;">
                <label class="form-label">Mevcut Ana Görsel</label>
                <div> <img id="image-preview" src="" alt="Mevcut Görsel" class="img-thumbnail"
                        style="max-width: 150px;"> </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            {{-- YENİ GÖRSEL ALANI --}}
            <label for="{{ $editorId ?? 'create' }}_image_sketch" class="form-label">Sketch Görsel (Aynı
                Boyutta)</label>
            <input type="file" class="form-control" name="image_sketch" id="{{ $editorId ?? 'create' }}_image_sketch">
            {{-- YENİ GÖRSEL ÖNİZLEME ALANI --}}
            {{-- JavaScript'in bulması için farklı ID'ler kullanalım --}}
            <div id="image_sketch-preview-container" class="mt-2" style="display: none;">
                <label class="form-label">Mevcut Sketch Görsel</label>
                <div> <img id="image_sketch-preview" src="" alt="Mevcut Sketch Görsel" class="img-thumbnail"
                        style="max-width: 150px;"> </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_link">Buton Linki (URL)</label>
            <input type="url" class="form-control" id="{{ $editorId ?? 'create' }}_link" name="link"
                placeholder="https://..." value="{{ old('link', $item->link ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_button_text">Buton Metni</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_button_text" name="button_text"
                value="{{ old('button_text', $item->button_text ?? '') }}">
        </div>
    </div>
</div>