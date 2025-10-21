@csrf
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_title">Proje Başlığı</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_title" name="title" value="{{ old('title', $item->title ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
         <div class="mb-3">
            <label for="{{ $editorId ?? 'create' }}_image" class="form-label">Ana Görsel</label>
            <input type="file" class="form-control" name="image" id="{{ $editorId ?? 'create' }}_image">
            <div id="image-preview-container" class="mt-2" style="display: none;">
                <label class="form-label">Mevcut Görsel</label>
                <div> <img id="image-preview" src="" alt="Mevcut Görsel" class="img-thumbnail" style="max-width: 150px;"> </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_client">Müşteri</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_client" name="client" value="{{ old('client', $item->client ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_project_manager">Proje Yöneticisi</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_project_manager" name="project_manager" value="{{ old('project_manager', $item->project_manager ?? '') }}" required>
        </div>
    </div>
</div>

 <div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_start_date">Başlangıç Tarihi</label>
            {{-- Carbon nesnesini YYYY-MM-DD formatına çeviriyoruz --}}
            <input type="date" class="form-control" id="{{ $editorId ?? 'create' }}_start_date" name="start_date" value="{{ old('start_date', isset($item) && $item->start_date ? $item->start_date->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_end_date">Bitiş Tarihi</label>
            <input type="date" class="form-control" id="{{ $editorId ?? 'create' }}_end_date" name="end_date" value="{{ old('end_date', isset($item) && $item->end_date ? $item->end_date->format('Y-m-d') : '') }}">
        </div>
    </div>
</div>

 <div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_location">Lokasyon</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_location" name="location" value="{{ old('location', $item->location ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label" for="{{ $editorId ?? 'create' }}_project_type">Proje Tipi</label>
            <input type="text" class="form-control" id="{{ $editorId ?? 'create' }}_project_type" name="project_type" value="{{ old('project_type', $item->project_type ?? '') }}">
        </div>
    </div>
     <div class="col-md-4">
        <div class="mb-3">
            <label for="gallery_id" class="form-label">İlişkili Galeri</label>
            <select class="form-control" name="gallery_id">
                <option value="">Galeri Yok</option>
                {{-- Controller'dan gelen $galleries değişkenini kullanıyoruz --}}
                @isset($galleries)
                    @foreach($galleries as $gallery)
                        <option value="{{ $gallery->id }}" @selected(isset($item) && $item->gallery_id == $gallery->id)>
                            {{ $gallery->name }}
                        </option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="{{ $editorId ?? 'create' }}_content" class="form-label">Proje Açıklaması</label>
    <textarea id="{{ $editorId ?? 'create' }}_content" class="summernote-editor" name="content">{{ old('content', $item->content ?? '') }}</textarea>
</div>