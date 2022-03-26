<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('layouts.header_script')

    <title>@yield('title')</title>
    @yield('css')
</head>
<body class="sb-nav-fixed">
    @include('layouts.top_nav')
    <div id="layoutSidenav">
        @include('layouts.sidebar')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    @yield('content')
                </div>
            </main>

            @include('layouts.footer')
        </div>
    </div>
    @include('layouts.footer_script')

    @yield('js')
</body>
</html>
