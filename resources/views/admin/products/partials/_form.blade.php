{{-- Bu dosya hem yeni kayıt hem de düzenleme formlarında kullanılacak. --}}
{{-- Değişken adını $blog yerine daha jenerik olan $item yapıyoruz. --}}

@csrf
<div class="mb-3">
    <label class="form-label" for="title">Ürün Adı</label>
    <input type="text" class="form-control" name="name" value="{{ old('title', $item->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="image" class="form-label">Görsel</label>
    <input type="file" class="form-control" name="image">

    {{-- JavaScript'in bu bölümü bulup görseli içine koyması için bir yer tutucu --}}
    <div id="image-preview-container" class="mt-2" style="display: none;">
        <label class="form-label">Mevcut Görsel</label>
        <div>
            <img id="image-preview" src="" alt="Mevcut Görsel" class="img-thumbnail" style="max-width: 150px;">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="category_id" class="form-label">Fiyat</label>
            <input type="text" class="form-control" name="price" value="{{ old('price', $item->price ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="author_id" class="form-label">Stok Kodu</label>
            <input type="text" class="form-control" name="sku" value="{{ old('sku', $item->sku ?? '') }}" required>
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
    <label for="content" class="form-label">İçerik</label>
    {{-- TinyMCE editörleri için ID'lerin benzersiz olması önemlidir. --}}
    <textarea id="{{ $editorId }}" class="tinymce-editor" name="content">{{ old('content', $item->content ?? '') }}</textarea></div>
