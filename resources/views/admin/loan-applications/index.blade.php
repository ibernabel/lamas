<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Loan Applications') }}
    </x-slot>

    <x-slot name="css>
        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
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
                $('#loanApplicationsTable').DataTable({
                    "ajax": {
                        "url": "{{ route('loan-applications.datatable') }}",
                        "error": function(xhr, error, thrown) {
                            console.error('Data Table error:', error);
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
                        [7, "asc"] // Default order by created_at in ascending order
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
            });
        </script>
    </x-slot>

</x-admin.app-layout>
