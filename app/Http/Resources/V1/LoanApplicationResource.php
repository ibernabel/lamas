<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// Assuming other resources might be created later, e.g.:
// use App\Http\Resources\V1\CustomerResource;
// use App\Http\Resources\V1\LoanApplicationDetailResource;
// use App\Http\Resources\V1\RiskResource;
// use App\Http\Resources\V1\NoteResource;

class LoanApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Base structure for the loan application
        $data = [
            'id' => $this->id,
            'status' => $this->status,
            'status_display' => __($this->status), // Include translated status
            'created_at' => $this->created_at->toISOString(), // Format date as ISO 8601
            'updated_at' => $this->updated_at->toISOString(),

            // Conditionally load relationships if they are present on the model instance
            'details' => $this->whenLoaded('details', function () {
                // If LoanApplicationDetailResource exists, use it:
                // return new LoanApplicationDetailResource($this->details);
                // Otherwise, return the data directly (adjust fields as needed):
                return [
                    'id' => $this->details->id,
                    'amount' => (float) $this->details->amount,
                    'term' => (int) $this->details->term,
                    'rate' => (float) $this->details->rate,
                    'frequency' => $this->details->frequency,
                    'purpose' => $this->details->purpose,
                    'collateral_description' => $this->details->collateral_description,
                    // Add other detail fields as necessary
                ];
            }),

            'customer' => $this->whenLoaded('customer', function () {
                // If CustomerResource exists, use it:
                // return new CustomerResource($this->customer);
                // Otherwise, return selected customer data:
                return [
                    'id' => $this->customer->id,
                    'nid' => $this->customer->NID,
                    'details' => $this->whenLoaded('customer.details', function () {
                        return [
                            'id' => $this->customer->details->id,
                            'first_name' => $this->customer->details->first_name,
                            'last_name' => $this->customer->details->last_name,
                            'email' => $this->customer->details->email,
                            'birth_date' => $this->customer->details->birth_date,
                            'marital_status' => $this->customer->details->marital_status,
                            'gender' => $this->customer->details->gender,
                            'nationality' => $this->customer->details->nationality,
                            'dependents' => $this->customer->details->dependents,
                            'education_level' => $this->customer->details->education_level,
                            'residence_type' => $this->customer->details->residence_type,
                            'residence_time_years' => $this->customer->details->residence_time_years,
                            'phones' => $this->whenLoaded('customer.details.phones', function () {
                                return $this->customer->details->phones->map(fn ($phone) => [
                                    'id' => $phone->id,
                                    'number' => $phone->number,
                                    'type' => $phone->type,
                                ]);
                            }),
                            'addresses' => $this->whenLoaded('customer.details.addresses', function () {
                                return $this->customer->details->addresses->map(fn ($address) => [
                                    'id' => $address->id,
                                    'street' => $address->street,
                                    'city' => $address->city,
                                    'state' => $address->state,
                                    'zip_code' => $address->zip_code,
                                    'country' => $address->country,
                                    'type' => $address->type,
                                ]);
                            }),
                        ];
                    }),
                    'company' => $this->whenLoaded('customer.company', function () {
                        return [
                            'id' => $this->customer->company->id,
                            'name' => $this->customer->company->name,
                            'tax_id' => $this->customer->company->tax_id,
                            'industry' => $this->customer->company->industry,
                            'years_in_business' => $this->customer->company->years_in_business,
                            'phones' => $this->whenLoaded('customer.company.phones', function () {
                                return $this->customer->company->phones->map(fn ($phone) => [
                                    'id' => $phone->id,
                                    'number' => $phone->number,
                                    'type' => $phone->type,
                                ]);
                            }),
                            'addresses' => $this->whenLoaded('customer.company.addresses', function () {
                                return $this->customer->company->addresses->map(fn ($address) => [
                                    'id' => $address->id,
                                    'street' => $address->street,
                                    'city' => $address->city,
                                    'state' => $address->state,
                                    'zip_code' => $address->zip_code,
                                    'country' => $address->country,
                                    'type' => $address->type,
                                ]);
                            }),
                        ];
                    }),
                    'job_info' => $this->whenLoaded('customer.jobInfo', function () {
                        return [
                            'id' => $this->customer->jobInfo->id,
                            'employer_name' => $this->customer->jobInfo->employer_name,
                            'position' => $this->customer->jobInfo->position,
                            'employment_type' => $this->customer->jobInfo->employment_type,
                            'years_employed' => $this->customer->jobInfo->years_employed,
                            'monthly_income' => (float) $this->customer->jobInfo->monthly_income,
                            'supervisor_name' => $this->customer->jobInfo->supervisor_name,
                            'supervisor_phone' => $this->customer->jobInfo->supervisor_phone,
                        ];
                    }),
                    'vehicle' => $this->whenLoaded('customer.vehicle', function () {
                        return [
                            'id' => $this->customer->vehicle->id,
                            'vehicle_type' => $this->customer->vehicle->vehicle_type,
                            'vehicle_brand' => $this->customer->vehicle->vehicle_brand,
                            'vehicle_model' => $this->customer->vehicle->vehicle_model,
                            'vehicle_year' => $this->customer->vehicle->vehicle_year,
                            'vehicle_value' => (float) $this->customer->vehicle->vehicle_value,
                            'vehicle_ownership_status' => $this->customer->vehicle->vehicle_ownership_status,
                        ];
                    }),
                    'references' => $this->whenLoaded('customer.references', function () {
                        return $this->customer->references->map(fn ($ref) => [
                            'id' => $ref->id,
                            'name' => $ref->name,
                            'relationship' => $ref->relationship,
                            'phone_number' => $ref->phone_number,
                            'occupation' => $ref->occupation,
                        ]);
                    }),
                    // Add other customer relationships like financialInfo, portfolio etc. when needed
                ];
            }),

            'risks' => $this->whenLoaded('risks', function () {
                // If RiskResource exists, use it:
                // return RiskResource::collection($this->risks);
                return $this->risks->map(fn ($risk) => [
                    'id' => $risk->id,
                    'name' => $risk->name,
                    'description' => $risk->description,
                    // Include pivot data if necessary: $risk->pivot->...
                ]);
            }),

            'notes' => $this->whenLoaded('notes', function () {
                // If NoteResource exists, use it:
                // return NoteResource::collection($this->notes);
                return $this->notes->map(fn ($note) => [
                    'id' => $note->id,
                    'content' => $note->content,
                    'created_at' => $note->created_at->toISOString(),
                    'user_id' => $note->user_id, // Consider adding user name if needed
                ]);
            }),

            // Include user who created the application if needed and loaded
            'user' => $this->whenLoaded('user', function () {
                 return [
                     'id' => $this->user->id,
                     'name' => $this->user->name,
                 ];
            }),
        ];

        // For collection (index method), we might want a simpler structure
        if ($this->isCollection($request)) {
             return [
                 'id' => $this->id,
                 'status' => $this->status,
                 'status_display' => __($this->status),
                 'created_at' => $this->created_at->toISOString(),
                 'customer_name' => $this->whenLoaded('customer.details', fn() => $this->customer->details->first_name . ' ' . $this->customer->details->last_name),
                 'amount' => $this->whenLoaded('details', fn() => (float) $this->details?->amount), // Use optional chaining
             ];
        }

        return $data; // Return full data for single resource requests (show, store, update)
    }

    /**
     * Check if the resource is being used as part of a collection.
     * Needed because $this->collection is not available directly in toArray.
     *
     * @param Request $request
     * @return bool
     */
    protected function isCollection(Request $request): bool
    {
        // A simple heuristic: check if the request path matches a typical index route pattern
        // Adjust the pattern based on your actual API routes (e.g., '/api/v1/loan-applications')
        return $request->routeIs('*.index'); // Assumes route names like 'api.v1.loan-applications.index'
    }
}
