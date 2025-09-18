@extends('admin.layouts.app')

@section('title', 'Yeni Kategori Ekle')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Yeni Kategori Ekle</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Kategori Adı</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Geri Dön</a>
                </form>
            </div>
        </div>
    </div>
@endsection
