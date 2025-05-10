<x-admin.app-layout>
    <x-slot name="title">
        {{ __(env('CUSTOMERS_TITLE', 'Customers')) }}
    </x-slot>

    {{-- CSS slot can be removed if no other custom CSS is needed here, or kept empty --}}
    {{-- <x-slot name="css">
    </x-slot> --}}


    <x-slot name="content_header">
        <h2 class="font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __(env('CUSTOMERS_TITLE', 'Customers')) }}
        </h2>
    </x-slot>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="customersTable">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Company') }}</th>
                        <th>{{ __('NID') }}</th>
                        <th>{{ __('Broker') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>



    <x-slot name="footer">
        <p>{{ __('Footer') }}</p>
    </x-slot>

    <x-slot name="js">
        <!-- Cargar jQuery primero -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <!-- Cargar DataTables desde CDN como respaldo -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <script>
            // Optional: Keep timeAgo function if needed elsewhere, otherwise remove
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
                $('#customersTable').DataTable({ // Changed table ID
                    "ajax": {
                        "url": "{{ url('/admin/customers/datatable') }}", // URL completa en lugar del nombre de la ruta
                        "error": function(xhr, error, thrown) {
                            console.error('Data Table error:', error);
                            alert('Error loading customers. Please try again.'); // Changed error message
                        }
                    },
                    "columns": [
                        {
                            data: "created_at_formated",
                            name: "created_at_formated",
                            render: function(data) {
                                return data.split(' ')[0] + "<br>" + data.split(' ')[1] + " " + data.split(' ')[2];
                            }
                        },
                        {
                            data: "name",
                            name: "name",
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "company",
                            name: "company", // Adjusted name for filtering
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "NID", // Added NID column
                            name: "NID",
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "broker",
                            name: "broker", // Adjusted name for filtering
                            searchable: true,
                            orderable: true,
                        },
                        {
                            data: "actions",
                            name: "actions",
                            orderable: false,
                            searchable: false
                        },
                         {
                            data: "created_at", // Raw created_at for sorting
                            name: "created_at",
                            visible: false // Hide this column
                        }
                    ],
                    "order": [
                        [6, "desc"] // Default order by raw created_at descending (index 6)
                    ],
                    "lengthMenu": [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Todos"]
                    ],
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
                        $('#customersTable').DataTable({
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
