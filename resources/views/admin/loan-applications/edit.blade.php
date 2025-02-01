<x-admin.app-layout>
  <x-slot name="title">
    {{ __('Loan Application') }}
  </x-slot>
  {{--<x-slot name="content_header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Loan Application') }}
    </h2>
</x-slot>--}}
    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="grid grid-cols-2 gap-x-2 mb-6 items-center">
          <h3 class="text-lg font-semibold text-gray-400">
              {{ __('Loan Application')}} #{{ $loanApplication->id }}
          </h3>
          <x-loan-status :status="$loanApplication->status" />
      </div>

        <div class="space-y-6">
            {{-- Customer Information Section --}}
            <x-card>
                <x-card.header>
                    <x-card.title>Información del Cliente</x-card.title>
                </x-card.header>

                <x-card.content>
                        <x-card.detail-item label="Nombre" :value="$loanApplication->customer->details->first_name .
                            ' ' .
                            $loanApplication->customer->details->last_name" />
                        <x-card.detail-item label="Cédula" :value="$loanApplication->customer->NID" />
                        <x-card.detail-item label="Email" :value="$loanApplication->customer->details->email" />
                        <x-card.detail-item label="Fecha de Nacimiento" :value="$loanApplication->customer->details->birthday" />
                        <x-card.detail-item label="Género" :value="$loanApplication->customer->details->gender" />
                        <x-card.detail-item label="Estado Civil" :value="$loanApplication->customer->details->marital_status" />
                        <x-card.detail-item label="Educación" :value="$loanApplication->customer->details->education_level" />
                </x-card.content>
            </x-card>

            {{-- Loan Details Section --}}
            <x-card>
                <x-card.header>
                    <x-card.title>Detalles del Préstamo</x-card.title>
                </x-card.header>

                <x-card.content>
                        <x-card.detail-item label="Monto" :value="number_format($loanApplication->details->amount, 2)" prefix="RD$" />
                        <x-card.detail-item label="Plazo" :value="$loanApplication->details->term" suffix="meses" />
                        <x-card.detail-item label="Tasa" :value="$loanApplication->details->rate" suffix="%" />
                        <x-card.detail-item label="Cuota" :value="number_format($loanApplication->details->quota, 2)" prefix="RD$" />
                        <x-card.detail-item label="Frecuencia" :value="$loanApplication->details->frecuency" />
                        <x-card.detail-item label="Propósito" :value="$loanApplication->details->purpose" />
                        <x-card.detail-item label="Comentario del Cliente" :value="$loanApplication->details->customer_comment" />
                </x-card.content>
            </x-card>

            {{-- Employment Information --}}
            <x-card>
                <x-card.header>
                    <x-card.title>Información Laboral</x-card.title>
                </x-card.header>

                <x-card.content>
                        <x-card.detail-item label="Empresa" :value="$loanApplication->customer->company->name" />
                        <x-card.detail-item label="Cargo" :value="$loanApplication->customer?->jobInfo->role ?? 'No especificado'" />
                          <x-card.detail-item label="Fecha de Ingreso" :value="$loanApplication->customer?->jobInfo?->start_date ?? ''" />
                        <x-card.detail-item label="Salario" :value="number_format($loanApplication->customer?->jobInfo?->salary, 2)" prefix="RD$" />
                        <x-card.detail-item label="Tipo de Pago" :value="$loanApplication->customer?->jobInfo?->payment_type ?? ''" />
                        <x-card.detail-item label="Frecuencia de Pago" :value="$loanApplication->customer?->jobInfo?->payment_frequency ?? ''" />
                        <x-card.detail-item label="Banco Nomina" :value="$loanApplication->customer?->jobInfo?->payment_bank ?? ''" />
                        <x-card.detail-item label="Horario" :value="$loanApplication->customer?->jobInfo?->schedule ?? ''" />
                        <x-card.detail-item label="Supervisor" :value="$loanApplication->customer?->jobInfo?->supervisor_name ?? ''" />
                </x-card.content>
            </x-card>

            {{-- References Section --}}
            <x-card>
                <x-card.header>
                    <x-card.title>Referencias</x-card.title>
                </x-card.header>

                <x-card.content>
                    @foreach ($loanApplication->customer->references as $reference)
                        <div class="mb-4 last:mb-0">
                            {{--<h4 class="font-medium text-gray-900 mb-2">Referencia {{ $loop->iteration }}</h4>--}}
                                <x-card.detail-item label="Referencia" :value="$loop->iteration" />
                                <x-card.detail-item label="Nombre" :value="$reference->name" />
                                <x-card.detail-item label="Teléfono" :value="$reference->phone_number" />
                                <x-card.detail-item label="Relación" :value="$reference->relationship" />
                        </div>
                    @endforeach
                </x-card.content>
            </x-card>

            {{-- Notes Section --}}
            @if ($loanApplication->notes->isNotEmpty())
                <x-card>
                    <x-card.header>
                        <x-card.title>Notas</x-card.title>
                    </x-card.header>

                    <x-card.content>
                        <div class="space-y-4">
                            @foreach ($loanApplication->notes as $note)
                                <div class="grid gap-x-4 items-center grid-cols-4 ">
                                    <div class="flex justify-end col-span-1 flex-shrink-0">
                                        <x-card.avatar :user="$note->user" class="h-10 w-10" />
                                    </div>
                                    <div class="flex justify-start col-span-3">
                                      <x-card.detail-item label="{{ $note->user->name }}: " :value="$note->note" />

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card.content>
                </x-card>
            @endif
        </div>
    </div>

<x-slot name="footer">
  <p>{{ __('Footer') }}</p>
</x-slot>
</x-admin.app-layout>