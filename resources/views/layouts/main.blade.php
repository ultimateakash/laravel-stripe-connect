<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials/head')
</head>

<body>
    <div class="container">
        @include('partials/header')
            @yield('content')
    </div>
</body>

</html>
