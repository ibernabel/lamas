@extends('adminlte::page')

@section('title', 'Loan Application')

@section('css')

@endsection

@section('content_header')
    <h1>{{ __('Loan Application') }}</h1>
@stop

@section('content')
{{-- resources/views/loan-applications/show.blade.php --}}
{{--<x-app-layout>--}}
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      {{-- Header Section --}}
      {{--<div class="mb-6">
          <h3 class="text-xl font-semibold text-gray-900">
              Solicitud de Préstamo #{{ $loanApplication->id }}
          </h3>
          <x-loan-status :status="$loanApplication->status" />
      </div>--}}

      <div class="space-y-6">
          {{-- Customer Information Section --}}
          <x-card>
              <x-card.header>
                  <x-card.title>Información del Cliente</x-card.title>
              </x-card.header>
              
              <x-card.content>
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                      <x-detail-item label="Nombre" :value="$loanApplication->customer->details->first_name . ' ' . $loanApplication->customer->details->last_name" />
                      <x-detail-item label="Cédula" :value="$loanApplication->customer->NID" />
                      <x-detail-item label="Email" :value="$loanApplication->customer->details->email" />
                      <x-detail-item label="Fecha de Nacimiento" :value="$loanApplication->customer->details->birthday" />
                      <x-detail-item label="Estado Civil" :value="$loanApplication->customer->details->marital_status" />
                  </dl>
              </x-card.content>
          </x-card>

          {{-- Loan Details Section --}}
          <x-card>
              <x-card.header>
                  <x-card.title>Detalles del Préstamo</x-card.title>
              </x-card.header>
              
              <x-card.content>
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                      <x-detail-item 
                          label="Monto" 
                          :value="number_format($loanApplication->details->amount, 2)" 
                          prefix="RD$" 
                      />
                      <x-detail-item 
                          label="Plazo" 
                          :value="$loanApplication->details->term" 
                          suffix="meses"
                      />
                      <x-detail-item 
                          label="Tasa" 
                          :value="$loanApplication->details->rate" 
                          suffix="%"
                      />
                      <x-detail-item 
                          label="Cuota" 
                          :value="number_format($loanApplication->details->quota, 2)" 
                          prefix="RD$" 
                      />
                      <x-detail-item 
                          label="Frecuencia" 
                          :value="$loanApplication->details->frecuency" 
                      />
                  </dl>
              </x-card.content>
          </x-card>

          {{-- Employment Information --}}
          <x-card>
              <x-card.header>
                  <x-card.title>Información Laboral</x-card.title>
              </x-card.header>
              
              <x-card.content>
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                      <x-detail-item label="Empresa" :value="$loanApplication->customer->company->name" />
                      <x-detail-item label="Cargo" :value="$loanApplication->customer?->jobInfo->role?? 'No especificado'" />
                      <x-detail-item 
                          label="Salario" 
                          :value="number_format($loanApplication->customer?->jobInfo?->salary, 2)" 
                          prefix="RD$"
                      />
                      <x-detail-item label="Fecha de Ingreso" :value="$loanApplication->customer?->jobInfo?->start_date ?? ''" />
                      <x-detail-item label="Supervisor" :value="$loanApplication->customer?->jobInfo?->supervisor_name ?? '' " />
                  </dl>
              </x-card.content>
          </x-card>

          {{-- References Section --}}
          <x-card>
              <x-card.header>
                  <x-card.title>Referencias</x-card.title>
              </x-card.header>
              
              <x-card.content>
                  @foreach($loanApplication->customer->references as $reference)
                      <div class="mb-4 last:mb-0">
                          <h4 class="font-medium text-gray-900 mb-2">Referencia {{ $loop->iteration }}</h4>
                          <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                              <x-detail-item label="Nombre" :value="$reference->name" />
                              <x-detail-item label="Teléfono" :value="$reference->phone_number" />
                              <x-detail-item label="Relación" :value="$reference->relationship" />
                          </dl>
                      </div>
                  @endforeach
              </x-card.content>
          </x-card>

          {{-- Notes Section --}}
          @if($loanApplication->notes->isNotEmpty())
              <x-card>
                  <x-card.header>
                      <x-card.title>Notas</x-card.title>
                  </x-card.header>
                  
                  <x-card.content>
                      <div class="space-y-4">
                          @foreach($loanApplication->notes as $note)
                              <div class="flex gap-x-3">
                                  <div class="flex-shrink-0">
                                      {{--<x-avatar :user="$note->user" class="h-10 w-10" />--}}
                                  </div>
                                  <div>
                                      <p class="text-sm font-medium text-gray-900">{{ $note->user->name }}</p>
                                      <p class="text-sm text-gray-500">{{ $note->note }}</p>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                  </x-card.content>
              </x-card>
          @endif
      </div>
  </div>
{{--</x-app-layout>--}}
@stop

@section('footer')
    <p>{{ __('Footer') }}</p>
@stop

@section('js')

@endsection
