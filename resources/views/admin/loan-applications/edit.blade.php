<x-admin.app-layout>
    <x-slot name="title">
        {{ __('Edit Loan Application') }}
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
        <form action="{{ route('loan-applications.update', $loanApplication) }}" method="POST">
            @csrf
            @method('PUT')

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
                                    value="{{ old('customer.details.first_name', $loanApplication->customer->details->first_name) }}" />
                                <x-input-error for="customer.details.first_name" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="last_name" value="{{ __('Last Name') }}" />
                                <x-input2 id="last_name" type="text" name="customer[details][last_name]"
                                    value="{{ old('customer.details.last_name', $loanApplication->customer->details->last_name) }}" />
                                <x-input-error for="customer.details.last_name" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="NID" value="{{ __('NID') }}" />
                                <x-input2 id="NID" type="text" name="customer.NID"
                                    value="{{ old('customer.NID', $loanApplication->customer->NID) }}" />
                                <x-input-error for="customer.NID" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="birthday" value="{{ __('Birthday') }}" />
                                <x-input2 id="birthday" type="date" name="customer[details][birthday]"
                                    value="{{ old('customer.details.birthday', $loanApplication->customer->details->birthday) }}" />
                                <x-input-error for="customer.details.birthday" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="cellular" value="{{ __('Cellular') }}" />
                                <x-input2 id="cellular" type="tel" name="customer[details][phones][0][number]"
                                    value="{{ old('customer.details.phones.0.number', $loanApplication->customer->details->phones[0]->number) }}" />
                                <x-input-error for="customer.details.phones.0.number" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="home_phone" value="{{ __('Home Phone') }}" />
                                <x-input2 id="home_phone" type="tel" name="customer[details][phones][1][number]"
                                    value="{{ old('customer.details.phones.1.number', $loanApplication->customer->details->phones[1]->number ?? '') }}" />
                                <x-input-error for="customer.details.phones.1.number" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input2 id="email" type="email" name="customer[details][email]"
                                    value="{{ old('customer.details.email', $loanApplication->customer->details->email) }}" />
                                <x-input-error for="customer.details.email" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="marital_status" value="{{ __('Marital Status') }}" />
                                <x-select id="marital_status" name="customer[details][housing_type]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="single"
                                        {{ old('customer.details.marital_status', $loanApplication->customer->details->marital_status) == 'single' ? 'selected' : '' }}>
                                        {{ __('Single') }}</option>
                                    <option value="married"
                                        {{ old('customer.details.marital_status', $loanApplication->customer->details->marital_status) == 'married' ? 'selected' : '' }}>
                                        {{ __('Married') }}</option>
                                    <option value="divorced"
                                        {{ old('customer.details.marital_status', $loanApplication->customer->details->marital_status) == 'divorced' ? 'selected' : '' }}>
                                        {{ __('Divorced') }}</option>
                                    <option value="widowed"
                                        {{ old('customer.details.marital_status', $loanApplication->customer->details->marital_status) == 'widowed' ? 'selected' : '' }}>
                                        {{ __('Widowed') }}</option>
                                    <option value="other"
                                        {{ old('customer.details.marital_status', $loanApplication->customer->details->marital_status) == 'other' ? 'selected' : '' }}>
                                        {{ __('Other') }}</option>
                                </x-select>
                                <x-input-error for="customer.details.marital_status" />

                            </x-input-group>
                            <x-input-group>
                                <x-label for="nationality" value="{{ __('Nationality') }}" />
                                <x-input2 id="nationality" type="text" name="customer.details.nationality"
                                    value="{{ old('customer.details.nationality', $loanApplication->customer->details->nationality) }}" />
                                <x-input-error for="customer.details.nationality" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="gender" value="{{ __('Gender') }}" />
                                <x-select id="gender" name="customer[details][gender]">
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="male"
                                        {{ old('customer.details.gender', $loanApplication->customer->details->gender) == 'male' ? 'selected' : '' }}>
                                        {{ __('Male') }}</option>
                                    <option value="female"
                                        {{ old('customer.details.gender', $loanApplication->customer->details->gender) == 'female' ? 'selected' : '' }}>
                                        {{ __('Female') }}</option>
                                </x-select>
                                <x-input-error for="customer.details.gender" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="education_level" value="{{ __('Education Level') }}" />
                                <x-select id="education_level" name="customer[details][education_level]">
                                    <option value="">{{ __('Select education level') }}</option>
                                    <option value="primary"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'primary' ? 'selected' : '' }}>
                                        {{ __('Primary') }}</option>
                                    <option value="secondary"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'secondary' ? 'selected' : '' }}>
                                        {{ __('Secondary') }}</option>
                                    <option value="high_school"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'high_school' ? 'selected' : '' }}>
                                        {{ __('High School') }}</option>
                                    <option value="bachelor"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'bachelor' ? 'selected' : '' }}>
                                        {{ __('Bachelor') }}</option>
                                    <option value="postgraduate"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'postgraduate' ? 'selected' : '' }}>
                                        {{ __('Postgraduate') }}</option>
                                    <option value="master"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'master' ? 'selected' : '' }}>
                                        {{ __('Master') }}</option>
                                    <option value="doctorate"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'doctorate' ? 'selected' : '' }}>
                                        {{ __('Doctorate') }}</option>
                                    <option value="other"
                                        {{ old('customer.details.education_level', $loanApplication->customer->details->education_level) == 'other' ? 'selected' : '' }}>
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
                                <x-input2 id="street" type="text" name="customer[details][addresses][0][street]"
                                    value="{{ old('customer.details.addresses.0.street', $loanApplication->customer->details->addresses[0]->street ?? '') }}" />
                                <x-input-error for="customer.details.addresses.0.street" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="street2" value="{{ __('Street 2') }}" />
                                <x-input2 id="street2" type="text"
                                    name="customer[details][addresses][0][street2]"
                                    value="{{ old('customer.details.addresses.0.street2', $loanApplication->customer->details->addresses[0]->street2 ?? '') }}" />
                                <x-input-error for="customer.details.addresses.0.street2" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="city" value="{{ __('City') }}" />
                                <x-input2 id="city" type="text" name="customer[details][addresses][0][city]"
                                    value="{{ old('customer.details.addresses.0.city', $loanApplication->customer->details->addresses[0]->city ?? '') }}" />
                                <x-input-error for="customer.details.addresses.0.city" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="state" value="{{ __('State') }}" />
                                <x-input2 id="state" type="text" name="customer[details][addresses][0][state]"
                                    value="{{ old('customer.details.addresses.0.state', $loanApplication->customer->details->addresses[0]->state ?? '') }}" />
                                <x-input-error for="customer.details.addresses.0.state" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="housing_type" value="{{ __('Housing Type') }}" />
                                <x-select id="housing_type" name="customer[details][housing_type]">
                                    <option value="">{{ __('Select housing type') }}</option>
                                    <option value="owned"
                                        {{ old('customer.details.housing_type', $loanApplication->customer->details->housing_type) == 'owned' ? 'selected' : '' }}>
                                        {{ __('Owned') }}</option>
                                    <option value="rented"
                                        {{ old('customer.details.housing_type', $loanApplication->customer->details->housing_type) == 'rented' ? 'selected' : '' }}>
                                        {{ __('Rented') }}</option>
                                    <option value="mortgaged"
                                        {{ old('customer.details.housing_type', $loanApplication->customer->details->housing_type) == 'mortgaged' ? 'selected' : '' }}>
                                        {{ __('Mortgaged') }}</option>
                                    <option value="other"
                                        {{ old('customer.details.housing_type', $loanApplication->customer->details->housing_type) == 'other' ? 'selected' : '' }}>
                                        {{ __('Other') }}</option>
                                </x-select>

                                <x-input-error for="customer.details.housing_type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="state" value="{{ __('Move in date') }}" />
                                <x-input2 id="state" type="date" name="customer[details][move_in_date]"
                                    value="{{ old('customer.details.move_in_date', $loanApplication->customer->details->move_in_date ?? '') }}" />
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
                                        {{ old('customer.vehicle.vehicle_type', $loanApplication->customer->vehicle->vehicle_type ?? '') == 'owned' ? 'selected' : '' }}>
                                        {{ __('Owned') }}</option>
                                    <option value="rented"
                                        {{ old('customer.vehicle.vehicle_type', $loanApplication->customer->vehicle->vehicle_type ?? '') == 'rented' ? 'selected' : '' }}>
                                        {{ __('Rented') }}</option>
                                    <option value="financed"
                                        {{ old('customer.vehicle.vehicle_type', $loanApplication->customer->vehicle->vehicle_type ?? '') == 'financed' ? 'selected' : '' }}>
                                        {{ __('Financed') }}</option>
                                    <option value="borrowed"
                                        {{ old('customer.vehicle.vehicle_type', $loanApplication->customer->vehicle->vehicle_type ?? '') == 'borrowed' ? 'selected' : '' }}>
                                        {{ __('Borrowed') }}</option>
                                    <option value="none"
                                        {{ old('customer.vehicle.vehicle_type', $loanApplication->customer->vehicle->vehicle_type ?? '') == 'none' ? 'selected' : '' }}>
                                        {{ __('None') }}</option>
                                    <option value="other"
                                        {{ old('customer.vehicle.vehicle_type', $loanApplication->customer->vehicle->vehicle_type ?? '') == 'other' ? 'selected' : '' }}>
                                        {{ __('Other') }}</option>
                                </x-select>
                                <x-input-error for="customer.vehicle.vehicle_type" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_brand" value="{{ __('Vehicle Brand') }}" />
                                <x-input2 id="vehicle_brand" type="text" name="customer[vehicle][vehicle_brand]"
                                    value="{{ old('customer.vehicle.vehicle_brand', $loanApplication->customer->vehicle->vehicle_brand ?? '') }}" />
                                <x-input-error for="customer.vehicle.vehicle_brand" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_model" value="{{ __('Vehicle Model') }}" />
                                <x-input2 id="vehicle_model" type="text" name="customer[vehicle][vehicle_model]"
                                    value="{{ old('customer.vehicle.vehicle_model', $loanApplication->customer->vehicle->vehicle_model ?? '') }}" />
                                <x-input-error for="customer.vehicle.vehicle_model" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="vehicle_year" value="{{ __('Vehicle Year') }}" />
                                <x-input2 id="vehicle_year" type="number" min="1900" max="{{ date('Y') }}" name="customer[vehicle][vehicle_year]"
                                    value="{{ old('customer.vehicle.vehicle_year', $loanApplication->customer->vehicle->vehicle_year ?? '') }}" />
                                <x-input-error for="customer.vehicle.vehicle_year" />
                            </x-input-group>

                        </div>

                    </x-card.content>
                </x-card>

                {{-- Loan Details Section --}}
                <x-card.header>
                    <x-card.title>{{ __('Loan Details') }}</x-card.title>
                </x-card.header>
                <x-card>

                    <x-card.content>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input-group>
                                <x-label for="amount" value="Amount" />
                                <x-input2 id="amount" type="number" step="0.01" name="details[amount]"
                                    value="{{ old('details.amount', $loanApplication->details->amount) }}" />
                                <x-input-error for="details.amount" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="term" value="Term (months)" />
                                <x-input2 id="term" type="number" name="details[term]"
                                    value="{{ old('details.term', $loanApplication->details->term) }}" />
                                <x-input-error for="details.term" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="rate" value="Rate (%)" />
                                <x-input2 id="rate" type="number" step="0.01" name="details[rate]"
                                    value="{{ old('details.rate', $loanApplication->details->rate) }}" />
                                <x-input-error for="details.rate" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="frequency" value="Payment Frequency" />
                                <x-select id="frequency" name="details[frequency]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="weekly"
                                        {{ old('details.frequency', $loanApplication->details->frequency) == 'weekly' ? 'selected' : '' }}>
                                        {{ __('Weekly') }}</option>
                                    <option value="biweekly"
                                        {{ old('details.frequency', $loanApplication->details->frequency) == 'biweekly' ? 'selected' : '' }}>
                                        {{ __('Biweekly') }}</option>
                                    <option value="monthly"
                                        {{ old('details.frequency', $loanApplication->details->frequency) == 'monthly' ? 'selected' : '' }}>
                                        {{ __('Monthly') }}</option>
                                </x-select>
                                <x-input-error for="details.frequency" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="purpose" value="Purpose" />
                                <x-textarea id="purpose" name="details[purpose]">
                                    {{ old('details.purpose', $loanApplication->details->purpose) }}
                                </x-textarea>
                                <x-input-error for="details.purpose" />
                            </x-input-group>
                        </div>
                    </x-card.content>
                </x-card>

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
                                        {{ old('customer.jobInfo.is_self_employed', $loanApplication->customer->jobInfo->is_self_employed) == 1 ? 'selected' : '' }}>
                                        {{ __('Yes') }}</option>
                                    <option value="0"
                                        {{ old('customer.jobInfo.is_self_employed', $loanApplication->customer->jobInfo->is_self_employed) == 0 ? 'selected' : '' }}>
                                        {{ __('No') }}</option>
                                </x-select>
                                <x-input-error for="customer.jobInfo.is_self_employed" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="company_name" value="{{ __('Company') }}" />
                                <x-input2 id="company_name" type="text" name="customer[company][name]"
                                    value="{{ old('customer.company.name', $loanApplication->customer->company->name) }}" />
                                <x-input-error for="customer.company.name" />
                            </x-input-group>
                            
                            <x-input-group>
                              <x-label for="company_phone" value="{{ __('Phone') }}" />
                              <x-input2 id="company_phone" type="tel"
                                  name="customer[company][phones][0][number]"
                                  value="{{ old('customer.company.phones.0.number', $loanApplication->customer->company->phones[0]->number ?? '') }}" />
                              <x-input-error for="customer.company.phones.0.number" />
                          </x-input-group>

                            <x-input-group>
                              <x-label for="company_address" value="{{ __('Address') }}" />
                              <x-input2 id="company_address" type="text"
                                  name="customer[company][addresses][0][street]"
                                  value="{{ old('customer.company.addresses.0.street', $loanApplication->customer->company->addresses[0]->street ?? '') }}" />
                              <x-input-error for="customer.company.addresses.0.street" />
                          </x-input-group>

                            <x-input-group>
                                <x-label for="role" value="{{ __('Role') }}" />
                                <x-input2 id="role" type="text" name="customer[jobInfo][role]"
                                    value="{{ old('customer.jobInfo.role', $loanApplication->customer->jobInfo->role) }}" />
                                <x-input-error for="customer.jobInfo.role" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="start_date" value="{{ __('Start Date') }}" />
                                <x-input2 id="start_date" type="date" name="customer[jobInfo][start_date]"
                                    value="{{ old('customer.jobInfo.start_date', $loanApplication->customer->jobInfo->start_date) }}" />
                                <x-input-error for="customer.jobInfo.start_date" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="salary" value="{{ __('Salary') }}" />
                                <x-input2 id="salary" type="number" step="0.01"
                                    name="customer[jobInfo][salary]"
                                    value="{{ old('customer.jobInfo.salary', $loanApplication->customer->jobInfo->salary) }}" />
                                <x-input-error for="customer.jobInfo.salary" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="payment_type" value="{{ __('Payment Type') }}" />
                                <x-select id="payment_type" name="customer[jobInfo][payment_type]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="cash"
                                        {{ old('customer.jobInfo.payment_type', $loanApplication->customer->jobInfo->payment_type) == 'cash' ? 'selected' : '' }}>
                                        {{ __('Cash') }}</option>
                                    <option value="bank_transfer"
                                        {{ old('customer.jobInfo.payment_type', $loanApplication->customer->jobInfo->payment_type) == 'bank_transfer' ? 'selected' : '' }}>
                                        {{ __('Bank Transfer') }}</option>
                                </x-select>
                                <x-input-error for="customer.jobInfo.payment_type" />
                            </x-input-group>

                            <x-input-group>
                                <x-label for="payment_frequency" value="{{ __('Payment Frequency') }}" />
                                <x-select id="payment_frequency" name="customer[jobInfo][payment_frequency]">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="weekly"
                                        {{ old('customer.jobInfo.payment_frequency', $loanApplication->customer->jobInfo->payment_frequency) == 'weekly' ? 'selected' : '' }}>
                                        {{ __('Weekly') }}</option>
                                    <option value="biweekly"
                                        {{ old('customer.jobInfo.payment_frequency', $loanApplication->customer->jobInfo->payment_frequency) == 'biweekly' ? 'selected' : '' }}>
                                        {{ __('Biweekly') }}</option>
                                    <option value="monthly"
                                        {{ old('customer.jobInfo.payment_frequency', $loanApplication->customer->jobInfo->payment_frequency) == 'monthly' ? 'selected' : '' }}>
                                        {{ __('Monthly') }}</option>
                                </x-select>
                                <x-input-error for="customer.jobInfo.payment_frequency" />
                            </x-input-group>
                            
                            <x-input-group>
                              <x-label for="payment_bank" value="{{ __('Payment Bank') }}" />
                              <x-input2 id="payment_bank" type="text" name="customer[jobInfo][payment_bank]"
                                  value="{{ old('customer.jobInfo.payment_bank', $loanApplication->customer->jobInfo->payment_bank) }}" />
                              <x-input-error for="customer.jobInfo.payment_bank" />
                          </x-input-group>


                            <x-input-group>
                                <x-label for="other_incomes" value="{{ __('Other Incomes') }}" />
                                <x-input2 id="other_incomes" type="text" name="customer[jobInfo][other_incomes]"
                                    value="{{ old('customer.jobInfo.other_incomes', $loanApplication->customer->jobInfo->other_incomes) }}" />
                                <x-input-error for="customer.jobInfo.other_incomes" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="other_incomes_source" value="{{ __('Other Incomes Source') }}" />
                                <x-input2 id="other_incomes_source" type="text"
                                    name="customer[jobInfo][other_incomes_source]"
                                    value="{{ old('customer.jobInfo.other_incomes_source', $loanApplication->customer->jobInfo->other_incomes_source) }}" />
                                <x-input-error for="customer.jobInfo.other_incomes_source" />
                            </x-input-group>
                            <x-input-group>
                                <x-label for="schedule" value="{{ __('Schedule') }}" />
                                <x-input2 id="schedule" type="text" name="customer[jobInfo][schedule]"
                                    value="{{ old('customer.jobInfo.schedule', $loanApplication->customer->jobInfo->schedule) }}" />
                                <x-input-error for="customer.jobInfo.schedule" />
                            </x-input-group>

                            <x-input-group>
                              <x-label for="supervisor_name" value="{{ __('Supervisor Name') }}" />
                              <x-input2 id="supervisor_name" type="text"
                                  name="customer[jobInfo][supervisor_name]"
                                  value="{{ old('customer.jobInfo.supervisor_name', $loanApplication->customer->jobInfo->supervisor_name) }}" />
                              <x-input-error for="customer.jobInfo.supervisor_name" />
                          </x-input-group>
                        </div>
                    </x-card.content>
                </x-card>

                <div class="flex justify-end space-x-4 my-4">
                    <x-button2 type="button" variant="secondary" onclick="window.history.back()">
                        {{ __('Cancel') }}
                    </x-button2>
                    <x-button2 type="submit" variant="primary" class="text-dark-900">
                        {{ __('Update') }}
                    </x-button2>
                </div>
            </div>
        </form>
    </div>
</x-admin.app-layout>
