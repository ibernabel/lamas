@extends('adminlte::page')

@section('title', 'Nueva Solicitud de Préstamo')

@section('content_header')
    <h1>Nueva Solicitud de Préstamo</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('loan-applications.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-12">
                    <h4>Datos Personales</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">Nombre</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required maxlength="100">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Apellido</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="100">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="identification">Cédula</label>
                        <input type="text" class="form-control" id="identification" name="identification" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="birth_date">Fecha Nacimiento</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mobile_phone">Celular</label>
                        <input type="tel" class="form-control" id="mobile_phone" name="mobile_phone" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="home_phone">Teléfono Casa</label>
                        <input type="tel" class="form-control" id="home_phone" name="home_phone">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="marital_status">Estado Civil</label>
                        <select class="form-control" id="marital_status" name="marital_status" required>
                            <option value="">Seleccione</option>
                            <option value="single">Soltero/a</option>
                            <option value="married">Casado/a</option>
                            <option value="divorced">Divorciado/a</option>
                            <option value="widowed">Viudo/a</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nationality">Nacionalidad</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" required>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <textarea class="form-control" id="address" name="address" required></textarea>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="housing_type">Vivienda</label>
                        <select class="form-control" id="housing_type" name="housing_type" required>
                            <option value="">Seleccione</option>
                            <option value="owned">Propia</option>
                            <option value="rented">Alquilada</option>
                            <option value="family">Familiar</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="residence_start_date">Reside desde</label>
                        <input type="date" class="form-control" id="residence_start_date" name="residence_start_date" required>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Información de Vehículo</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="own_vehicle" id="own_vehicle" value="1"> 
                            ¿Vehículo Propio?
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="vehicle_financed" id="vehicle_financed" value="1"> 
                            ¿Es Financiado?
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vehicle_brand">Marca</label>
                        <input type="text" class="form-control" id="vehicle_brand" name="vehicle_brand">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vehicle_year">Año</label>
                        <input type="number" class="form-control" id="vehicle_year" name="vehicle_year" min="1900" max="{{ date('Y') }}">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Información de Cónyuge</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="spouse_name">Nombre Cónyuge</label>
                        <input type="text" class="form-control" id="spouse_name" name="spouse_name">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="spouse_phone">Celular Cónyuge</label>
                        <input type="tel" class="form-control" id="spouse_phone" name="spouse_phone">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Información Laboral</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="self_employed" id="self_employed" value="1"> 
                            ¿Trabaja por Cuenta Propia?
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_name">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="company_name" name="company_name">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="work_phone">Teléfono</label>
                        <input type="tel" class="form-control" id="work_phone" name="work_phone">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="work_address">Dirección Trabajo</label>
                        <input type="text" class="form-control" id="work_address" name="work_address">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="position">Posición que Ocupa</label>
                        <input type="text" class="form-control" id="position" name="position">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employment_start_date">Laborando desde</label>
                        <input type="date" class="form-control" id="employment_start_date" name="employment_start_date">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="monthly_salary">Sueldo/Ingreso Mensual</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">RD$</span>
                            </div>
                            <input type="number" class="form-control" id="monthly_salary" name="monthly_salary" required min="0" step="0.01">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="other_income">Otros Ingresos</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">RD$</span>
                            </div>
                            <input type="number" class="form-control" id="other_income" name="other_income" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="other_income_description">Especifique Otros Ingresos</label>
                        <input type="text" class="form-control" id="other_income_description" name="other_income_description">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supervisor_name">Nombre Supervisor Inmediato</label>
                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Referencias</h4>
                </div>
                
                @for ($i = 1; $i <= 2; $i++)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Referencia {{ $i }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reference_name_{{ $i }}">Nombre Completo</label>
                                        <input type="text" class="form-control" id="reference_name_{{ $i }}" name="references[{{ $i-1 }}][name]" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reference_occupation_{{ $i }}">Ocupación</label>
                                        <input type="text" class="form-control" id="reference_occupation_{{ $i }}" name="references[{{ $i-1 }}][occupation]" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reference_relationship_{{ $i }}">Parentesco</label>
                                        <input type="text" class="form-control" id="reference_relationship_{{ $i }}" name="references[{{ $i-1 }}][relationship]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="acceptance" required>
                            Acepto los términos y condiciones *
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    <style>
        .form-group { margin-bottom: 15px; }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Optional: Add dynamic form behaviors
            $('#own_vehicle').change(function() {
                if ($(this).is(':checked')) {
                    $('#vehicle_brand, #vehicle_year').prop('required', true);
                } else {
                    $('#vehicle_brand, #vehicle_year').prop('required', false);
                }
            });
        });
    </script>
@stop
