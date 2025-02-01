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
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&family=Staatliches&display=swap');
    </style>
@stop

@section('content_header')
    {{ $content_header ?? '' }}
@stop

@section('content')

    {{ $slot }}

@stop

@section('footer')
    {{ $footer ?? 'Footer' }}
@stop
@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>

    {{-- uiball.com preloaders --}}
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/tailChase.js"></script>

@endsection
