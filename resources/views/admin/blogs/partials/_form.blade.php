@csrf
<div class="mb-3">
    <label class="form-label" for="{{ $editorId }}_title">Başlık</label>
    <input type="text" class="form-control" id="{{ $editorId }}_title" name="title" value="{{ old('title', $item->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="{{ $editorId }}_image" class="form-label">Görsel</label>
    <input type="file" class="form-control" name="image" id="{{ $editorId }}_image">
    
    {{-- GÜNCELLENDİ: JavaScript'in bulabilmesi için standart ID'ler eklendi --}}
    <div id="image-preview-container" class="mt-2" style="display: none;">
        <label class="form-label">Mevcut Görsel</label>
        <div>
            {{-- GÜNCELLENDİ: ID statik hale getirildi ve src temizlendi (JS dolduracak) --}}
            <img id="image-preview" src="" alt="Mevcut Görsel" class="img-thumbnail" style="max-width: 150px;">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select class="form-control" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(isset($item) && $item->category_id == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="author_id" class="form-label">Yazar</label>
            <select class="form-control" name="author_id">
                <option value="">Yazar Yok</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(isset($item) && $item->author_id == $author->id)>
                        {{ $author->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="gallery_id" class="form-label">Galeri</label>
            <select class="form-control" name="gallery_id">
                <option value="">Galeri Yok</option>
                @foreach($galleries as $gallery)
                    <option value="{{ $gallery->id }}" @selected(isset($item) && $item->gallery_id == $gallery->id)>
                        {{ $gallery->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="is_featured" class="form-label">Manşet Ayarı</label>
    <div class="form-check form-switch form-switch-lg">
        <input type="hidden" name="is_featured" value="0">
        <input class="form-check-input" type="checkbox" name="is_featured" value="1" @checked(isset($item) && $item->is_featured)>
        <label class="form-check-label" for="is_featured">Bu haberi manşete taşı</label>
    </div>
</div>

<div class="mb-3">
    <label for="content" class="form-label">İçerik</label>
    <textarea id="{{ $editorId }}" class="summernote-editor" name="content">{{ old('content', $item->content ?? '') }}</textarea>
</div>