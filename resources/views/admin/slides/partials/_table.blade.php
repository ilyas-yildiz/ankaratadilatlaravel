<div class="table-responsive">
    <table class="table table-nowrap align-middle">
        <thead class="table-light">
        <tr>
            <th style="width: 40px;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                </div>
            </th>
            <th style="width: 50px;">Sırala</th>
            <th>Görsel</th>
            <th>Başlık</th>
            <th>Link</th>
            <th style="width: 120px;">Durum</th>
            <th style="width: 150px;">İşlemler</th>
        </tr>
        </thead>
        <tbody id="sortable-list" data-update-url="{{ route('admin.common.updateOrder', ['model' => 'slides']) }}">
        @forelse ($slides as $slide)
            <tr id="row-{{ $slide->id }}" data-id="{{ $slide->id }}">
                <td>
                    <div class="form-check">
                        <input class="form-check-input row-checkbox" type="checkbox"
                               data-id="{{ $slide->id }}" value="{{ $slide->id }}">
                    </div>
                </td>
                <td class="handle-cell" style="cursor: move;">
                    <i class="ri-drag-move-2-fill fs-16"></i>
                </td>
                <td>
                    {{-- Resim yükleme yapıldığında burası storage'dan gelen resim olacak --}}
                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" class="rounded" style="width: 150px; height: auto;">
                </td>
                <td>
                    <h5 class="fs-14 mb-1">{{ $slide->title }}</h5>
                    <p class="text-muted mb-0">{{ $slide->subtitle }}</p>
                </td>
                <td>
                    @if($slide->link)
                        <a href="{{ $slide->link }}" target="_blank">{{ Str::limit($slide->link, 30) }}</a>
                    @else
                        <span class="text-muted">Link Yok</span>
                    @endif
                </td>
                <td>
                    <div class="form-check form-switch form-switch-md mb-3">
                        <input class="form-check-input status-switch" type="checkbox" role="switch"
                               id="status-switch-{{ $slide->id }}"
                               data-id="{{ $slide->id }}"
                               data-model="slides"
                            {{ $slide->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status-switch-{{ $slide->id }}"></label>
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                data-fetch-url="{{ route('admin.slides.edit', $slide) }}"
                                data-update-url="{{ route('admin.slides.update', $slide) }}"
                                data-model="slides"> {{-- << EKLENMESİ GEREKEN SATIR --}}
                            Düzenle
                        </button>

                        {{-- Butonu, JS'in beklediği yapıya uygun bir forma dönüştürüyoruz --}}
                        <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="delete-form d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <p class="text-muted mb-0">Henüz hiç slayt eklenmemiş.</p>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
