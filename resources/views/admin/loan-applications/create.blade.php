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
            @method('POST')
            
            <div class="row">
                <div class="col-md-12">
                    <h4>Datos Personales</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_first_name">Nombre</label>
                        <input type="text" class="form-control" id="customer_details_first_name" name="customer[details][first_name]" value="{{ old('customer.details.first_name') }}" required maxlength="100">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_last_name">Apellido</label>
                        <input type="text" class="form-control" id="customer_details_last_name" name="customer[details][last_name]" value="{{ old('customer.details.last_name') }}" required maxlength="100">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_nid">Cédula</label>
                        <input type="text" class="form-control" id="customer_nid" name="customer[NID]" value="{{ old('customer.NID') }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_birthday">Fecha Nacimiento</label>
                        <input type="date" class="form-control" id="customer_details_birthday" name="customer[details][birthday]" value="{{ old('customer.details.birthday') }}" required>
                    </div>
                </div>
                
                {{-- Phones --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_phones_0_number">Celular</label>
                        <input type="tel" class="form-control" id="customer_details_phones_0_number" name="customer[details][phones][0][number]" value="{{ old('customer.details.phones.0.number') }}" required>
                        <input type="hidden" name="customer[details][phones][0][type]" value="mobile">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_phones_1_number">Teléfono Casa</label>
                        <input type="tel" class="form-control" id="customer_details_phones_1_number" name="customer[details][phones][1][number]" value="{{ old('customer.details.phones.1.number') }}">
                        <input type="hidden" name="customer[details][phones][1][type]" value="home">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_email">Email</label>
                        <input type="email" class="form-control" id="customer_details_email" name="customer[details][email]" value="{{ old('customer.details.email') }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_marital_status">Estado Civil</label>
                        <select class="form-control" id="customer_details_marital_status" name="customer[details][marital_status]" required>
                            <option value="">Seleccione</option>
                            <option value="single" {{ old('customer.details.marital_status') == 'single' ? 'selected' : '' }}>Soltero/a</option>
                            <option value="married" {{ old('customer.details.marital_status') == 'married' ? 'selected' : '' }}>Casado/a</option>
                            <option value="divorced" {{ old('customer.details.marital_status') == 'divorced' ? 'selected' : '' }}>Divorciado/a</option>
                            <option value="widowed" {{ old('customer.details.marital_status') == 'widowed' ? 'selected' : '' }}>Viudo/a</option>
                            <option value="other" {{ old('customer.details.marital_status') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_nationality">Nacionalidad</label>
                        <input type="text" class="form-control" id="customer_details_nationality" name="customer[details][nationality]" value="{{ old('customer.details.nationality') }}" required>
                    </div>
                </div>
                
                {{-- Address --}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer_details_addresses_0_street">Dirección</label>
                        <textarea class="form-control" id="customer_details_addresses_0_street" name="customer[details][addresses][0][street]" required>{{ old('customer.details.addresses.0.street') }}</textarea>
                        {{-- Hidden fields for required address components not in the original form --}}
                        <input type="hidden" name="customer[details][addresses][0][type]" value="home"> {{-- Changed from primary to home --}}
                        <input type="hidden" name="customer[details][addresses][0][city]" value="DefaultCity"> {{-- Placeholder --}}
                        <input type="hidden" name="customer[details][addresses][0][state]" value="DefaultState"> {{-- Placeholder --}}
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_housing_type">Vivienda</label>
                        <select class="form-control" id="customer_details_housing_type" name="customer[details][housing_type]" required>
                            <option value="">Seleccione</option>
                            <option value="owned" {{ old('customer.details.housing_type') == 'owned' ? 'selected' : '' }}>Propia</option>
                            <option value="rented" {{ old('customer.details.housing_type') == 'rented' ? 'selected' : '' }}>Alquilada</option>
                            <option value="mortgaged" {{ old('customer.details.housing_type') == 'mortgaged' ? 'selected' : '' }}>Hipotecada</option>
                            <option value="other" {{ old('customer.details.housing_type') == 'other' ? 'selected' : '' }}>Otro</option> {{-- Added missing 'family' equivalent? Assuming 'other' or adjust validation --}}
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_details_move_in_date">Reside desde</label>
                        <input type="date" class="form-control" id="customer_details_move_in_date" name="customer[details][move_in_date]" value="{{ old('customer.details.move_in_date') }}" required>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Información de Vehículo</h4>
                </div>
                
                <div class="col-md-6">
                    {{-- These checkboxes might need JS to set the 'customer[vehicle][vehicle_type]' value based on selection --}}
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="own_vehicle_checkbox" value="1" {{ old('customer.vehicle.vehicle_type') == 'owned' ? 'checked' : '' }}> 
                            ¿Vehículo Propio? (Tipo: owned)
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="financed_vehicle_checkbox" value="1" {{ old('customer.vehicle.vehicle_type') == 'financed' ? 'checked' : '' }}> 
                            ¿Es Financiado? (Tipo: financed)
                        </label>
                        {{-- Hidden input for vehicle type, potentially set by JS --}}
                        <input type="hidden" name="customer[vehicle][vehicle_type]" id="customer_vehicle_vehicle_type" value="{{ old('customer.vehicle.vehicle_type', 'none') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_vehicle_vehicle_brand">Marca</label>
                        <input type="text" class="form-control" id="customer_vehicle_vehicle_brand" name="customer[vehicle][vehicle_brand]" value="{{ old('customer.vehicle.vehicle_brand') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_vehicle_vehicle_year">Año</label>
                        <input type="number" class="form-control" id="customer_vehicle_vehicle_year" name="customer[vehicle][vehicle_year]" value="{{ old('customer.vehicle.vehicle_year') }}" min="1900" max="{{ date('Y') + 1 }}">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Información de Cónyuge</h4>
                </div>
                
                <div class="col-md-6">
                    {{-- Spouse as Reference 0 --}}
                    <div class="form-group">
                        <label for="customer_references_0_name">Nombre Cónyuge</label>
                        <input type="text" class="form-control" id="customer_references_0_name" name="customer[references][0][name]" value="{{ old('customer.references.0.name') }}">
                        <input type="hidden" name="customer[references][0][relationship]" value="spouse">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_references_0_phone_number">Celular Cónyuge</label>
                        <input type="tel" class="form-control" id="customer_references_0_phone_number" name="customer[references][0][phone_number]" value="{{ old('customer.references.0.phone_number') }}">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Información Laboral</h4>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            {{-- Hidden input ensures 0 is sent if checkbox is unchecked --}}
                            <input type="hidden" name="customer[jobInfo][is_self_employed]" value="0">
                            <input type="checkbox" name="customer[jobInfo][is_self_employed]" id="customer_jobInfo_is_self_employed" value="1" {{ old('customer.jobInfo.is_self_employed', 0) == 1 ? 'checked' : '' }}> 
                            ¿Trabaja por Cuenta Propia?
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_company_name">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="customer_company_name" name="customer[company][name]" value="{{ old('customer.company.name') }}">
                    </div>
                </div>

                {{-- Company Email --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_company_email">Email Empresa</label>
                        <input type="email" class="form-control" id="customer_company_email" name="customer[company][email]" value="{{ old('customer.company.email') }}">
                    </div>
                </div>
                
                {{-- Company Phone --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_company_phones_0_number">Teléfono Trabajo</label>
                        <input type="tel" class="form-control" id="customer_company_phones_0_number" name="customer[company][phones][0][number]" value="{{ old('customer.company.phones.0.number') }}">
                        <input type="hidden" name="customer[company][phones][0][type]" value="work">
                    </div>
                </div>
                
                {{-- Company Address --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_company_addresses_0_street">Dirección Trabajo</label>
                        <input type="text" class="form-control" id="customer_company_addresses_0_street" name="customer[company][addresses][0][street]" value="{{ old('customer.company.addresses.0.street') }}">
                        {{-- Hidden fields for required address components --}}
                        <input type="hidden" name="customer[company][addresses][0][type]" value="work">
                        <input type="hidden" name="customer[company][addresses][0][city]" value="DefaultCity"> {{-- Placeholder --}}
                        <input type="hidden" name="customer[company][addresses][0][state]" value="DefaultState"> {{-- Placeholder --}}
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_jobInfo_role">Posición que Ocupa</label>
                        <input type="text" class="form-control" id="customer_jobInfo_role" name="customer[jobInfo][role]" value="{{ old('customer.jobInfo.role') }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_jobInfo_start_date">Laborando desde</label>
                        <input type="date" class="form-control" id="customer_jobInfo_start_date" name="customer[jobInfo][start_date]" value="{{ old('customer.jobInfo.start_date') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_jobInfo_salary">Sueldo/Ingreso Mensual</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">RD$</span>
                            </div>
                            <input type="number" class="form-control" id="customer_jobInfo_salary" name="customer[jobInfo][salary]" value="{{ old('customer.jobInfo.salary') }}" required min="0" step="0.01" required>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_jobInfo_other_incomes">Otros Ingresos</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">RD$</span>
                            </div>
                            <input type="number" class="form-control" id="customer_jobInfo_other_incomes" name="customer[jobInfo][other_incomes]" value="{{ old('customer.jobInfo.other_incomes') }}" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer_jobInfo_other_incomes_source">Especifique Otros Ingresos</label>
                        <input type="text" class="form-control" id="customer_jobInfo_other_incomes_source" name="customer[jobInfo][other_incomes_source]" value="{{ old('customer.jobInfo.other_incomes_source') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_jobInfo_supervisor_name">Nombre Supervisor Inmediato</label>
                        <input type="text" class="form-control" id="customer_jobInfo_supervisor_name" name="customer[jobInfo][supervisor_name]" value="{{ old('customer.jobInfo.supervisor_name') }}">
                    </div>
                </div>
                
                <div class="col-md-12">
                    <h4>Referencias</h4>
                </div>
                
                {{-- References start from index 1, as index 0 is spouse --}}
                @for ($i = 1; $i <= 2; $i++)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Referencia {{ $i }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_references_{{ $i }}_name">Nombre Completo</label>
                                        <input type="text" class="form-control" id="customer_references_{{ $i }}_name" name="customer[references][{{ $i }}][name]" value="{{ old('customer.references.'.$i.'.name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_references_{{ $i }}_occupation">Ocupación</label>
                                        <input type="text" class="form-control" id="customer_references_{{ $i }}_occupation" name="customer[references][{{ $i }}][occupation]" value="{{ old('customer.references.'.$i.'.occupation') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_references_{{ $i }}_relationship">Parentesco</label>
                                        <input type="text" class="form-control" id="customer_references_{{ $i }}_relationship" name="customer[references][{{ $i }}][relationship]" value="{{ old('customer.references.'.$i.'.relationship') }}" required>
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
