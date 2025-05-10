<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class LoanApplicationRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true; // La autorización se maneja a través de políticas y middleware
  }

  protected function prepareForValidation()
  {
    if ($this->has('details') && is_array($this->input('details'))) {
      $details = $this->input('details');
      if (array_key_exists('amount', $details) && $details['amount'] === null) {
        $details['amount'] = 0;
        $this->merge(['details' => $details]);
      }
      if (array_key_exists('term', $details) && $details['term'] === null) {
        $details['term'] = 0;
        $this->merge(['details' => $details]);
      }
      if (array_key_exists('rate', $details) && $details['rate'] === null) {
        $details['rate'] = 0;
        $this->merge(['details' => $details]);
      }
      if (array_key_exists('frequency', $details) && $details['frequency'] === null) {
        $details['frequency'] = 'monthly';
        $this->merge(['details' => $details]);
      }
    }

    // Filtrar los teléfonos de la compañía: solo procesar si se proporciona un número.
    if ($this->has('customer.company.phones') && is_array($this->input('customer.company.phones'))) {
      $companyPhonesInput = $this->input('customer.company.phones');
      $validCompanyPhones = [];
      foreach ($companyPhonesInput as $phoneData) {
        // Considerar el teléfono solo si 'number' está presente y no es una cadena vacía.
        if (isset($phoneData['number']) && $phoneData['number'] !== '' && $phoneData['number'] !== null) {
          $validCompanyPhones[] = $phoneData;
        }
      }

      // Actualizar la entrada 'customer.company.phones' con los teléfonos filtrados.
      $customerData = $this->input('customer', []);
      $companyData = $customerData['company'] ?? [];
      $companyData['phones'] = $validCompanyPhones;
      $customerData['company'] = $companyData;
      $this->merge(['customer' => $customerData]);
    }
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    // Get the LoanApplication model instance from the route parameter if it exists
    $loanApplicationId = $this->route('id');
    $loanApplication = \App\Models\LoanApplication::with(['customer.details'])->find($loanApplicationId);
    // Get the CustomerDetail ID to ignore, if we are updating
    $customerDetailIdToIgnore = $loanApplication?->customer?->details?->id;
    // Get the Customer ID to ignore, if we are updating
    $customerIdToIgnore = $loanApplication?->customer?->id;
    // Get the Company ID to ignore, if we are updating
    $companyIdToIgnore = $loanApplication?->customer?->company?->id; // Assuming relationship path is correct


    $rules = [
      // Loan Details (Optional at creation)
      'details.amount' => $this->isMethod('POST') ? 'required|numeric|min:0' : 'sometimes|nullable|numeric|min:0',
      'details.term' => $this->isMethod('POST') ? 'required|integer|min:0' : 'sometimes|nullable|integer|min:0',
      'details.rate' => $this->isMethod('POST') ? 'required|numeric|min:0|max:100' : 'sometimes|nullable|numeric|min:0|max:100',
      'details.frequency' => $this->isMethod('POST') ? 'required|in:weekly,biweekly,monthly' : 'sometimes|nullable|in:weekly,biweekly,monthly',
      'details.purpose' => 'sometimes|nullable|string|max:1000',
      'details.quota' => 'sometimes|nullable|numeric|min:0',
      'details.customer_comment' => 'sometimes|nullable|string|max:1000',

      // Customer Core Info
      'customer.NID' => [
        $this->isMethod('POST') ? 'required' : 'sometimes',
        'string',
        'min:11',
        'max:50',
        // Use the customer ID from the route model binding for ignoring
        Rule::unique('customers', 'NID')->ignore($customerIdToIgnore)
      ],
      'customer.details.first_name' => 'required|string|max:100',
      'customer.details.last_name' => 'required|string|max:100',
      'customer.details.birthday' => 'sometimes|date',
      'customer.details.email' => [
        'required',
        'email',
        'max:255',
        // Use the customer detail ID from the route model binding for ignoring
        Rule::unique('customer_details', 'email')->ignore($customerDetailIdToIgnore)
      ],
      'customer.details.marital_status' => [$this->isMethod('POST') ? 'required' : 'sometimes', 'in:single,married,divorced,widowed,other'],
      'customer.details.nationality' => 'sometimes|string|max:100',
      'customer.details.housing_possession_type' => [$this->isMethod('POST') ? 'required' : 'sometimes', 'in:owned,rented,mortgaged,other'],

      // Customer Optional Info
      'customer.details.gender' => 'sometimes|nullable|in:male,female',
      'customer.details.education_level' => 'sometimes|nullable|in:primary,secondary,high_school,bachelor,postgraduate,master,doctorate,other',
      'customer.details.move_in_date' => 'sometimes|nullable|date',
      'customer.details.mode_of_transport' => [$this->isMethod('POST') ? 'required' : 'sometimes', 'in:public_transportation,own_car,own_motorcycle,bicycle,other'],

      // Customer Phones (Required: at least one mobile)
      'customer.details.phones' => [
        'required',
        'array',
        'min:1',
        function ($attribute, $value, $fail) {
          $hasMobile = collect($value)->contains(function ($phone) {
            return isset($phone['type']) && $phone['type'] === 'mobile' && !empty($phone['number']);
          });
          if (!$hasMobile) {
            $fail(__('validation.custom.customer.details.phones.mobile_required'));
          }
        },
      ],
      'customer.details.phones.*.number' => 'required_if:customer.details.phones.*.type,mobile|nullable|string|max:20',
      'customer.details.phones.*.type' => 'required|in:mobile,home',

      // Customer Addresses (Required: at least one primary)
      'customer.details.addresses' => [
        'required',
        'array',
        'min:1',
        function ($attribute, $value, $fail) {
          $hasHome = collect($value)->contains(function ($address) {
            return isset($address['type']) && $address['type'] === 'home' && !empty($address['street']);
          });
          if (!$hasHome) {
            $fail(__('validation.custom.customer.details.addresses.home_required'));
          }
        },
      ],
      'customer.details.addresses.*.street' => 'required|string|max:255',
      'customer.details.addresses.*.street2' => 'sometimes|nullable|string|max:255',
      'customer.details.addresses.*.city' => 'sometimes|nullable|string|max:100',
      'customer.details.addresses.*.state' => 'sometimes|nullable|string|max:100',
      'customer.details.addresses.*.type' => 'sometimes|in:home,work,billing,shipping',

      // Vehicle (Optional)
      'customer.vehicle.vehicle_possession_type' => 'sometimes|nullable|required_with:customer.vehicle.vehicle_brand|in:owned,rented,financed,shared,leased,borrowed,none,other',
      'customer.vehicle.vehicle_brand' => 'sometimes|nullable|string|max:100',
      'customer.vehicle.vehicle_model' => 'sometimes|nullable|string|max:100',
      'customer.vehicle.vehicle_year' => [
        'sometimes',
        'nullable',
        'integer',
        'min:1900',
        'max:' . (date('Y') + 1)
      ],

      // Job Information
      'customer.jobInfo.is_self_employed' => 'required|boolean',
      'customer.company.name' => 'required|string|max:255',
      'customer.company.email' => [
        'sometimes',
        'nullable',
        'email',
        'max:255',
        // Use the company ID from the route model binding for ignoring
        Rule::unique('companies', 'email')->ignore($companyIdToIgnore)
      ],
      'customer.company.phones' => 'sometimes|array',
      'customer.company.phones.*.number' => 'required|string|max:20',
      'customer.company.phones.*.type' => 'required|string|in:work,mobile,fax,other',
      'customer.company.addresses' => 'required|array|min:1',
      'customer.company.addresses.*.street' => 'required|string|max:255',
      'customer.company.addresses.*.street2' => 'sometimes|nullable|string|max:255',
      'customer.company.addresses.*.city' => 'sometimes|string|max:100',
      'customer.company.addresses.*.state' => 'sometimes|string|max:100',
      'customer.company.addresses.*.type' => 'required|in:home,work,billing,shipping',
      'customer.jobInfo.role' => 'required|string|max:100',
      'customer.jobInfo.start_date' => 'required|date',
      'customer.jobInfo.salary' => 'required|numeric|min:0',
      'customer.jobInfo.payment_type' => 'sometimes|nullable|in:cash,bank_transfer',
      'customer.jobInfo.payment_frequency' => 'sometimes|nullable|in:weekly,biweekly,monthly',
      'customer.jobInfo.payment_bank' => 'sometimes|nullable|string|max:255',
      'customer.jobInfo.other_incomes' => 'sometimes|nullable|numeric|min:0',
      'customer.jobInfo.other_incomes_source' => 'sometimes|nullable|required_with:customer.jobInfo.other_incomes|string|max:255',
      'customer.jobInfo.schedule' => 'sometimes|nullable|string|max:255',
      'customer.jobInfo.level' => 'sometimes|nullable|string|max:255',
      'customer.jobInfo.supervisor_name' => 'sometimes|nullable|string|max:255',

      // References
      'customer.references' => 'required|array|min:1',
      'customer.references.*.name' => 'required|string|max:255',
      'customer.references.*.relationship' => 'required|string|max:255',
      'customer.references.*.occupation' => 'sometimes|nullable|string|max:255',
      // 'customer.references.*.phone_number' => 'sometimes|nullable|string|max:20', // Removed old rule
      'customer.references.*.phones' => 'required|array|min:1', // Require phones array
      'customer.references.*.phones.*.number' => 'required|string|max:20', // Require number within phones
      'customer.references.*.phones.*.type' => 'required|in:mobile,home', // Require type within phones
      'terms' => ['accepted'], // Renamed from 'acceptance' to match form field name
    ];

    // Add specific rules for update method
    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      // Ensure the loan application exists if we are updating
      if (!$loanApplication) {
        // Handle case where loan application is not found, maybe throw an exception or add a general error
        // For now, let's assume route model binding handles this.
      }
      $rules['customer.references.*.id'] = 'sometimes|integer|exists:customer_references,id';
      // Remove terms requirement for update
      unset($rules['terms']);
    }


    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'customer.NID.unique' => __('validation.unique', ['attribute' => __('NID')]),
      'customer.details.email.unique' => __('validation.unique', ['attribute' => __('Email')]),
      'customer.details.phones.mobile_required' => __('A mobile phone number is required.'),
      'customer.details.addresses.home_required' => __('A home address is required.'),
      'terms.accepted' => __('You must accept the terms and conditions.'), // Changed from acceptance.accepted
      // 'customer.references.*.id.required' => __('Reference ID is required for updates.'), // Removed as it's now sometimes
      'customer.references.*.id.exists' => __('Invalid reference ID provided.'),
    ];
  }
}
