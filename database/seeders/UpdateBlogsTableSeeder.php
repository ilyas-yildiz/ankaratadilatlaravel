<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class UpdateBlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // En az bir kullanıcı olduğundan emin olmak için ilk kullanıcıyı bul.
        $user = User::first();

        // Eğer kullanıcı bulunamazsa, bir hata mesajı verip işlemi durdur.
        if (!$user) {
            echo "Kullanıcı bulunamadı. Lütfen önce kullanıcı oluşturun.\n";
            return;
        }

        // user_id değeri null olan tüm blogları güncelle.
        Blog::whereNull('user_id')->update(['user_id' => $user->id]);

        echo "Blogs tablosundaki user_id değeri null olan " . Blog::where('user_id', $user->id)->count() . " adet satır güncellendi.\n";
    }
}
