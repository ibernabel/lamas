<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Admin') }}
    </x-slot>
    <x-slot name="content_header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Sección de Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Tarjeta de Usuarios -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 mr-4">
                        <svg class="h-6 w-6 text-indigo-500 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nuevos este mes</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Pendientes</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\User::where('is_approved', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Solicitudes de Préstamo -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                        <svg class="h-6 w-6 text-green-500 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Solicitudes</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\LoanApplication::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nuevas este mes</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\LoanApplication::where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Aprobadas</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\LoanApplication::where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Clientes -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                        <svg class="h-6 w-6 text-blue-500 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Clientes</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\Customer::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nuevos este mes</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\Customer::where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Activos</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\Customer::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Cartera -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 mr-4">
                        <svg class="h-6 w-6 text-yellow-500 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Carteras</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\Portfolio::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Préstamos activos</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ \App\Models\LoanApplication::where('is_active', true)->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Riesgo promedio</p>
                        <p class="text-md font-semibold text-gray-800 dark:text-gray-200">Medio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Gráfico de Solicitudes por Mes -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Solicitudes por Mes</h3>
            </div>
            <div class="p-4">
                <canvas id="loanApplicationsChart" height="200"></canvas>
            </div>
        </div>

        <!-- Gráfico de Estado de Solicitudes -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Estado de Solicitudes</h3>
            </div>
            <div class="p-4">
                <canvas id="loanStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Sección de Mapa y Distribución de Riesgo -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Mapa de Distribución Geográfica -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Distribución Geográfica</h3>
            </div>
            <div class="p-4">
                <div id="map" class="h-64 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <p class="text-gray-500 dark:text-gray-400">Mapa de distribución de clientes</p>
                </div>
            </div>
        </div>

        <!-- Gráfico de Distribución de Riesgo -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Distribución de Riesgo Crediticio</h3>
            </div>
            <div class="p-4">
                <canvas id="riskDistributionChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Sección de Tablas de Resumen -->
    <div class="grid grid-cols-1 gap-4 mb-6">
        <!-- Últimas Solicitudes de Préstamo -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Últimas Solicitudes</h3>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Monto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach(\App\Models\LoanApplication::with('customer')->latest()->take(5)->get() as $application)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $application->customer->details->first_name ?? 'N/A' . ' ' . $application->customer->details->last_name ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($application->details->amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($application->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @elseif($application->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $application->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('loan-applications.show', $application->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Widgets Adicionales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Widget de Notificaciones -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Notificaciones</h3>
            </div>
            <div class="p-4">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="flex h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-500 dark:text-blue-300 items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Nueva solicitud recibida</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hace 2 horas</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="flex h-8 w-8 rounded-full bg-green-100 dark:bg-green-900 text-green-500 dark:text-green-300 items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Solicitud aprobada</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ayer</p>
                            </div>
                        </div>
                    </li>
                    <li class="py-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="flex h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-500 dark:text-yellow-300 items-center justify-center">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Alerta de riesgo</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hace 3 días</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Widget de Tareas Pendientes -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Tareas Pendientes</h3>
            </div>
            <div class="p-4">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-3">
                        <div class="flex items-center">
                            <input id="task-1" name="task-1" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="task-1" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Revisar solicitudes pendientes
                            </label>
                        </div>
                    </li>
                    <li class="py-3">
                        <div class="flex items-center">
                            <input id="task-2" name="task-2" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="task-2" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Aprobar usuarios nuevos
                            </label>
                        </div>
                    </li>
                    <li class="py-3">
                        <div class="flex items-center">
                            <input id="task-3" name="task-3" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="task-3" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Actualizar tasas de interés
                            </label>
                        </div>
                    </li>
                    <li class="py-3">
                        <div class="flex items-center">
                            <input id="task-4" name="task-4" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="task-4" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Revisar informes mensuales
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Widget de Información del Usuario -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Información del Usuario</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <span class="inline-block h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <svg class="h-full w-full text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Role') . ': ' . Auth::user()->role }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Last Login') . ': ' . Auth::user()->last_login }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Account Status') . ': ' . Auth::user()->status }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para los gráficos -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Solicitudes por Mes
            const loanApplicationsCtx = document.getElementById('loanApplicationsChart').getContext('2d');
            const loanApplicationsChart = new Chart(loanApplicationsCtx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Solicitudes',
                        data: [12, 19, 3, 5, 2, 3, 8, 14, 10, 15, 9, 6],
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de Estado de Solicitudes
            const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
            const loanStatusChart = new Chart(loanStatusCtx, {
                type: 'pie',
                data: {
                    labels: ['Aprobadas', 'Pendientes', 'Rechazadas', 'En Revisión'],
                    datasets: [{
                        data: [35, 20, 15, 30],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.6)',
                            'rgba(234, 179, 8, 0.6)',
                            'rgba(239, 68, 68, 0.6)',
                            'rgba(59, 130, 246, 0.6)'
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(59, 130, 246, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico de Distribución de Riesgo
            const riskDistributionCtx = document.getElementById('riskDistributionChart').getContext('2d');
            const riskDistributionChart = new Chart(riskDistributionCtx, {
                type: 'bar',
                data: {
                    labels: ['Bajo', 'Medio-Bajo', 'Medio', 'Medio-Alto', 'Alto'],
                    datasets: [{
                        label: 'Clientes por Nivel de Riesgo',
                        data: [15, 25, 30, 20, 10],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.6)',
                            'rgba(134, 239, 172, 0.6)',
                            'rgba(234, 179, 8, 0.6)',
                            'rgba(249, 115, 22, 0.6)',
                            'rgba(239, 68, 68, 0.6)'
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(134, 239, 172, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(249, 115, 22, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Inicializar mapa
            const mapElement = document.getElementById('map');
            if (mapElement) {
                // Limpiar el contenido del placeholder
                mapElement.innerHTML = '';

                // Inicializar el mapa de Leaflet
                const map = L.map('map').setView([18.4861, -69.9312], 7); // Coordenadas de República Dominicana

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Ejemplo de marcadores (puedes reemplazar con datos reales)
                const markers = [
                    { lat: 18.4861, lng: -69.9312, name: 'Santo Domingo', count: 120 },
                    { lat: 19.4517, lng: -70.6970, name: 'Santiago', count: 85 },
                    { lat: 18.5601, lng: -68.3725, name: 'Punta Cana', count: 65 },
                    { lat: 18.7357, lng: -70.1627, name: 'San Cristóbal', count: 42 },
                    { lat: 19.7807, lng: -70.6851, name: 'Puerto Plata', count: 38 }
                ];

                // Añadir marcadores al mapa
                markers.forEach(marker => {
                    L.marker([marker.lat, marker.lng])
                        .addTo(map)
                        .bindPopup(`<b>${marker.name}</b><br>Clientes: ${marker.count}`);
                });
            }
        });
    </script>
    @endpush
    <x-slot name="footer">
        <p>{{ config('app.name') }}</p>
    </x-slot>
</x-admin.app-layout>
