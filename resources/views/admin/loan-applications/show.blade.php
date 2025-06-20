<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Loan Application') }}
    </x-slot>
    {{-- <x-slot name="content_header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Loan Application') }}
        </h2>
    </x-slot> --}}
    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="grid grid-cols-2 gap-x-2 grid-rows-2 mb-6 items-center">
            <h3 class="text-lg font-semibold text-gray-400">
                {{ __('Loan Application') }} #{{ $loanApplication->id }}
            </h3>
            <x-loan-status :status="$loanApplication->status" />
            <small class="text-secondary">{{ __('Date created') }}:
                {{ $loanApplication->created_at->format('d/m/Y h:i:s a') }}
            </small>
            <small>
                {{ __('Lead channel') }}: {{ $loanApplication->customer->lead_channel ?? __('No channel') }}
            </small>
        </div>

        <div class="space-y-6">
            {{-- Customer Information Section --}}
            <x-card.header>
                <x-card.title>{{ __(env('CUSTOMER_TITLE') . ' ' . 'Information') }}</x-card.title>
            </x-card.header>
            <x-card>

                <x-card.content>
                    <x-card.detail-item label="Nombre" :value="$loanApplication?->customer?->details?->first_name .
                        ' ' .
                        $loanApplication?->customer?->details?->last_name" />
                    <x-card.detail-item label="Cédula" :value="$loanApplication?->customer?->NID" />
                    <x-card.detail-item label="Fecha de Nacimiento" :value="$loanApplication?->customer?->details?->birthday" />
                    <x-card.detail-item label="Celular" :value="$loanApplication?->customer?->details?->phones[0]?->number" />
                    <x-card.detail-item label="Teléfono Casa:" :value="$loanApplication?->customer?->details?->phones[1]?->number ?? ''" />
                    <x-card.detail-item label="Email" :value="$loanApplication?->customer?->details?->email" />
                    <x-card.detail-item label="Estado Civil" :value="__($loanApplication?->customer?->details?->marital_status)" />
                    <x-card.detail-item label="Nacionalidad" :value="$loanApplication?->customer?->details?->nationality" />
                    <x-card.detail-item label="Dirección" :value="$loanApplication?->customer?->details?->addresses?->first()
                        ? collect([
                            $loanApplication?->customer?->details?->addresses[0]?->street,
                            $loanApplication?->customer?->details?->addresses[0]?->street2,
                            $loanApplication?->customer?->details?->addresses[0]?->city,
                            $loanApplication?->customer?->details?->addresses[0]?->state,
                        ])
                            ->filter()
                            ->join(', ')
                        : __('No address available')" />

                    <x-card.detail-item label="Tipo de vivienda" :value="ucfirst(__($loanApplication?->customer?->details?->housing_possession_type))" />
                    <x-card.detail-item label="Reside desde" :value="$loanApplication?->customer?->details?->move_in_date" />
                    <x-card.detail-item label="Medio de transporte" :value="__($loanApplication?->customer?->details?->mode_of_transport)" />
                    <x-card.detail-item label="Género" :value="__($loanApplication?->customer?->details?->gender)" />
                    <x-card.detail-item label="Educación" :value="ucfirst(__($loanApplication?->customer?->details?->education_level))" />
                    @if (config('features.show_vehicle_section'))

                    <x-card.detail-item label="Vehículo" :value="$loanApplication?->customer?->vehicle?->vehicle_type ?? ''" />
                    <x-card.detail-item label="Marca" :value="$loanApplication?->customer?->vehicle?->vehicle_brand ?? ''" />
                    <x-card.detail-item label="Modelo" :value="$loanApplication?->customer?->vehicle?->vehicle_model ?? ''" />
                    <x-card.detail-item label="Año" :value="$loanApplication?->customer?->vehicle?->vehicle_year ?? ''" />
                    @endif
                </x-card.content>
            </x-card>

            {{-- Loan Details Section --}}
            <x-card.header>
                <x-card.title>Detalles del Préstamo</x-card.title>
            </x-card.header>
            <x-card>

                <x-card.content>
                    <x-card.detail-item label="Monto" :value="number_format(optional($loanApplication->details)->amount, 2) ?? ''" prefix="RD$" />
                    <x-card.detail-item label="Plazo" :value="optional($loanApplication->details)->term ?? ''" suffix="meses" />
                    <x-card.detail-item label="Tasa" :value="optional($loanApplication->details)->rate ?? ''" suffix="%" />
                    <x-card.detail-item label="Cuota" :value="number_format(optional($loanApplication->details)->quota, 2) ?? ''" prefix="RD$" />
                    <x-card.detail-item label="Frecuencia" :value="__(optional($loanApplication->details)->frequency) ?? ''" />
                    <x-card.detail-item label="Propósito" :value="optional($loanApplication->details)->purpose ?? ''" />
                    <x-card.detail-item label="Comentario del Cliente" :value="optional($loanApplication->details)->customer_comment ?? ''" />
                </x-card.content>
            </x-card>

            {{-- Employment Information --}}
            <x-card.header>
                <x-card.title>Información Laboral</x-card.title>
            </x-card.header>
            <x-card>
                {{-- is_self_employed --}}
                <x-card.content>
                    <x-card.detail-item label="Trabajador Independiente" :value="$loanApplication?->customer?->jobInfo?->is_self_employed ? __('Yes') : __('No')" />
                    <x-card.detail-item label="Empresa" :value="$loanApplication?->customer?->company?->name" />
                    <x-card.detail-item label="Teléfono" :value="optional($loanApplication?->customer?->company?->phones)[0]?->number" />
                    <x-card.detail-item label="Dirección" :value="$loanApplication?->customer?->company?->addresses?->first()
                        ? collect([
                            $loanApplication?->customer?->company?->addresses[0]?->street,
                            $loanApplication?->customer?->company?->addresses[0]?->street2,
                            $loanApplication?->customer?->company?->addresses[0]?->city,
                            $loanApplication?->customer?->company?->addresses[0]?->state,
                        ])
                            ->filter()
                            ->join(', ')
                        : __('No address available')" />
                    <x-card.detail-item label="Cargo" :value="$loanApplication?->customer?->jobInfo?->role ?? 'No especificado'" />
                    <x-card.detail-item label="Fecha de Ingreso" :value="$loanApplication?->customer?->jobInfo?->start_date ?? ''" />
                    <x-card.detail-item label="Salario" :value="number_format($loanApplication?->customer?->jobInfo?->salary ?? 0, 2)" prefix="RD$" />
                    <x-card.detail-item label="Tipo de Pago" :value="ucfirst(__($loanApplication?->customer?->jobInfo?->payment_type ?? ''))" /> {{-- Fixed missing parenthesis --}}
                    <x-card.detail-item label="Frecuencia de Pago" :value="ucfirst(__($loanApplication?->customer?->jobInfo?->payment_frequency ?? ''))" /> {{-- Fixed missing parenthesis --}}
                    <x-card.detail-item label="Banco Nomina" :value="$loanApplication?->customer?->jobInfo?->payment_bank ?? ''" />
                    <x-card.detail-item label="Otros Ingresos" :value="$loanApplication?->customer?->jobInfo?->other_incomes ?? ''" />
                    <x-card.detail-item label="Fuente Otros Ingresos" :value="$loanApplication?->customer?->jobInfo?->other_incomes_source ?? ''" />
                    <x-card.detail-item label="Horario" :value="$loanApplication?->customer?->jobInfo?->schedule ?? ''" />
                    <x-card.detail-item label="Supervisor" :value="$loanApplication?->customer?->jobInfo?->supervisor_name ?? ''" />
                </x-card.content>
            </x-card>

            {{-- References Section --}}
            <x-card.header>
                <x-card.title>Referencias</x-card.title>
            </x-card.header>
            <x-card>

                <x-card.content>
                    @foreach ($loanApplication?->customer?->references ?? [] as $reference)
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
                            <x-card.detail-item label="{{ __('Type') }}" :value="$reference->type ?? ''" /> {{-- Added Type --}}
                        </div>
                    @endforeach
                </x-card.content>
            </x-card>

            {{-- Notes Section --}}
            @if ($loanApplication?->notes?->isNotEmpty())
                <x-card.header>
                    <x-card.title>Notas</x-card.title>
                </x-card.header>
                <x-card>

                    <x-card.content>
                        <div class="space-y-4">
                            @foreach ($loanApplication?->notes ?? [] as $note)
                                <div class="grid gap-x-4 items-center grid-cols-4 ">
                                    <div class="flex justify-end col-span-1 flex-shrink-0">
                                        <x-card.avatar :user="$note?->user" class="h-10 w-10" />
                                    </div>
                                    <div class="col-span-3">
                                        <x-card.detail-item2 label="{{ $note?->user?->name }}: " :value="$note?->note" />
                                        <small class="text-gray-500">
                                            {{ $note?->created_at?->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card.content>
                </x-card>
            @endif
        </div>
    </div>
    <div class="sticky bottom-0 bg-white border-t border-gray-200 p-2 flex justify-end space-x-4 my-2">

        <x-button2 type="button" variant="light" onclick="window.print()">
            <i class="fas fa-print mr-2"></i>
            {{ __('Print') }}
        </x-button2>
        <x-button2 type="button" variant="warning"
            onclick="window.location.href='{{ route('loan-applications.edit', $loanApplication) }}'">
            <i class="fas fa-pencil-alt mr-2"></i>
            {{ __('Edit') }}
        </x-button2>
        <x-button2 type="button" variant="info"
            onclick="window.location.href='{{ route('loan-applications.index') }}'">
            <i class="fas fa-home mr-2"></i>
            {{ __('Home') }}
        </x-button2>
    </div>
    <x-slot name="footer">
        <p>{{ config('app.name') }}</p>
    </x-slot>
</x-admin.app-layout>
