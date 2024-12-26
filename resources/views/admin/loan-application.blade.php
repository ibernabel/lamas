@extends('adminlte::page')

@section('title', 'Loan Applications')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

@endsection

@section('content_header')
    <h1>{{ __('Loan Applications') }}</h1>
@stop

@section('content')
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

@stop

@section('footer')
    <p>{{ __('Footer') }}</p>
@stop

@section('js')

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

                "columns": [{
                        data: "created_at",
                        render: function(data) {
                            return timeAgo(data);
                        }
                    },
                    {
                        data: null,
                        "render": function(data) {
                            return `${data.customer.details.first_name} ${data.customer.details.last_name}`;
                        }
                    },
                    {
                        data: "customer.company.name"
                    },
                    {
                        data: "details.amount",
                        "render": function(data) {
                            return new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(data);
                        }
                    },

                    {
                        data: "customer.portfolio.broker.user.name"
                    },
                    {
                        data: "status",
                        "render": function(data) {
                            if (!data) return data; // Check for empty string  
                            return data.charAt(0).toUpperCase() + data.slice(1).toLowerCase();
                        }
                    },
                    {

                        data: "id",
                        render: function(data) {
                            const baseUrl = '{{ route('loan-applications.show', ':id') }}'.replace(
                                ':id', data);
                            return `<a class="btn btn-info" href="${baseUrl}">{{__('View')}}</a>`;
                        }



                    }
                ],
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Mostrar " +
                        `<select class="form-select form-select-sm" aria-label=".form-select-sm example">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="-1">Todos</option>
                </select>` + " registros por página",
                    "info": 'Mostrando la página _PAGE_ de _PAGES_',
                    "infoEmpty": 'No hay registros disponibles',
                    "infoFiltered": '(filtrado de _MAX_ registros totales)',
                    "zeroRecords": 'No se ha encontrado nada - lo siento',
                    "search": 'Buscar:',
                    "paginate": {
                        "next": 'Siguiente',
                        "previous": 'Anterior'
                    }
                }
            });
        });
    </script>
@endsection
