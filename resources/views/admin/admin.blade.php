@extends('adminlte::page')

@section('title', 'Dashboard')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@section('css')

@endsection

@section('content_header')
    <h1>{{ __('Dashboard') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="loanApplicationsTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Amount</th>
                        <th>Broker</th>
                        <th>Status</th>
                        {{-- <th>Accion</th> --}}
                    </tr>
                </thead>
                {{--<tbody>
                    @foreach ($loanApplications as $loanApplication)
                        <tr>
                            <td>{{ $loanApplication->created_at->diffForHumans() }}</td>
                            <td>{{ $loanApplication->customer->details->first_name . ' ' . $loanApplication->customer->details->last_name }}
                            </td>
                            <td>{{ $loanApplication->customer->company->name }}</td>
                            <td>{{ $loanApplication->details->amount }}</td>
                            <td>{{ $loanApplication->customer->portfolio->broker_id }}</td>
                            <td>{{ $loanApplication->status }}</td>
                            {{-- <td>#</td> --}}
                        </tr>
                    @endforeach
                </tbody>--}}
            </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
    <script>
        $('#loanApplicationsTable').DataTable({
            "ajax": "../ajax/data/array.txt"
            responsive: true,
            autoWidth: false,
            language: {
                lengthMenu: "Mostrar " +
                            `<select class="form-select form-select-sm form-select-sm" aria-label=".form-select-sm example">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">Todos</option>
                              </select>`
                            + " registros por página",
                info: 'Mostrando la ´pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No hay registros disponibles',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                zeroRecords: 'No se ha encontrado nada - lo siento',
                search: 'Buscar:',
                paginate: {
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            }
        });
    </script>
@endsection
