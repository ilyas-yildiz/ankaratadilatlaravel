<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\RedirectIfLoggedIn;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SlideController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::name('frontend.')->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('home');

    // YENİ EKLENEN ROTA
    Route::get('/hakkimizda', [FrontendController::class, 'about'])->name('about');

    // Diğer frontend rotaları (header'da kullandıklarımız)
    Route::get('/hizmetlerimiz', [FrontendController::class, 'servicesIndex'])->name('services'); // Liste sayfası
    Route::get('/hizmetlerimiz/{slug}', [FrontendController::class, 'serviceDetail'])->name('services.detail'); // Detay sayfası
    Route::get('/projelerimiz', [FrontendController::class, 'projectsIndex'])->name('projects'); // Liste sayfası
    Route::get('/projelerimiz/{slug}', [FrontendController::class, 'projectDetail'])->name('projects.detail'); // Detay sayfası
    Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('blog.index'); // Liste
    Route::get('/blog/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.detail');
    Route::get('/iletisim', [FrontendController::class, 'contact'])->name('contact'); // Sayfayı göster
    Route::post('/iletisim', [FrontendController::class, 'handleContactForm'])->name('contact.submit'); // Formu işle
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::middleware(RedirectIfLoggedIn::class)->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {

        $resourceControllers = [
            'categories' => CategoryController::class,
            'galleries' => GalleryController::class,
            'blogs' => BlogController::class,
            'authors' => AuthorController::class,
            'products' => ProductController::class,
            'abouts' => AboutController::class,
            'services' => ServiceController::class,
            'projects' => ProjectController::class,
            'slides' => SlideController::class,
        ];

        foreach ($resourceControllers as $name => $controller) {
            Route::resource($name, $controller);
        }

        Route::patch('{model}/{id}/status', [CommonController::class, 'updateStatus'])->name('common.updateStatus');
        Route::post('{model}/update-order', [CommonController::class, 'updateOrder'])->name('common.updateOrder');
        Route::post('{model}/bulk-delete', [CommonController::class, 'bulkDestroy'])->name('common.bulkDestroy');
        Route::post('common/upload-image', [CommonController::class, 'uploadImage'])->name('common.uploadImage');

        Route::post('/galleries/{gallery}/items', [GalleryItemController::class, 'store'])->name('galleries.items.store');
        Route::delete('galleries/items/{galleryItem}', [GalleryItemController::class, 'destroy'])->name('galleries.items.destroy');
        Route::put('galleries/{gallery}/cover-image', [GalleryController::class, 'updateCoverImage'])->name('galleries.updateCoverImage');

        // Blog Modülü Özel Rotaları
        Route::get('blogs/ai/create', [BlogController::class, 'createWithAi'])->name('blogs.createWithAi');
        Route::get('blogs/ai/new-chat', [BlogController::class, 'startNewAiChat'])->name('blogs.startNewAiChat');
        Route::post('blogs/ai/generate', [BlogController::class, 'generateWithAi'])->name('blogs.generateWithAi');
        Route::post('blogs/ai/prepare', [BlogController::class, 'prepareFromAi'])->name('blogs.prepareFromAi');
        // AŞAĞIDAKİ SATIR KALDIRILDI
        // Route::get('blogs/{blog}/edit-content', [BlogController::class, 'editContent'])->name('blogs.editContent');
        Route::post('blogs/ai/save-chat', [BlogController::class, 'saveCurrentChat'])->name('blogs.saveAiChat');
        Route::get('blogs/ai/load-chat/{chat}', [BlogController::class, 'loadChat'])->name('blogs.loadAiChat');
        Route::delete('blogs/ai/delete-chat/{chat}', [BlogController::class, 'destroyAiChat'])->name('blogs.destroyAiChat');
        Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        Route::resource('slides', App\Http\Controllers\Admin\SlideController::class);
        Route::resource('references', App\Http\Controllers\Admin\ReferenceController::class);
    });
});

Route::get('/sistemi-temizle-12345', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return "Butun onbellekler temizlendi!";
    } catch (Exception $e) {
        return "Hata: " . $e->getMessage();
    }
});

Route::get('/run-user-seeder-a1b2c3d4e5f6', function () {
    try {
        Artisan::call('db:seed', ['--class' => 'UserSeeder', '--force' => true]);
        return 'UserSeeder başarıyla çalıştırıldı.';
    } catch (\Exception $e) {
        return 'Hata: ' . $e->getMessage();
    }
});

Route::get('/migrate-now', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Veritabanı başarıyla güncellendi!';
    } catch (Exception $e) {
        return 'Hata oluştu: ' . $e->getMessage();
    }
});