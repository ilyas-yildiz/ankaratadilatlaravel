{{-- resources/views/layouts/app.blade.php --}}

@include('frontend.partials._head')

<body>

<div class="page-wraper">

    @include('frontend.partials._header')

    <main class="page-content">
        @yield('content')
    </main>
    @include('frontend.partials._footer')

</div>

@include('frontend.partials._include_script')

</body>

</html>
