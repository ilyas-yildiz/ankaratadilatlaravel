@extends('admin.layouts.app')

@section('title', 'Kategori Düzenle')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Kategori Düzenle</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- PUT veya PATCH metodu için --}}
                    <div class="form-group">
                        <label for="name">Kategori Adı</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Güncelle</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Geri Dön</a>
                </form>
            </div>
        </div>
    </div>
@endsection

