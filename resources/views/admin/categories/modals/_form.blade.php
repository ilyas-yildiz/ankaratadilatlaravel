<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">Kategori Adı <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Renk</label>
            <input type="color" class="form-control form-control-color w-100" name="color" value="{{ $category->color }}" title="Renk Seç">
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Üst Kategori</label>
    <select class="form-control" name="parent_id">
        <option value="">Yok (Ana Kategori)</option>
        @foreach($parentCategories as $parent)
            <option value="{{ $parent->id }}" @selected($category->parent_id == $parent->id)>
                {{ $parent->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Mevcut Görsel</label>
    <div class="d-flex align-items-center gap-3">
        @if($category->image_url)
            <img src="{{ asset('storage/category-images/100x100/' . $category->image_url) }}" class="img-thumbnail" width="80">
        @else
            <span class="text-muted fst-italic">Görsel yok</span>
        @endif
        <input type="file" class="form-control" name="image">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Açıklama</label>
    <textarea class="form-control" name="description" rows="3">{{ $category->description }}</textarea>
</div>

<hr>
<h6 class="fw-bold text-muted">SEO Ayarları</h6>
<div class="mb-3">
    <label class="form-label">Meta Başlık</label>
    <input type="text" class="form-control" name="meta_title" maxlength="70" value="{{ $category->meta_title }}">
</div>
<div class="mb-3">
    <label class="form-label">Meta Açıklama</label>
    <textarea class="form-control" name="meta_description" maxlength="255" rows="2">{{ $category->meta_description }}</textarea>
</div>