<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Bu satırı ekliyoruz

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * Bu satır hala bir "öneri" olarak durabilir, zararı yok.
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Kullanıcı başarıyla doğrulandıktan sonra çalışacak olan metot.
     *
     * Bu metot, AuthenticatesUsers trait'inin varsayılan yönlendirme mantığını
     * ezerek, yönlendirmenin nereye yapılacağı konusunda bize tam kontrol verir.
     * Bu, projedeki en güçlü ve en kesin yönlendirme yöntemidir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Ne olursa olsun, giriş yapan kullanıcıyı '/dashboard' adresine yönlendir.
        return redirect('/dashboard');
    }
}

