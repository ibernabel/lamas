<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Eager load relationships if they haven't been loaded yet
        $this->resource->loadMissing([
            'details.phones',
            'details.addresses',
            'company.phones',
            'company.addresses',
            'jobInfo',
            'vehicle',
            'references.phones',
            'portfolio.broker.user:id,name', // Example of nested relationship
        ]);

        return [
            'id' => $this->id,
            'nid' => $this->NID,
            'leadChannel' => $this->lead_channel,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'details' => $this->whenLoaded('details', function () {
                return [
                    'id' => $this->details->id,
                    'firstName' => $this->details->first_name,
                    'lastName' => $this->details->last_name,
                    'email' => $this->details->email,
                    'birthday' => $this->details->birthday,
                    'maritalStatus' => $this->details->marital_status,
                    'nationality' => $this->details->nationality,
                    'housingType' => $this->details->housing_type,
                    'gender' => $this->details->gender,
                    'educationLevel' => $this->details->education_level,
                    'moveInDate' => $this->details->move_in_date,
                    'phones' => $this->details->phones->map(function ($phone) {
                        return [
                            'id' => $phone->id,
                            'number' => $phone->number,
                            'type' => $phone->type,
                        ];
                    }),
                    'addresses' => $this->details->addresses->map(function ($address) {
                        return [
                            'id' => $address->id,
                            'street' => $address->street,
                            'street2' => $address->street2,
                            'city' => $address->city,
                            'state' => $address->state,
                            'type' => $address->type,
                        ];
                    }),
                ];
            }),
            'vehicle' => $this->whenLoaded('vehicle', function () {
                return [
                    'id' => $this->vehicle->id,
                    'vehicleType' => $this->vehicle->vehicle_type,
                    'vehicleBrand' => $this->vehicle->vehicle_brand,
                    'vehicleModel' => $this->vehicle->vehicle_model,
                    'vehicleYear' => $this->vehicle->vehicle_year,
                ];
            }),
            'company' => $this->whenLoaded('company', function () {
                return [
                    'id' => $this->company->id,
                    'name' => $this->company->name,
                    'email' => $this->company->email,
                    'phones' => $this->company->phones->map(function ($phone) {
                        return [
                            'id' => $phone->id,
                            'number' => $phone->number,
                            'type' => $phone->type,
                        ];
                    }),
                    'addresses' => $this->company->addresses->map(function ($address) {
                        return [
                            'id' => $address->id,
                            'street' => $address->street,
                            'street2' => $address->street2,
                            'city' => $address->city,
                            'state' => $address->state,
                            'type' => $address->type,
                        ];
                    }),
                ];
            }),
            'jobInfo' => $this->whenLoaded('jobInfo', function () {
                return [
                    'id' => $this->jobInfo->id,
                    'isSelfEmployed' => $this->jobInfo->is_self_employed,
                    'role' => $this->jobInfo->role,
                    'startDate' => $this->jobInfo->start_date,
                    'salary' => $this->jobInfo->salary,
                    'paymentType' => $this->jobInfo->payment_type,
                    'paymentFrequency' => $this->jobInfo->payment_frequency,
                    'paymentBank' => $this->jobInfo->payment_bank,
                    'otherIncomes' => $this->jobInfo->other_incomes,
                    'otherIncomesSource' => $this->jobInfo->other_incomes_source,
                    'schedule' => $this->jobInfo->schedule,
                    'level' => $this->jobInfo->level,
                    'supervisorName' => $this->jobInfo->supervisor_name,
                ];
            }),
            'references' => $this->whenLoaded('references', function () {
                return $this->references->map(function ($reference) {
                    return [
                        'id' => $reference->id,
                        'name' => $reference->name,
                        'relationship' => $reference->relationship,
                        'occupation' => $reference->occupation,
                        'phones' => $reference->phones->map(function ($phone) {
                            return [
                                'id' => $phone->id,
                                'number' => $phone->number,
                                'type' => $phone->type,
                            ];
                        }),
                    ];
                });
            }),
            'portfolio' => $this->whenLoaded('portfolio', function () {
                return [
                    'id' => $this->portfolio->id,
                    'name' => $this->portfolio->name,
                    'broker' => $this->whenLoaded('portfolio.broker', function () {
                        return [
                            'id' => $this->portfolio->broker->id,
                            'name' => $this->portfolio->broker->user->name ?? null, // Access user name via broker
                        ];
                    }),
                ];
            }),
            // Add other relationships as needed
        ];
    }
}
