<div class="table-responsive">
    <table class="table table-bordered align-middle table-nowrap mb-0">
        <thead>
        <tr>
            {{-- Toplu Seçim Kaldırıldı --}}
            {{-- <th style="width: 50px;"><div class="form-check"><input class="form-check-input" type="checkbox" id="selectAllCheckbox"></div></th> --}}
            {{-- Sıralama Kaldırıldı --}}
            {{-- <th style="width: 50px;">Sıra</th> --}}
            <th style="width: 50px;">ID</th>
            <th scope="col">Başlık</th>
            {{-- Kategori, Yazar vb. kaldırıldı --}}
            <th scope="col">Durumu</th>
            <th scope="col">İşlemler</th>
        </tr>
        </thead>
        {{-- Sıralama için data attribute'ları kaldırıldı --}}
        <tbody> 
        @forelse ($data as $item)
            {{-- data-id kaldı (silme ve düzenleme için gerekli) --}}
            <tr data-id="{{ $item->id }}"> 
                {{-- Toplu Seçim Kaldırıldı --}}
                {{-- <td><div class="form-check"><input class="form-check-input row-checkbox" type="checkbox" value="{{ $item->id }}"></div></td> --}}
                {{-- Sıralama Kaldırıldı --}}
                {{-- <td class="handle-cell text-center"><i class="ri-menu-2-line handle"></i></td> --}}
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                {{-- Kategori, Yazar vb. kaldırıldı --}}
                <td>
                    <div class="form-check form-switch form-switch-lg text-center">
                        <input type="checkbox" class="form-check-input status-switch"
                               data-id="{{ $item->id }}"
                               data-model="{{ $routeName }}" {{ $item->status ? 'checked' : '' }}>
                    </div>
                </td>
                <td>
                    <div class="hstack gap-3 fs-15 justify-content-center"> {{-- Ortala eklendi --}}
                        <a href="#" class="link-primary openEditModal"
                           data-bs-toggle="modal"
                           data-bs-target="#editModal"
                           data-id="{{ $item->id }}"
                           data-fetch-url="{{ route('admin.' . $routeName . '.edit', $item->id) }}"
                           data-update-url="{{ route('admin.' . $routeName . '.update', $item->id) }}">
                            <i class="ri-settings-4-line"></i>
                        </a>
                        {{-- Hakkımızda genellikle tek kayıt olduğu için silme butonu kaldırılabilir veya gizlenebilir --}}
                        <form action="{{ route('admin.' . $routeName . '.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="link-danger" style="background:none; border:none; padding:0;"><i class="ri-delete-bin-5-line"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">Henüz hiç içerik eklenmemiş.</td></tr> {{-- Colspan güncellendi --}}
        @endforelse
        </tbody>
    </table>
</div>