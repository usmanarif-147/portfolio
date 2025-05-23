<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">

    <title>{{ $title ?? 'ISG BACK OFFICE' }}</title>

    @include('layouts.admin.partials.css')
</head>

<body class="app">

    <header class="app-header fixed-top">
        @include('layouts.admin.partials.navbar')
        @include('layouts.admin.partials.sidepannel')
    </header>


    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            @yield('content')
        </div>
    </div>

    @include('layouts.admin.partials.js')
</body>

</html>
