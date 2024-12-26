@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')

@endsection

@section('content_header')
    <h1>{{ __('Dashboard') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
          <p>Contenido Espectacular</p>
        </div>
    </div>

@stop

@section('footer')
    <p>{{ __('Footer') }}</p>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>


@endsection
