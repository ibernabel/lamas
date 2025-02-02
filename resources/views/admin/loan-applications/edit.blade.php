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
                  <x-card.title>{{__('Customer Information')}}</x-card.title>
              </x-card.header>
              <x-card>

                  <x-card.content>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <x-input-group>
                              <x-label for="first_name" value="First Name" />
                              <x-input2 id="first_name" type="text" name="customer[details][first_name]"
                                  value="{{ old('customer.details.first_name', $loanApplication->customer->details->first_name) }}" />
                              <x-input-error for="customer.details.first_name" />
                          </x-input-group>

                          <x-input-group>
                              <x-label for="last_name" value="Last Name" />
                              <x-input2 id="last_name" type="text" name="customer[details][last_name]"
                                  value="{{ old('customer.details.last_name', $loanApplication->customer->details->last_name) }}" />
                              <x-input-error for="customer.details.last_name" />
                          </x-input-group>

                          <x-input-group>
                              <x-label for="email" value="Email" />
                              <x-input2 id="email" type="email" name="customer[details][email]"
                                  value="{{ old('customer.details.email', $loanApplication->customer->details->email) }}" />
                              <x-input-error for="customer.details.email" />
                          </x-input-group>

                          <x-input-group>
                              <x-label for="birthday" value="Birthday" />
                              <x-input2 id="birthday" type="date" name="customer[details][birthday]"
                                  value="{{ old('customer.details.birthday', $loanApplication->customer->details->birthday) }}" />
                              <x-input-error for="customer.details.birthday" />
                          </x-input-group>

                          <x-input-group>
                              <x-label for="gender" value="Gender" />
                              <x-select id="gender" name="customer[details][gender]">
                                  <option value="">Select Gender</option>
                                  <option value="male" {{ old('customer.details.gender', $loanApplication->customer->details->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                  <option value="female" {{ old('customer.details.gender', $loanApplication->customer->details->gender) == 'female' ? 'selected' : '' }}>Female</option>
                              </x-select>
                              <x-input-error for="customer.details.gender" />
                          </x-input-group>
                      </div>
                  </x-card.content>
              </x-card>

              {{-- Loan Details Section --}}
              <x-card.header>
                  <x-card.title>{{__('Loan Details')}}</x-card.title>
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
                                  <option value="">Select Frequency</option>
                                  <option value="weekly" {{ old('details.frequency', $loanApplication->details->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                  <option value="biweekly" {{ old('details.frequency', $loanApplication->details->frequency) == 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                                  <option value="monthly" {{ old('details.frequency', $loanApplication->details->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
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
                  <x-card.title>{{__('Employment Information')}}</x-card.title>
              </x-card.header>
              <x-card>

                  <x-card.content>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <x-input-group>
                              <x-label for="company_name" value="Company" />
                              <x-input2 id="company_name" type="text" name="customer[company][name]"
                                  value="{{ old('customer.company.name', $loanApplication->customer->company->name) }}" />
                              <x-input-error for="customer.company.name" />
                          </x-input-group>

                          <x-input-group>
                              <x-label for="role" value="Role" />
                              <x-input2 id="role" type="text" name="customer[jobInfo][role]"
                                  value="{{ old('customer.jobInfo.role', $loanApplication->customer->jobInfo->role) }}" />
                              <x-input-error for="customer.jobInfo.role" />
                          </x-input-group>

                          <x-input-group>
                              <x-label for="salary" value="Salary" />
                              <x-input2 id="salary" type="number" step="0.01" name="customer[jobInfo][salary]"
                                  value="{{ old('customer.jobInfo.salary', $loanApplication->customer->jobInfo->salary) }}" />
                              <x-input-error for="customer.jobInfo.salary" />
                          </x-input-group>
                      </div>
                  </x-card.content>
              </x-card>

              <div class="flex justify-end space-x-4">
                  <x-button2 type="button" variant="secondary" onclick="window.history.back()">
                      {{__('Cancel')}}
                  </x-button2>
                  <x-button2 type="submit" variant="primary">
                      {{__('Update')}}
                  </x-button2>
              </div>
          </div>
      </form>
  </div>
</x-admin.app-layout>