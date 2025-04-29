<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!--Favicon -->
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" type="image/x-icon">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">

                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        {{ __('Dashboard') }}
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        {{  __('Log in') }}
                                    </a>

                                    {{--@if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            {{ __('Register') }}
                                        </a>
                                    @endif--}}
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        <div class="bg-white dark:bg-gray-900/50 rounded-lg shadow-lg overflow-hidden">
                            <div class="px-6 py-12 sm:px-12 sm:py-16">
                                <div class="text-center">
                                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                                        Gestiona tus Solicitudes de Préstamo <span class="text-[#FF2D20]">Eficientemente</span>
                                    </h1>
                                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                                        LAMAS es la plataforma SaaS definitiva para simplificar y optimizar todo el ciclo de vida de las solicitudes de préstamo. Centraliza la información, agiliza los procesos y toma decisiones informadas.
                                    </p>
                                    <div class="mt-10 flex items-center justify-center gap-x-6">
                                        <a href="{{ route('login') }}" class="rounded-md bg-[#FF2D20] px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#E01D10] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#FF2D20]">Empezar Ahora</a>
                                        <a href="https://github.com/ibernabel/lamas" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:text-gray-700 dark:hover:text-gray-300">Ver en GitHub <span aria-hidden="true">→</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Beneficios -->
                        <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6 transition hover:shadow-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Centralización Total</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Toda la información de clientes, solicitudes, garantes y vehículos en un solo lugar accesible.</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6 transition hover:shadow-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Procesos Ágiles</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Reduce el tiempo de gestión de cada solicitud con flujos de trabajo optimizados y automatización.</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6 transition hover:shadow-lg">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Decisiones Informadas</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Analiza el riesgo crediticio y accede a reportes detallados para tomar mejores decisiones financieras.</p>
                            </div>
                        </div>

                        <!-- Sección de Funcionalidades -->
                        <div class="mt-16">
                            <h2 class="text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Funcionalidades Clave</h2>
                            <p class="mt-4 text-center text-lg leading-8 text-gray-600 dark:text-gray-400">Descubre cómo LAMAS transforma la gestión de préstamos.</p>
                            <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-2">
                                <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Gestión de Clientes y Solicitudes</h4>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Administra perfiles completos de clientes, incluyendo detalles personales, financieros, laborales y referencias.</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Análisis de Riesgo Crediticio</h4>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Evalúa la solvencia de los solicitantes con herramientas integradas de análisis de riesgo.</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Administración de Portafolios</h4>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Organiza y supervisa diferentes carteras de préstamos asignadas a brokers o promotores.</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md p-6">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Seguimiento y Notas</h4>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Mantén un historial detallado de cada solicitud con notas y actualizaciones de estado.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Call to Action -->
                        <div class="mt-16 bg-gray-100 dark:bg-gray-800/50 rounded-lg shadow-inner p-12 text-center">
                            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">¿Listo para Revolucionar tu Gestión de Préstamos?</h2>
                            <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-400">
                                Únete a LAMAS hoy mismo y experimenta la eficiencia y el control que tu negocio necesita.
                            </p>
                            <div class="mt-10">
                                <a href="{{ route('login') }}" class="rounded-md bg-[#FF2D20] px-5 py-3 text-base font-semibold text-white shadow-sm hover:bg-[#E01D10] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#FF2D20]">Comienza Gratis</a>
                            </div>
                        </div>
                    </main>

                    <footer class="py-16 text-center text-sm text-black dark:text-white/70">

                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
