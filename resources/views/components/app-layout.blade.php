<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lamas') . ' | '. $title ?? '' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')

</head>

<body>

    <header></header>

    {{ $slot }}

    <footer></footer>
</body>

</html>
