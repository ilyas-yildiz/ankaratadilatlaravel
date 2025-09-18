@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Merhaba, {{ Auth::user()->name }}!</h4>
                                <p class="text-muted mb-0">Websitenizdeki genel duruma bir göz atın.</p>
                            </div>
                            {{-- Not: Tarih filtreleme gibi özellikler daha sonra eklenebilir. --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <!-- Kart: Toplam Yazı Sayısı -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Toplam Yazı Sayısı</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $totalBlogs }}</h4>
                                        <a href="{{ route('admin.blogs.index') }}" class="text-decoration-underline">Tüm yazıları gör</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success rounded fs-3"><i class="ri-article-line"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <!-- Kart: Yazar Sayısı -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Toplam Yazar</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $totalAuthors }}</h4>
                                        <a href="{{ route('admin.authors.index') }}" class="text-decoration-underline">Tüm yazarları gör</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info rounded fs-3"><i class="ri-user-star-line"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <!-- Kart: Kategori Sayısı -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Toplam Kategori</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $totalCategories }}</h4>
                                        <a href="{{ route('admin.categories.index') }}" class="text-decoration-underline">Kategorileri yönet</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning rounded fs-3"><i class="ri-folders-line"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">En Son Eklenen Yazılar</h4>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-soft-primary btn-sm shadow-none">Tümünü Gör</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                        <tbody>
                                        @forelse($latestBlogs as $blog)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            @if($blog->image_url)
                                                                <img src="{{ asset('storage/blog-images/128x128/' . $blog->image_url) }}" alt="{{ $blog->title }}" class="img-fluid d-block">
                                                            @else
                                                                <img src="{{ asset('admin/images/products/img-1.png') }}" alt="varsayılan" class="img-fluid d-block">
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a href="#" class="text-reset text-truncate d-block" style="max-width: 250px;">{{ $blog->title }}</a></h5>
                                                            <span class="text-muted">{{ $blog->created_at->format('d M, Y') }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">{{ $blog->category->name ?? 'N/A' }}</h5>
                                                    <span class="text-muted">Kategori</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">{{ $blog->author->title ?? 'N/A' }}</h5>
                                                    <span class="text-muted">Yazar</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Henüz hiç blog yazısı eklenmemiş.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">En Aktif Yazarlar</h4>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('admin.authors.index') }}" class="btn btn-soft-primary btn-sm shadow-none">Tümünü Gör</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                        <tbody>
                                        @forelse($activeAuthors as $author)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            {{-- Not: Yazar için bir avatar alanı eklenebilir. --}}
                                                            <img src="{{ asset('admin/images/users/avatar-1.jpg') }}" alt="" class="avatar-sm p-2 rounded-circle shadow">
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1 fw-medium"><a href="#" class="text-reset">{{ $author->title }}</a></h5>
                                                            <span class="text-muted">{{-- $author->field ?? 'Alan belirtilmemiş' --}}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="mb-0 fw-medium">{{ $author->blogs_count }}</p>
                                                    <span class="text-muted">Yazı Sayısı</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">Henüz hiç yazar eklenmemiş.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-auto layout-rightside-col">
            <div class="overlay"></div>
            <div class="layout-rightside">
                <div class="card h-100 rounded-0">
                    <div class="card-body p-0">
                        <div class="p-3">
                            <h6 class="text-muted mb-3 text-uppercase fw-semibold">Kategoriye Göre Yazı Sayısı</h6>
                            <ol class="ps-3 text-muted">
                                @forelse($categoriesWithCount as $category)
                                    <li class="py-1">
                                        <a href="#" class="text-muted">{{ $category->name }} <span class="float-end">({{ $category->blogs_count }})</span></a>
                                    </li>
                                @empty
                                    <li class="py-1 text-muted">Henüz hiç kategori yok.</li>
                                @endforelse
                            </ol>
                            <div class="mt-3 text-center">
                                <a href="{{ route('admin.categories.index') }}" class="text-muted text-decoration-underline">Tüm Kategorileri Gör</a>
                            </div>
                        </div>
                        <div class="card sidebar-alert bg-light border-0 text-center mx-4 mb-0 mt-3">
                            <div class="card-body">
                                <img src="{{ asset('admin/images/giftbox.png') }}" alt="">
                                <div class="mt-4">
                                    <h5>Yeni Yazar Davet Edin</h5>
                                    <p class="text-muted lh-base">Bilgisini paylaşmak isteyen yeni bir köşe yazarını sitemize davet edin.</p>
                                    <button type="button" class="btn btn-primary btn-label rounded-pill"><i class="ri-mail-fill label-icon align-middle rounded-pill fs-16 me-2"></i> Şimdi Davet Et</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
