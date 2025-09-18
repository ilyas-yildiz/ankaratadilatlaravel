<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Kullanıcı Girişi | Enderun CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Enderun Digital CMS" name="description" />
    <meta content="Enderun Digital CMS" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('admin/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />


</head>

<body>

<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="index.html" class="d-inline-block auth-logo">
                                <img src="{{ asset('admin/images/enderunlogodisi.png') }}" alt="" height="120">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Enderun Digital Yönetim Paneli</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Hoş Geldiniz !</h5>
                                <p class="text-muted">Giriş yaparak Enderun CMS'e devam edebilirsiniz.</p>
                            </div>
                            <div class="p-2 mt-4">
                                {{-- Laravel'in form yapısı ve CSRF koruması --}}
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    {{-- E-posta alanı --}}
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-posta Adresi</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-posta">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                        @enderror
                                    </div>

                                    {{-- Şifre alanı --}}
                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="{{ route('password.request') }}" class="text-muted">Şifremi Unuttum</a>
                                        </div>
                                        <label class="form-label" for="password">Şifre</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input id="password" type="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Şifrenizi giriniz">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Beni Hatırla alanı --}}
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Beni Hatırla</label>
                                    </div>

                                    {{-- Giriş yap butonu --}}
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success w-100">Giriş Yap</button>
                                    </div>

                                    {{-- Kayıt olma linki --}}
                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Hesabınız yoksa yeni bir hesap oluşturabilirsiniz. <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline"> Kayıt Ol </a> </p>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    {{-- Kayıt olma linki --}}
                    <div class="mt-4 text-center">
                        <p class="mb-0">Hesabınız yoksa yeni bir hesap oluşturabilirsiniz. <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline"> Kayıt Ol </a> </p>
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy;
                            <script>document.write(new Date().getFullYear())</script> Enderun Digital</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

<!-- JAVASCRIPT -->
<script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('admin/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('admin/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('admin/js/plugins.js') }}"></script>

<!-- particles js -->
<script src="{{ asset('admin/libs/particles.js/particles.js') }}"></script>
<!-- particles app js -->
<script src="{{ asset('admin/js/pages/particles.app.js') }}"></script>
<!-- password-addon init -->
<script src="{{ asset('admin/js/pages/password-addon.init.js') }}"></script>
</body>

</html>
