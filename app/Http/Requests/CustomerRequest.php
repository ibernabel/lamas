<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Customer; // Import Customer model

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Adjust authorization logic as needed, e.g., check permissions
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Get the Customer model instance from the route parameter if it exists (for updates)
        $customerId = $this->route('id'); // Assuming route parameter is 'id' for customer
        $customer = $customerId ? Customer::with(['details', 'company'])->find($customerId) : null;

        // Get IDs to ignore for unique rules during updates
        $customerDetailIdToIgnore = $customer?->details?->id;
        $customerIdToIgnore = $customer?->id;
        $companyIdToIgnore = $customer?->company?->id;

        $rules = [
            // Customer Core Info
            'customer.NID' => [
                'required',
                'string',
                'min:11',
                'max:50',
                Rule::unique('customers', 'NID')->ignore($customerIdToIgnore)
            ],
            'customer.details.first_name' => 'required|string|max:100',
            'customer.details.last_name' => 'required|string|max:100',
            'customer.details.birthday' => 'sometimes|nullable|date',
            'customer.details.email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customer_details', 'email')->ignore($customerDetailIdToIgnore)
            ],
            'customer.details.marital_status' => ['required', 'in:single,married,divorced,widowed,other'],
            'customer.details.nationality' => 'sometimes|nullable|string|max:100',
            'customer.details.housing_type' => ['required', 'in:owned,rented,mortgaged,other'],

            // Customer Optional Info
            'customer.details.gender' => 'sometimes|nullable|in:male,female',
            'customer.details.education_level' => 'sometimes|nullable|in:primary,secondary,high_school,bachelor,postgraduate,master,doctorate,other',
            'customer.details.move_in_date' => 'sometimes|nullable|date',

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

            // Customer Addresses (Required: at least one primary/home)
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
            'customer.details.addresses.*.type' => 'required|in:home,work,billing,shipping', // Made required

            // Vehicle (Optional)
            'customer.vehicle.vehicle_type' => 'sometimes|nullable|required_with:customer.vehicle.vehicle_brand|in:owned,rented,financed,shared,leased,borrowed,none,other',
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
            'customer.company.name' => 'required_if:customer.jobInfo.is_self_employed,false|nullable|string|max:255', // Required only if not self-employed
            'customer.company.email' => [
                'sometimes',
                'nullable',
                'email',
                'max:255',
                Rule::unique('companies', 'email')->ignore($companyIdToIgnore)
            ],
            'customer.company.phones' => 'sometimes|nullable|array',
            'customer.company.phones.*.number' => 'sometimes|nullable|string|max:20',
            'customer.company.phones.*.type' => 'sometimes|nullable|in:mobile,home,work', // Added work type
            'customer.company.addresses' => 'required_if:customer.jobInfo.is_self_employed,false|nullable|array|min:1', // Required only if not self-employed
            'customer.company.addresses.*.street' => 'required|string|max:255',
            'customer.company.addresses.*.street2' => 'sometimes|nullable|string|max:255',
            'customer.company.addresses.*.city' => 'sometimes|nullable|string|max:100',
            'customer.company.addresses.*.state' => 'sometimes|nullable|string|max:100',
            'customer.company.addresses.*.type' => 'required|in:home,work,billing,shipping', // Made required
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
            'customer.references.*.phone_number' => 'sometimes|nullable|string|max:20',
        ];

        // Add specific rules for update method (PUT/PATCH)
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['customer.references.*.id'] = 'sometimes|integer|exists:customer_references,id,customer_id,' . $customerIdToIgnore; // Ensure reference belongs to customer
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
            'customer.company.email.unique' => __('validation.unique', ['attribute' => __('Company Email')]),
            'customer.details.phones.mobile_required' => __('A mobile phone number is required.'),
            'customer.details.addresses.home_required' => __('A home address is required.'),
            'customer.references.*.id.exists' => __('Invalid reference ID provided.'),
            'customer.company.name.required_if' => __('Company name is required when not self-employed.'),
            'customer.company.addresses.required_if' => __('Company address is required when not self-employed.'),
        ];
    }
}
