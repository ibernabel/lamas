<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Edit Customer') }} {{-- Changed Title --}}
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="grid grid-cols-2 gap-x-2 grid-rows-1 mb-6 items-center"> {{-- Adjusted grid rows --}}
            <h3 class="text-lg font-semibold text-gray-400">
                {{ __('Customer') }} #{{ $customer->id }} {{-- Use $customer --}}
            </h3>
            {{-- Removed Loan Status --}}
            <small class="text-secondary text-right">{{-- Added text-right --}}
                {{ __('Date updated') }}: {{ $customer->updated_at->format('d/m/Y h:i:s a') }} {{-- Use $customer --}}
            </small>
        </div>
        <form action="{{ route('customers.update', $customer) }}" method="POST"> {{-- Changed Route and Variable --}}
            @csrf
            @method('PUT')

            {{-- General Error Display --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">{{ __('Whoops! Something went wrong.') }}</strong>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                {{-- Customer Information Section --}}
                <x-card.header>
                    <x-card.title>{{ __('Customer Information') }}</x-card.title>
                </x-card.header>
                <x-card>
                    <x-card.content>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input-group>
                                <x-label for="first_name" value="{{ __('First Name') }}" />
                                <x-input2 id="first_name" type="text" name="customer[details][first_name]"
                                    value="{{ old('customer.details.first_name', $customer->details?->first_name ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.first_name" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="last_name" value="{{ __('Last Name') }}" />
                                <x-input2 id="last_name" type="text" name="customer[details][last_name]"
                                    value="{{ old('customer.details.last_name', $customer->details?->last_name ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.last_name" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="NID" value="{{ __('NID') }}" />
                                <x-input2 id="NID" type="text" name="customer[NID]"
                                    value="{{ old('customer.NID', $customer->NID ?? '') }}" /> {{-- Use $customer --}}
                                <x-input-error for="customer.NID" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="birthday" value="{{ __('Birthday') }}" />
                                <x-input2 id="birthday" type="date" name="customer[details][birthday]"
                                    value="{{ old('customer.details.birthday', $customer->details?->birthday ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.birthday" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="cellular" value="{{ __('Cellular') }}" />
                                <x-input2 id="cellular" type="tel" name="customer[details][phones][0][number]"
                                    value="{{ old('customer.details.phones.0.number', $customer->details?->phones->where('type', 'mobile')->first()?->number ?? '') }}" />
                                {{-- Use $customer, adjusted logic --}}
                                <input type="hidden" name="customer[details][phones][0][type]" value="mobile">
                                <x-input-error for="customer.details.phones.0.number" />
                                <x-input-error for="customer.details.phones.0.type" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="home_phone" value="{{ __('Home Phone') }}" />
                                <x-input2 id="home_phone" type="tel" name="customer[details][phones][1][number]"
                                    value="{{ old('customer.details.phones.1.number', $customer->details?->phones->where('type', 'home')->first()?->number ?? '') }}" />
                                {{-- Use $customer, adjusted logic --}}
                                <input type="hidden" name="customer[details][phones][1][type]" value="home">
                                <x-input-error for="customer.details.phones.1.number" />
                                <x-input-error for="customer.details.phones.1.type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input2 id="email" type="email" name="customer[details][email]"
                                    value="{{ old('customer.details.email', $customer->details?->email ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.email" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="marital_status" value="{{ __('Marital Status') }}" />
                                <x-select id="marital_status" name="customer[details][marital_status]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="single"
                                        {{ old('customer.details.marital_status', $customer->details?->marital_status) == 'single' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Single') }}</option>
                                    <option value="married"
                                        {{ old('customer.details.marital_status', $customer->details?->marital_status) == 'married' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Married') }}</option>
                                    <option value="divorced"
                                        {{ old('customer.details.marital_status', $customer->details?->marital_status) == 'divorced' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Divorced') }}</option>
                                    <option value="widowed"
                                        {{ old('customer.details.marital_status', $customer->details?->marital_status) == 'widowed' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Widowed') }}</option>
                                    <option value="other"
                                        {{ old('customer.details.marital_status', $customer->details?->marital_status) == 'other' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Other') }}</option>
                                </x-select>
                                <x-input-error for="customer.details.marital_status" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="nationality" value="{{ __('Nationality') }}" />
                                <x-input2 id="nationality" type="text" name="customer[details][nationality]"
                                    value="{{ old('customer.details.nationality', $customer->details?->nationality ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.nationality" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="gender" value="{{ __('Gender') }}" />
                                <x-select id="gender" name="customer[details][gender]">
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="male"
                                        {{ old('customer.details.gender', $customer->details?->gender) == 'male' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Male') }}</option>
                                    <option value="female"
                                        {{ old('customer.details.gender', $customer->details?->gender) == 'female' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Female') }}</option>
                                </x-select>
                                <x-input-error for="customer.details.gender" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="education_level" value="{{ __('Education Level') }}" />
                                <x-select id="education_level" name="customer[details][education_level]">
                                    <option value="">{{ __('Select education level') }}</option>
                                    <option value="primary"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'primary' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Primary') }}</option>
                                    <option value="secondary"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'secondary' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Secondary') }}</option>
                                    <option value="high_school"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'high_school' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('High School') }}</option>
                                    <option value="bachelor"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'bachelor' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Bachelor') }}</option>
                                    <option value="postgraduate"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'postgraduate' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Postgraduate') }}</option>
                                    <option value="master"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'master' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Master') }}</option>
                                    <option value="doctorate"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'doctorate' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Doctorate') }}</option>
                                    <option value="other"
                                        {{ old('customer.details.education_level', $customer->details?->education_level) == 'other' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Other') }}</option>
                                </x-select>
                                <x-input-error for="customer.details.education_level" />
                            </x-input-group>

                            <x-input-group>
                            </x-input-group>

                            {{-- Address --}}
                            <x-input-group>
                                <br>
                                <x-label value="{{ __('Address') }}" />
                                <hr>
                            </x-input-group>
                            <x-input-group>
                                <br>
                            </x-input-group>
                            <x-input-group>
                                <x-label for="street" value="{{ __('Street') }}" />
                                <x-input2 id="street" type="text"
                                    name="customer[details][addresses][0][street]"
                                    value="{{ old('customer.details.addresses.0.street', $customer->details?->addresses?->first()?->street ?? '') }}" />
                                {{-- Use $customer --}}
                                <input type="hidden" name="customer[details][addresses][0][type]" value="home">
                                <x-input-error for="customer.details.addresses.0.street" />
                                <x-input-error for="customer.details.addresses.0.type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="street2" value="{{ __('Street 2') }}" />
                                <x-input2 id="street2" type="text"
                                    name="customer[details][addresses][0][street2]"
                                    value="{{ old('customer.details.addresses.0.street2', $customer->details?->addresses?->first()?->street2 ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.addresses.0.street2" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="city" value="{{ __('City') }}" />
                                <x-input2 id="city" type="text" name="customer[details][addresses][0][city]"
                                    value="{{ old('customer.details.addresses.0.city', $customer->details?->addresses?->first()?->city ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.addresses.0.city" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="state" value="{{ __('State') }}" />
                                <x-input2 id="state" type="text" name="customer[details][addresses][0][state]"
                                    value="{{ old('customer.details.addresses.0.state', $customer->details?->addresses?->first()?->state ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.addresses.0.state" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="housing_possession_type" value="{{ __('Housing Type') }}" />
                                <x-select id="housing_possession_type"
                                    name="customer[details][housing_possession_type]">
                                    <option value="">{{ __('Select housing type') }}</option>
                                    <option value="owned"
                                        {{ old('customer.details.housing_possession_type', $customer->details?->housing_possession_type) == 'owned' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Owned') }}</option>
                                    <option value="rented"
                                        {{ old('customer.details.housing_possession_type', $customer->details?->housing_possession_type) == 'rented' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Rented') }}</option>
                                    <option value="mortgaged"
                                        {{ old('customer.details.housing_possession_type', $customer->details?->housing_possession_type) == 'mortgaged' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Mortgaged') }}</option>
                                    <option value="other"
                                        {{ old('customer.details.housing_possession_type', $customer->details?->housing_possession_type) == 'other' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Other') }}</option>
                                </x-select>
                                <x-input-error for="customer.details.housing_possession_type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="move_in_date" value="{{ __('Move in date') }}" />
                                {{-- Changed label id --}}
                                <x-input2 id="move_in_date" type="date" name="customer[details][move_in_date]"
                                    value="{{ old('customer.details.move_in_date', $customer->details?->move_in_date ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.details.move_in_date" />
                            </x-input-group>

                            {{-- Vehicle --}}
                            <x-input-group>
                                <br>
                                <x-label value="{{ __('Vehicle') }}" />
                                <hr>
                            </x-input-group>
                            <x-input-group>
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_type" value="{{ __('Vehicle Type') }}" />
                                <x-select id="vehicle_type" name="customer[vehicle][vehicle_type]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="owned"
                                        {{ old('customer.vehicle.vehicle_type', $customer->vehicle?->vehicle_type ?? '') == 'owned' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Owned') }}</option>
                                    <option value="rented"
                                        {{ old('customer.vehicle.vehicle_type', $customer->vehicle?->vehicle_type ?? '') == 'rented' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Rented') }}</option>
                                    <option value="financed"
                                        {{ old('customer.vehicle.vehicle_type', $customer->vehicle?->vehicle_type ?? '') == 'financed' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Financed') }}</option>
                                    <option value="borrowed"
                                        {{ old('customer.vehicle.vehicle_type', $customer->vehicle?->vehicle_type ?? '') == 'borrowed' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Borrowed') }}</option>
                                    <option value="none"
                                        {{ old('customer.vehicle.vehicle_type', $customer->vehicle?->vehicle_type ?? '') == 'none' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('None') }}</option>
                                    <option value="other"
                                        {{ old('customer.vehicle.vehicle_type', $customer->vehicle?->vehicle_type ?? '') == 'other' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Other') }}</option>
                                </x-select>
                                <x-input-error for="customer.vehicle.vehicle_type" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_brand" value="{{ __('Vehicle Brand') }}" />
                                <x-input2 id="vehicle_brand" type="text" name="customer[vehicle][vehicle_brand]"
                                    value="{{ old('customer.vehicle.vehicle_brand', $customer->vehicle?->vehicle_brand ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.vehicle.vehicle_brand" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_model" value="{{ __('Vehicle Model') }}" />
                                <x-input2 id="vehicle_model" type="text" name="customer[vehicle][vehicle_model]"
                                    value="{{ old('customer.vehicle.vehicle_model', $customer->vehicle?->vehicle_model ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.vehicle.vehicle_model" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_year" value="{{ __('Vehicle Year') }}" />
                                <x-input2 id="vehicle_year" type="number" min="1900"
                                    max="{{ date('Y') }}" name="customer[vehicle][vehicle_year]"
                                    value="{{ old('customer.vehicle.vehicle_year', $customer->vehicle?->vehicle_year ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.vehicle.vehicle_year" />
                            </x-input-group>
                        </div>
                    </x-card.content>
                </x-card>

                {{-- Removed Loan Details Section --}}

                {{-- Employment Information --}}
                <x-card.header>
                    <x-card.title>{{ __('Employment Information') }}</x-card.title>
                </x-card.header>
                <x-card>
                    <x-card.content>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input-group>
                                <x-label for="is_self_employed" value="{{ __('Self Employed') }}" />
                                <x-select id="is_self_employed" name="customer[jobInfo][is_self_employed]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="1"
                                        {{ old('customer.jobInfo.is_self_employed', $customer->jobInfo?->is_self_employed) == 1 ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Yes') }}</option>
                                    <option value="0"
                                        {{ old('customer.jobInfo.is_self_employed', $customer->jobInfo?->is_self_employed) == 0 ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('No') }}</option>
                                </x-select>
                                <x-input-error for="customer.jobInfo.is_self_employed" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="company_name" value="{{ __('Company') }}" />
                                <x-input2 id="company_name" type="text" name="customer[company][name]"
                                    value="{{ old('customer.company.name', $customer->company?->name ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.company.name" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="company_phone" value="{{ __('Phone') }}" />
                                <x-input2 id="company_phone" type="tel"
                                    name="customer[company][phones][0][number]"
                                    value="{{ old('customer.company.phones.0.number', $customer->company?->phones->where('type', 'work')->first()?->number ?? '') }}" />
                                {{-- Use $customer, adjusted logic --}}
                                <input type="hidden" name="customer[company][phones][0][type]" value="work">
                                <x-input-error for="customer.company.phones.0.number" />
                                <x-input-error for="customer.company.phones.0.type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="company_address" value="{{ __('Address') }}" />
                                <x-input2 id="company_address" type="text"
                                    name="customer[company][addresses][0][street]"
                                    value="{{ old('customer.company.addresses.0.street', $customer->company?->addresses->where('type', 'work')->first()?->street ?? '') }}" />
                                {{-- Use $customer, adjusted logic --}}
                                <input type="hidden" name="customer[company][addresses][0][type]" value="work">
                                <x-input-error for="customer.company.addresses.0.street" />
                                <x-input-error for="customer.company.addresses.0.type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="role" value="{{ __('Role') }}" />
                                <x-input2 id="role" type="text" name="customer[jobInfo][role]"
                                    value="{{ old('customer.jobInfo.role', $customer->jobInfo?->role ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.role" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="start_date" value="{{ __('Start Date') }}" />
                                <x-input2 id="start_date" type="date" name="customer[jobInfo][start_date]"
                                    value="{{ old('customer.jobInfo.start_date', $customer->jobInfo?->start_date ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.start_date" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="salary" value="{{ __('Salary') }}" />
                                <x-input2 id="salary" type="number" step="0.01"
                                    name="customer[jobInfo][salary]"
                                    value="{{ old('customer.jobInfo.salary', $customer->jobInfo?->salary ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.salary" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="payment_type" value="{{ __('Payment Type') }}" />
                                <x-select id="payment_type" name="customer[jobInfo][payment_type]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="cash"
                                        {{ old('customer.jobInfo.payment_type', $customer->jobInfo?->payment_type) == 'cash' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Cash') }}</option>
                                    <option value="bank_transfer"
                                        {{ old('customer.jobInfo.payment_type', $customer->jobInfo?->payment_type) == 'bank_transfer' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Bank Transfer') }}</option>
                                </x-select>
                                <x-input-error for="customer.jobInfo.payment_type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="payment_frequency" value="{{ __('Payment Frequency') }}" />
                                <x-select id="payment_frequency" name="customer[jobInfo][payment_frequency]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="weekly"
                                        {{ old('customer.jobInfo.payment_frequency', $customer->jobInfo?->payment_frequency) == 'weekly' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Weekly') }}</option>
                                    <option value="biweekly"
                                        {{ old('customer.jobInfo.payment_frequency', $customer->jobInfo?->payment_frequency) == 'biweekly' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Biweekly') }}</option>
                                    <option value="monthly"
                                        {{ old('customer.jobInfo.payment_frequency', $customer->jobInfo?->payment_frequency) == 'monthly' ? 'selected' : '' }}>
                                        {{-- Use $customer --}}
                                        {{ __('Monthly') }}</option>
                                </x-select>
                                <x-input-error for="customer.jobInfo.payment_frequency" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="payment_bank" value="{{ __('Payment Bank') }}" />
                                <x-input2 id="payment_bank" type="text" name="customer[jobInfo][payment_bank]"
                                    value="{{ old('customer.jobInfo.payment_bank', $customer->jobInfo?->payment_bank ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.payment_bank" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="other_incomes" value="{{ __('Other Incomes') }}" />
                                <x-input2 id="other_incomes" type="text" name="customer[jobInfo][other_incomes]"
                                    value="{{ old('customer.jobInfo.other_incomes', $customer->jobInfo?->other_incomes ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.other_incomes" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="other_incomes_source" value="{{ __('Other Incomes Source') }}" />
                                <x-input2 id="other_incomes_source" type="text"
                                    name="customer[jobInfo][other_incomes_source]"
                                    value="{{ old('customer.jobInfo.other_incomes_source', $customer->jobInfo?->other_incomes_source ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.other_incomes_source" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="schedule" value="{{ __('Schedule') }}" />
                                <x-input2 id="schedule" type="text" name="customer[jobInfo][schedule]"
                                    value="{{ old('customer.jobInfo.schedule', $customer->jobInfo?->schedule ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.schedule" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="supervisor_name" value="{{ __('Supervisor Name') }}" />
                                <x-input2 id="supervisor_name" type="text"
                                    name="customer[jobInfo][supervisor_name]"
                                    value="{{ old('customer.jobInfo.supervisor_name', $customer->jobInfo?->supervisor_name ?? '') }}" />
                                {{-- Use $customer --}}
                                <x-input-error for="customer.jobInfo.supervisor_name" />
                            </x-input-group>
                        </div>
                    </x-card.content>
                </x-card>

                {{-- References Section --}}
                <x-card.header>
                    <x-card.title>{{ __('References') }}</x-card.title>
                </x-card.header>
                <x-card>
                    @forelse ($customer->references ?? [] as $reference)
                        {{-- Use $customer --}}
                        <x-card.content class="border-b last:border-b-0"> {{-- Added border for separation --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-input-group class="md:col-span-2"> {{-- Span title --}}
                                    <h4 class="font-medium text-gray-700">Referencia {{ $loop->iteration }}</h4>
                                    <input type="hidden" name="customer[references][{{ $loop->index }}][id]"
                                        value="{{ $reference->id }}" /> {{-- Hidden ID for update --}}
                                </x-input-group>

                                <x-input-group>
                                    <x-label for="reference_name_{{ $loop->index }}"
                                        value="{{ __('Name') }}" />
                                    <x-input2 id="reference_name_{{ $loop->index }}" type="text"
                                        name="customer[references][{{ $loop->index }}][name]"
                                        value="{{ old('customer.references.' . $loop->index . '.name', $reference->name ?? '') }}" />
                                    <x-input-error for="customer.references.{{ $loop->index }}.name" />
                                </x-input-group>

                                <x-input-group>
                                    <x-label for="reference_occupation_{{ $loop->index }}"
                                        value="{{ __('Occupation') }}" />
                                    <x-input2 id="reference_occupation_{{ $loop->index }}" type="text"
                                        name="customer[references][{{ $loop->index }}][occupation]"
                                        value="{{ old('customer.references.' . $loop->index . '.occupation', $reference->occupation ?? '') }}" />
                                    <x-input-error for="customer.references.{{ $loop->index }}.occupation" />
                                </x-input-group>

                                <x-input-group>
                                    <x-label for="reference_relationship_{{ $loop->index }}"
                                        value="{{ __('Relationship') }}" />
                                    <x-input2 id="reference_relationship_{{ $loop->index }}" type="text"
                                        name="customer[references][{{ $loop->index }}][relationship]"
                                        value="{{ old('customer.references.' . $loop->index . '.relationship', $reference->relationship ?? '') }}" />
                                    <x-input-error for="customer.references.{{ $loop->index }}.relationship" />
                                </x-input-group>

                                <x-input-group>
                                    <x-label for="reference_phone_{{ $loop->index }}"
                                        value="{{ __('Phone') }}" />
                                    <x-input2 id="reference_phone" type="tel"
                                        name="customer[references][{{ $loop->index }}][phones][0][number]"
                                        value="{{ old('customer.references.' . $loop->index . '.phones.0.number', $reference->phones[0]?->number ?? '') }}" />
                                    <input type="hidden"
                                        name="customer[references][{{ $loop->index }}][phones][0][type]"
                                        value="mobile">
                                    <x-input-error for="customer.references.{{ $loop->index }}.phones.0.number" />
                                </x-input-group>
                            </div>
                        </x-card.content>
                    @empty
                        <x-card.content>
                            <p>{{ __('No references found for this customer.') }}</p>
                            {{-- Optionally add fields here to create the first reference if none exist --}}
                        </x-card.content>
                    @endforelse
                </x-card>
            </div> {{-- End space-y-6 --}}

            <div class="sticky bottom-0 bg-white border-t border-gray-200 p-2 flex justify-end space-x-4 my-2">
                <x-button2 type="button" variant="secondary" onclick="window.history.back()">
                    <i class="fas fa-times mr-2"></i>
                    {{ __('Cancel') }}
                </x-button2>
                <x-button2 type="submit" variant="success">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Update') }} {{-- Changed Button Text --}}
                </x-button2>
            </div>
        </form>
    </div> {{-- End max-w-5xl --}}

    <x-slot name="footer">
        <p>{{ config('app.name') }}</p>
    </x-slot>
</x-admin.app-layout>
