<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Customer Details') }} {{-- Changed Title --}}
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="grid grid-cols-2 gap-x-2 grid-rows-1 mb-6 items-center"> {{-- Adjusted grid rows --}}
            <h3 class="text-lg font-semibold text-gray-400">
                {{ __('Customer') }} #{{ $customer->id }} {{-- Use $customer --}}
            </h3>
            {{-- Removed Loan Status --}}
            <small class="text-secondary text-right">{{-- Added text-right --}}
                {{ __('Date created') }}: {{ $customer->created_at->format('d/m/Y h:i:s a') }} {{-- Use $customer --}}
            </small>
        </div>

        <div class="space-y-6">
            {{-- Customer Information Section --}}
            <x-card.header>
                <x-card.title>{{ __('Customer Information') }}</x-card.title>
            </x-card.header>
            <x-card>
                <x-card.content>
                    <x-card.detail-item label="Nombre" :value="$customer?->details?->first_name . ' ' . $customer?->details?->last_name" />
                    <x-card.detail-item label="Cédula" :value="$customer?->NID" />
                    <x-card.detail-item label="Fecha de Nacimiento" :value="$customer?->details?->birthday" />
                    <x-card.detail-item label="Celular" :value="$customer?->details?->phones->where('type', 'mobile')->first()?->number ?? ''" /> {{-- Adjusted phone logic --}}
                    <x-card.detail-item label="Teléfono Casa:" :value="$customer?->details?->phones->where('type', 'home')->first()?->number ?? ''" /> {{-- Adjusted phone logic --}}
                    <x-card.detail-item label="Email" :value="$customer?->details?->email" />
                    <x-card.detail-item label="Estado Civil" :value="ucfirst(__($customer?->details?->marital_status))" />
                    <x-card.detail-item label="Nacionalidad" :value="$customer?->details?->nationality" />
                    <x-card.detail-item label="Dirección" :value="$customer?->details?->addresses?->first()
                        ? collect([
                            $customer?->details?->addresses[0]?->street,
                            $customer?->details?->addresses[0]?->street2,
                            $customer?->details?->addresses[0]?->city,
                            $customer?->details?->addresses[0]?->state,
                        ])
                            ->filter()
                            ->join(', ')
                        : __('No address available')" />
                    <x-card.detail-item label="Tipo de vivienda" :value="ucfirst(__($customer?->details?->housing_possession_type))" />
                    <x-card.detail-item label="Reside desde" :value="$customer?->details?->move_in_date" />
                    <x-card.detail-item label="Género" :value="ucfirst(__($customer?->details?->gender))" /> {{-- Updated gender value --}}
                    <x-card.detail-item label="Educación" :value="ucfirst(__($customer?->details?->education_level))" />
                    <x-card.detail-item label="Vehículo" :value="$customer?->vehicle?->vehicle_type ?? ''" />
                    <x-card.detail-item label="Marca" :value="$customer?->vehicle?->vehicle_brand ?? ''" />
                    <x-card.detail-item label="Modelo" :value="$customer?->vehicle?->vehicle_model ?? ''" />
                    <x-card.detail-item label="Año" :value="$customer?->vehicle?->vehicle_year ?? ''" />
                </x-card.content>
            </x-card>

            {{-- Removed Loan Details Section --}}

            {{-- Employment Information --}}
            <x-card.header>
                <x-card.title>Información Laboral</x-card.title>
            </x-card.header>
            <x-card>
                <x-card.content>
                    <x-card.detail-item label="Trabajador Independiente" :value="$customer?->jobInfo?->is_self_employed ? __('Yes') : __('No')" />
                    <x-card.detail-item label="Empresa" :value="$customer?->company?->name" />
                    <x-card.detail-item label="Dirección Empresa" :value="$customer?->company?->addresses?->first()
                        ? collect([
                            $customer?->company?->addresses[0]?->street,
                            $customer?->company?->addresses[0]?->street2,
                            $customer?->company?->addresses[0]?->city,
                            $customer?->company?->addresses[0]?->state,
                        ])
                            ->filter()
                            ->join(', ')
                        : __('No address available')" />
                    <x-card.detail-item label="Teléfono Empresa" :value="$customer?->company?->phones->where('type', 'work')->first()?->number ?? ''" /> {{-- Adjusted phone logic --}}
                    <x-card.detail-item label="Cargo" :value="$customer?->jobInfo?->role ?? 'No especificado'" />
                    <x-card.detail-item label="Fecha de Ingreso" :value="$customer?->jobInfo?->start_date ?? ''" />
                    <x-card.detail-item label="Salario" :value="number_format($customer?->jobInfo?->salary ?? 0, 2)" prefix="RD$" />
                    <x-card.detail-item label="Tipo de Pago" :value="ucfirst(__($customer?->jobInfo?->payment_type ?? ''))" />
                    <x-card.detail-item label="Frecuencia de Pago" :value="ucfirst(__($customer?->jobInfo?->payment_frequency ?? ''))" /> {{-- Updated payment frequency --}}
                    <x-card.detail-item label="Banco Nomina" :value="$customer?->jobInfo?->payment_bank ?? ''" />
                    <x-card.detail-item label="Otros Ingresos" :value="$customer?->jobInfo?->other_incomes ?? ''" />
                    <x-card.detail-item label="Fuente Otros Ingresos" :value="$customer?->jobInfo?->other_incomes_source ?? ''" />
                    <x-card.detail-item label="Horario" :value="$customer?->jobInfo?->schedule ?? ''" />
                    <x-card.detail-item label="Supervisor" :value="$customer?->jobInfo?->supervisor_name ?? ''" />
                </x-card.content>
            </x-card>

            {{-- References Section --}}
            <x-card.header>
                <x-card.title>Referencias</x-card.title>
            </x-card.header>
            <x-card>
                <x-card.content>
                    @forelse ($customer?->references ?? [] as $reference)
                        {{-- Use forelse for empty check --}}
                        @php
                        $is_household_member = $reference->relationship == 'household member';
                        $relationship = $is_household_member ? __('household member') : $reference->relationship;
                    @endphp
                        <div class="mb-4 last:mb-0">
                            <x-card.detail-item label="Referencia" :value="$loop->iteration" />
                            <hr class="my-2 w-10/12" />
                            <x-card.detail-item label="Nombre" :value="$reference->name" />
                            <x-card.detail-item label="Ocupación" :value="$reference->occupation" />
                            <x-card.detail-item label="Relación" :value="$relationship" />
                            <x-card.detail-item label="Teléfono" :value="$reference->phones[0]?->number ?? ''" />
                            <x-card.detail-item label="{{__('Type')}}" :value="__($reference->type) ?? ''" />

                        </div>
                    @empty
                        <p>{{ __('No references available.') }}</p>
                    @endforelse
                </x-card.content>
            </x-card>

            {{-- Removed Notes Section --}}
        </div>
    </div>
    <div class="sticky bottom-0 bg-white border-t border-gray-200 p-2 flex justify-end space-x-4 my-2">

        <x-button2 type="button" variant="light" onclick="window.print()">
            <i class="fas fa-print mr-2"></i>
            {{ __('Print') }}
        </x-button2>
        <x-button2 type="button" variant="warning"
            onclick="window.location.href='{{ route('customers.edit', $customer->id) }}'"> {{-- Changed Route --}}
            <i class="fas fa-pencil-alt mr-2"></i>
            {{ __('Edit') }}
        </x-button2>
        <x-button2 type="button" variant="info" onclick="window.location.href='{{ route('customers.index') }}'">
            {{-- Changed Route --}}
            <i class="fas fa-home mr-2"></i>
            {{ __('Back to') }} {{-- Changed Text --}}
        </x-button2>
    </div>
    <x-slot name="footer">
        <p>{{ config('app.name') }}</p>
    </x-slot>
</x-admin.app-layout>
