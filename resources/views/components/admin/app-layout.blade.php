@extends('adminlte::page')

{{-- Setup Custom Preloader Content --}}

@section('preloader')
    {{-- uiball.com preloaders --}}
    <l-tail-chase size="40" speed="1.75" color="black"></l-tail-chase>
    <h4 class="mt-4 text-dark">{{ __('Loading') }}</h4>
@stop

@section('title')
    {{ $title ?? '' }}
@stop
@section('css')
    @vite('resources/css/app.css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');
    </style>

    {{ $css ?? '' }}
@stop
@section('content_header')
    {{ $content_header ?? '' }}
@stop

@section('content')

    {{ $slot }}

@stop

@section('footer')
    {{ $footer ?? '' }}
@stop
@section('js')
    @vite('resources/js/app.js')
    {{-- uiball.com preloaders --}}
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/tailChase.js"></script>
    {{ $js ?? '' }}
@endsection
