@csrf
<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="{{ old('title', $slide->title ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="subtitle" class="form-label">Alt Başlık</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle"
                   value="{{ old('subtitle', $slide->subtitle ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Link</label>
            <input type="url" class="form-control" id="link" name="link" placeholder="https://..."
                   value="{{ old('link', $slide->link ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="image" class="form-label">Görsel</label>
            <input type="file" class="form-control" id="image" name="new_image" accept="image/*">
            <small class="form-text text-muted">Önerilen boyut: 1920x1080px</small>
        </div>
        <div id="image-preview-container" class="mt-2" style="display: none;">
            <p class="mb-1 fw-medium">Mevcut Görsel:</p>
            <img id="image-preview" src="" alt="Mevcut Görsel" class="rounded" style="max-height: 100px;">
        </div>
        <div class="mb-3">
            <label class="form-label">Durum</label>
            <div class="form-check form-switch form-switch-md mb-3">
                <input class="form-check-input status-switch" type="checkbox" role="switch" id="status-123" name="status"
                       @if(isset($slide) && !$slide->status) @else checked @endif>
                <label class="form-check-label" for="status">Aktif</label>
            </div>
        </div>
    </div>
</div>
