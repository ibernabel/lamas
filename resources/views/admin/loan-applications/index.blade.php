<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Loan Applications') }}
    </x-slot>

    <x-slot name="content_header">
        <h2 class="font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Loan Applications') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="loanApplicationsTable">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Broker') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-slot name="js">
        <!-- Cargar jQuery primero -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <!-- Cargar DataTables desde CDN como respaldo -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

        <script>
            // This function seems unused now as the render uses created_at_formated
            function timeAgo(date) {
                const seconds = Math.floor((new Date() - new Date(date)) / 1000);
                const intervals = {
                    year: 31536000,
                    month: 2592000,
                    week: 604800,
                    day: 86400,
                    hour: 3600,
                    minute: 60,
                    second: 1
                };

                const rtf = new Intl.RelativeTimeFormat('es', {
                    numeric: 'auto'
                });

                for (const [unit, secondsInUnit] of Object.entries(intervals)) {
                    const elapsed = Math.floor(seconds / secondsInUnit);
                    if (elapsed > 0) {
                        return rtf.format(-elapsed, unit);
                    }
                }
                return rtf.format(0, 'second');
            }
        </script>
        <script>
            // Asegurarse de que jQuery esté disponible globalmente
            if (typeof jQuery !== 'undefined') {
                window.$ = window.jQuery = jQuery;
            }

            // Verificar si jQuery y DataTables están disponibles
            console.log('jQuery disponible:', typeof jQuery !== 'undefined');
            console.log('$ disponible:', typeof $ !== 'undefined');
            console.log('$.fn.dataTable disponible:', typeof $.fn?.dataTable !== 'undefined');
            console.log('DataTable script loading...');
            $(document).ready(function() {
                console.log('Document ready, initializing DataTable...');
                try {
                    $('#loanApplicationsTable').DataTable({
                        "ajax": {
                            "url": "{{ url('/admin/loan-applications/datatable') }}",
                            "error": function(xhr, error, thrown) {
                                console.error('Data Table error:', error);
                                console.error('XHR Status:', xhr.status);
                                console.error('XHR Response Text:', xhr.responseText);
                                console.error('Thrown error:', thrown);
                                alert('Error loading loan applications. Please try again.');
                            }
                        },
                        "columns": [
                        {
                            data: "created_at_formated",
                            name: "created_at_formated", // Add name for server-side sorting/filtering if needed
                            render: function(data) {
                                return data.split(' ')[0] + "<br>" + data.split(' ')[1] + " " + data.split(' ')[2]; // Return the raw date for server-side processing
                                //return timeAgo(data); // Use the timeAgo function
                            }
                        },
                        {
                            data: "name", // Use the 'name' key returned by the server
                            name: "name",   // Name for server-side filtering
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "company", // Use the 'company' key
                            name: "customer.company.name", // Specify related column for potential server-side sorting/filtering
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "amount", // Use the 'amount' key
                            name: "details.amount", // Specify related column
                            searchable: true,
                            orderable: true,
                            render: function(data) {
                                // Client-side formatting for currency
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(data);
                            }
                        },
                        {
                            data: "broker", // Use the 'broker' key
                            name: "customer.portfolio.broker.user.name", // Specify related column
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "status",
                            name: "status",
                            searchable: true,
                            orderable: true,
                            render: function(data) {
                                if (!data) return data;
                                return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                            }
                        },
                        {
                            data: "actions", // Use the 'actions' key returned by the server
                            name: "actions",
                            orderable: false, // Actions column usually isn't orderable
                            searchable: false // Actions column usually isn't searchable
                        },
                        {
                            data: "created_at", // Use the raw created_at key for potential server-side filtering
                            name: "created_at", // Name for server-side filtering (use actual DB column)
                            visible: false // Hide the created_at_raw column if not needed in the UI

                        }
                    ],
                    "order": [
                        [7, "desc"] // Default order by created_at in descending order (newest first)
                    ],
                    "lengthMenu": [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Todos"]
                    ],
                    //"iDisplayLength": 10,
                    "pageLength": 10,
                    "responsive": true,
                    "processing": true,
                    "serverSide": true,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ registros por página",
                        "info": 'Mostrando la página _PAGE_ de _PAGES_',
                        "infoEmpty": 'No hay registros disponibles',
                        "infoFiltered": '(filtrado de _MAX_ registros totales)',
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "zeroRecords": 'No se han encontrado registros',
                        "search": 'Buscar:',
                        "paginate": {
                            "next": 'Siguiente',
                            "previous": 'Anterior'
                        }
                    }
                });
                } catch (error) {
                    console.error('Error initializing DataTable:', error);
                    alert('Error initializing DataTable: ' + error.message);

                    // Intentar inicializar DataTables de forma más simple como respaldo
                    try {
                        console.log('Intentando inicializar DataTable de forma simple...');
                        $('#loanApplicationsTable').DataTable({
                            "processing": true,
                            "language": {
                                "processing": "Procesando...",
                                "search": "Buscar:",
                                "lengthMenu": "Mostrar _MENU_ registros",
                                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                                "infoFiltered": "(filtrado de _MAX_ registros en total)",
                                "zeroRecords": "No se encontraron resultados",
                                "emptyTable": "No hay datos disponibles en la tabla",
                                "paginate": {
                                    "first": "Primero",
                                    "previous": "Anterior",
                                    "next": "Siguiente",
                                    "last": "Último"
                                }
                            }
                        });
                        console.log('DataTable inicializado de forma simple con éxito');
                    } catch (fallbackError) {
                        console.error('Error en inicialización simple de DataTable:', fallbackError);
                    }
                }
            });
        </script>
    </x-slot>
    <x-slot name="footer">
        <p>{{ config('app.name') }}</p>
    </x-slot>
</x-admin.app-layout>
