<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('layouts.header_script')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <style>
        input[type=text],
        input[type=search],
        input[type=number],
        select {
            box-shadow: inset -1px 4px 10px 0px rgb(212 212 212);
            height: 28px !important;
            font-size: 14px !important;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

    </style>
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
    @include('layouts.common')
    @yield('js')
</body>

</html>
