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
      'created_at' => $this->created_at ? $this->created_at->toISOString() : null, // Format date as ISO 8601 or null
      'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,

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
          'customer_comment' => $this->details->customer_comment,
          'loan_application_id' => $this->details->loan_application_id,
          'created_at' => $this->details->created_at ? $this->details->created_at->toISOString() : null,
          'updated_at' => $this->details->updated_at ? $this->details->updated_at->toISOString() : null,
          // Include any other fields from the details relationship as needed
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
          'lead_channel' => $this->customer->lead_channel,
          'is_referred' => $this->customer->is_referred,
          'referred_by' => $this->customer->referred_by,
          'is_active' => $this->customer->is_active,
          'portfolio_id' => $this->customer->portfolio_id,
          'promoter_id' => $this->customer->promoter_id,
          'is_assigned' => $this->customer->is_assigned,
          'assigned_at' => $this->customer->assigned_at,
          'created_at' => $this->customer->created_at ? $this->customer->created_at->toISOString() : null,
          'updated_at' => $this->customer->updated_at ? $this->customer->updated_at->toISOString() : null,
          // Include other customer fields as needed
          // The controller already eager-loads 'customer.details',
          // so we just need to check if it's not null before accessing properties.
          'details' => $this->customer->details ? [
            'id' => $this->customer->details->id,
            'customer_id' => $this->customer->details->customer_id,
            'first_name' => $this->customer->details->first_name,
            'last_name' => $this->customer->details->last_name,
            'email' => $this->customer->details->email,
            'nickname' => $this->customer->details->nickname,
            'birthday' => $this->customer->details->birthday,
            'gender' => $this->customer->details->gender,
            'marital_status' => $this->customer->details->marital_status,
            'education_level' => $this->customer->details->education_level,
            'nationality' => $this->customer->details->nationality,
            'housing_type' => $this->customer->details->housing_type,
            'move_in_date' => $this->customer->details->move_in_date,
            'phones' => $this->customer->details->phones->isNotEmpty() ?  $this->customer->details->phones->map(fn($phone) => [
              'id' => $phone->id,
              'number' => $phone->number,
              'type' => $phone->type,
            ]) : [],
            'addresses' => $this->customer->details->addresses->isNotEmpty() ? $this->customer->details->addresses->map(fn($address) => [
              'id' => $address->id,
              'street' => $address->street,
              'city' => $address->city,
              'state' => $address->state,
              'zip_code' => $address->zip_code,
              'country' => $address->country,
              'type' => $address->type,
            ]) : [],
          ] : null, // Return null if details relationship is empty
          'company' => $this->customer->company ? [
            'id' => $this->customer->company->id,
            'name' => $this->customer->company->name,
            'email' => $this->customer->company->email,
            'type' => $this->customer->company->type,
            'rnc' => $this->customer->company->rnc,
            'website' => $this->customer->company->website,
            'departmet' => $this->customer->company->department,
            'branch' => $this->customer->company->branch,
            'phones' => $this->customer->company->phones->isNotEmpty() ? $this->customer->company->phones->map(fn($phone) => [
              'id' => $phone->id,
              'number' => $phone->number,
              'type' => $phone->type,
            ]) : null,
            'addresses' => $this->customer->company->addresses->isNotEmpty() ? $this->customer->company->addresses->map(fn($address) => [
              'id' => $address->id,
              'street' => $address->street,
              'city' => $address->city,
              'state' => $address->state,
              'zip_code' => $address->zip_code,
              'country' => $address->country,
              'type' => $address->type,
            ]) : [],
          ] : null,
          'job_info' => $this->customer->jobInfo ? [
            'id' => $this->customer->jobInfo->id,
            'role' => $this->customer->jobInfo->role,
            'level' => $this->customer->jobInfo->level,
            'start_date' => $this->customer->jobInfo->start_date,
            'salary' => $this->customer->jobInfo->salary,
            'other_incomes' => (float) $this->customer->jobInfo->other_incomes,
            'other_incomes_source' => $this->customer->jobInfo->other_incomes_source,
            'payment_type' => $this->customer->jobInfo->payment_type,
            'payment_frequency' => $this->customer->jobInfo->payment_frequency,
            'payment_bank' => $this->customer->jobInfo->payment_bank,
            'payment_account_number' => $this->customer->jobInfo->payment_account_number,
            'schedule' => $this->customer->jobInfo->schedule,
            'supervisor_name' => $this->customer->jobInfo->supervisor_name,
            'is_self_employed' => $this->customer->jobInfo->is_self_employed,
            'customer_id' => $this->customer->jobInfo->customer_id,
          ] : null,
          'vehicle' => $this->customer->vehicle ? [
            'id' => $this->customer->vehicle->id,
            'vehicle_brand' => $this->customer->vehicle->vehicle_brand,
            'vehicle_model' => $this->customer->vehicle->vehicle_model,
            'vehicle_year' => $this->customer->vehicle->vehicle_year,
            'vehicle_color' => $this->customer->vehicle->vehicle_color,
            'vehicle_type' => $this->customer->vehicle->vehicle_type,
            'vehicle_plate_number' => $this->customer->vehicle->vehicle_plate_number,
            'is_financed' => $this->customer->vehicle->is_financed,
            'is_owned' => $this->customer->vehicle->is_owned,
            'is_leased' => $this->customer->vehicle->is_leased,
            'is_rented' => $this->customer->vehicle->is_rented,
            'is_shared' => $this->customer->vehicle->is_shared,
          ] : null,
          'references' => $this->customer->references->isNotEmpty() ? $this->customer->references->map(fn($ref) => [ // Check if references collection is loaded and not empty
            'id' => $ref->id,
            'name' => $ref->name,
            'phone_number' => $ref->phone_number,
            'reference_email' => $ref->reference_email,
            'relationship' => $ref->relationship,
            'occupation' => $ref->occupation,
            'reference_since' => $ref->reference_since,
            'is_who_referred' => $ref->is_who_referred,
          ]) : [] // Return empty array if no references
          ,
          // Add other customer relationships like financialInfo, portfolio etc. when needed
        ];
      }),

      'risks' => $this->whenLoaded('risks', function () {
        // If RiskResource exists, use it:
        // return RiskResource::collection($this->risks);
        return $this->risks->map(fn($risk) => [
          'id' => $risk->id,
          'name' => $risk->name,
          'description' => $risk->description,
          // Include pivot data if necessary: $risk->pivot->...
        ]);
      }),

      'notes' => $this->whenLoaded('notes', function () {
        // If NoteResource exists, use it:
        // return NoteResource::collection($this->notes);
        return $this->notes->map(fn($note) => [
          'id' => $note->id,
          'content' => $note->note,
          'user_id' => $note->user_id, // Consider adding user name if needed
          'loan_application_id' => $note->loan_application_id,
          'created_at' => $note->created_at ? $note->created_at->toISOString() : null,
          'updated_at' => $note->updated_at ? $note->updated_at->toISOString() : null,
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
        'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
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
