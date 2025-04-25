<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Customers') }}
    </x-slot>

    <x-slot name="css>
        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    </x-slot>


    <x-slot name="content_header">
        <h2 class="font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customers') }}
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
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
            $(document).ready(function() {
                $('#customersTable').DataTable({ // Changed table ID
                    "ajax": {
                        "url": "{{ route('customers.datatable') }}", // Changed route name
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
            });
        </script>
    </x-slot>

</x-admin.app-layout>
