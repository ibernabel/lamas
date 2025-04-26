<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest; // Use CustomerRequest for validation
use App\Http\Resources\V1\CustomerResource; // Use CustomerResource for responses
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 15); // Default to 15 items per page

            $customers = Customer::with([
                'details:id,customer_id,first_name,last_name,email', // Basic details for list view
            ])
                ->select(['id', 'NID', 'created_at']) // Select specific columns
                ->latest()
                ->paginate($perPage);

            return CustomerResource::collection($customers);
        } catch (\Exception $e) {
            Log::error('API Customer Index Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve customers.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        Log::info('API Customer Store Request Received', ['request_data' => $request->validated()]); // Log validated data

        try {
            $validatedData = $request->validated(); // Already validated by CustomerRequest

            $customer = DB::transaction(function () use ($validatedData) {
                // Extract customer core data and details data
                $customerData = $validatedData['customer'];
                $detailsData = $customerData['details'];
                $phonesData = $detailsData['phones'] ?? [];
                $addressesData = $detailsData['addresses'] ?? [];
                $companyData = $customerData['company'] ?? null;
                $jobInfoData = $customerData['jobInfo'] ?? null;
                $vehicleData = $customerData['vehicle'] ?? null;
                $referencesData = $customerData['references'] ?? [];

                // 1. Create Customer
                $customer = Customer::create([
                    'NID' => $customerData['NID'],
                    'lead_channel' => 'api', // Indicate source
                ]);
                Log::info('API Customer Created', ['customer_id' => $customer->id]);

                // 2. Create Customer Details (remove phones and addresses before creating)
                unset($detailsData['phones'], $detailsData['addresses']);
                $customerDetail = $customer->details()->create($detailsData);
                Log::info('API Customer Details Created', ['customer_detail_id' => $customerDetail->id]);

                // 3. Create Customer Phones
                foreach ($phonesData as $phoneData) {
                    if (!empty($phoneData['number'])) {
                        $customerDetail->phones()->create($phoneData);
                    }
                }
                Log::info('API Customer Phones Created');

                // 4. Create Customer Addresses
                foreach ($addressesData as $addressData) {
                    $customerDetail->addresses()->create($addressData);
                }
                Log::info('API Customer Addresses Created');

                // 5. Create Company (if provided)
                if ($companyData) {
                    $companyPhones = $companyData['phones'] ?? [];
                    $companyAddresses = $companyData['addresses'] ?? [];
                    unset($companyData['phones'], $companyData['addresses']); // Remove nested arrays before create

                    $company = $customer->company()->create($companyData);
                    Log::info('API Company Created', ['company_id' => $company->id]);

                    // 6. Create Company Addresses
                    foreach ($companyAddresses as $addressData) {
                        $addressData['type'] = $addressData['type'] ?? 'work'; // Default type if needed
                        $company->addresses()->create($addressData);
                    }
                    Log::info('API Company Addresses Created');

                    // 7. Create Company Phones
                    foreach ($companyPhones as $phoneData) {
                        if (!empty($phoneData['number'])) {
                            $company->phones()->create($phoneData);
                        }
                    }
                    Log::info('API Company Phones Created');
                }

                // 8. Create Job Info (if provided)
                if ($jobInfoData) {
                    $customer->jobInfo()->create($jobInfoData);
                    Log::info('API Customer Job Info Created');
                }

                // 9. Create Vehicle Info (Conditional, if brand exists)
                if ($vehicleData && !empty($vehicleData['vehicle_brand'])) {
                    $customer->vehicle()->create($vehicleData);
                    Log::info('API Customer Vehicle Created');
                }

                // 10. Create References
                foreach ($referencesData as $referenceData) {
                    $customer->references()->create($referenceData);
                }
                Log::info('API Customer References Created');

                return $customer;
            });

            Log::info('API Customer Transaction Committed Successfully');

            // Load relationships needed for the resource response
            $customer->load([
                'details.phones',
                'details.addresses',
                'company.phones',
                'company.addresses',
                'jobInfo',
                'vehicle',
                'references',
            ]);

            return (new CustomerResource($customer))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            // This shouldn't normally be hit if CustomerRequest is used, but good practice
            Log::warning('API Customer Store Validation Failed (Controller Catch)', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation Failed', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            DB::rollBack(); // Ensure rollback if transaction failed
            Log::error('API Customer creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to create customer.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) // Use string ID for flexibility (UUIDs etc.)
    {
        try {
            $customer = Customer::with([
                'details.phones',
                'details.addresses',
                'company.phones',
                'company.addresses',
                'jobInfo',
                'vehicle',
                'references',
                'portfolio.broker.user:id,name', // Include portfolio/broker if needed
                // Add any other relationships you want to show
            ])->findOrFail($id); // Use findOrFail to handle not found automatically

            return new CustomerResource($customer);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('API Customer Show Not Found', ['id' => $id]);
            return response()->json(['message' => 'Customer not found.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('API Customer Show Error: ' . $e->getMessage(), ['id' => $id]);
            return response()->json(['message' => 'Failed to retrieve customer data.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        Log::info('API Customer Update Request Received', ['id' => $id, 'request_data' => $request->validated()]);

        try {
            $customer = Customer::findOrFail($id); // Find the customer first
            $validatedData = $request->validated(); // Get validated data

            $updatedCustomer = DB::transaction(function () use ($validatedData, $customer) {
                // Extract data sections
                $customerData = $validatedData['customer'];
                $detailsData = $customerData['details'];
                $phonesData = $detailsData['phones'] ?? [];
                $addressesData = $detailsData['addresses'] ?? [];
                $companyData = $customerData['company'] ?? null;
                $jobInfoData = $customerData['jobInfo'] ?? null;
                $vehicleData = $customerData['vehicle'] ?? null;
                $referencesData = $customerData['references'] ?? [];

                // 1. Update Customer Basic Info
                $customer->update(['NID' => $customerData['NID']]);
                Log::info('API Customer Basic Info Updated', ['customer_id' => $customer->id]);

                // 2. Update Customer Details
                $customerDetails = $customer->details;
                if ($customerDetails) {
                    unset($detailsData['phones'], $detailsData['addresses']); // Don't update relations directly
                    $customerDetails->update($detailsData);
                    Log::info('API Customer Details Updated', ['customer_detail_id' => $customerDetails->id]);

                    // Update Phones (Replace existing)
                    $customerDetails->phones()->delete();
                    foreach ($phonesData as $phoneData) {
                        if (!empty($phoneData['number'])) {
                            $customerDetails->phones()->create($phoneData);
                        }
                    }
                    Log::info('API Customer Phones Updated');

                    // Update Addresses (Replace existing)
                    $customerDetails->addresses()->delete();
                    foreach ($addressesData as $addressData) {
                        $customerDetails->addresses()->create($addressData);
                    }
                    Log::info('API Customer Addresses Updated');
                } else {
                    // Should not happen if customer exists, but handle defensively
                    Log::warning('API Customer Update: Customer details missing, creating.', ['customer_id' => $customer->id]);
                    // Create if somehow missing (logic similar to store)
                }

                // 3. Update Company
                if ($companyData) {
                    $companyPhones = $companyData['phones'] ?? [];
                    $companyAddresses = $companyData['addresses'] ?? [];
                    unset($companyData['phones'], $companyData['addresses']);

                    $company = $customer->company()->updateOrCreate(
                        ['customer_id' => $customer->id], // Find by customer_id
                        $companyData // Data to update or create with
                    );
                    Log::info('API Company Updated/Created', ['company_id' => $company->id]);

                    // Update Company Phones (Replace existing)
                    $company->phones()->delete();
                    foreach ($companyPhones as $phoneData) {
                        if (!empty($phoneData['number'])) {
                            $company->phones()->create($phoneData);
                        }
                    }
                    Log::info('API Company Phones Updated');

                    // Update Company Addresses (Replace existing)
                    $company->addresses()->delete();
                    foreach ($companyAddresses as $addressData) {
                        $addressData['type'] = $addressData['type'] ?? 'work';
                        $company->addresses()->create($addressData);
                    }
                    Log::info('API Company Addresses Updated');
                } else {
                    // If company data is null/missing, delete existing company? Optional.
                    // $customer->company()->delete();
                    // Log::info('API Deleted company as data was missing in update request', ['customer_id' => $customer->id]);
                }

                // 4. Update Job Info
                if ($jobInfoData) {
                    $customer->jobInfo()->updateOrCreate(
                        ['customer_id' => $customer->id],
                        $jobInfoData
                    );
                    Log::info('API Customer Job Info Updated/Created');
                } else {
                    // $customer->jobInfo()->delete(); // Optional: delete if missing
                }

                // 5. Update Vehicle Info
                if ($vehicleData) {
                    if (!empty($vehicleData['vehicle_brand'])) {
                        $customer->vehicle()->updateOrCreate(
                            ['customer_id' => $customer->id],
                            $vehicleData
                        );
                        Log::info('API Customer Vehicle Updated/Created');
                    } else {
                        // If brand is empty, delete existing vehicle info
                        $customer->vehicle()->delete();
                        Log::info('API Customer Vehicle Deleted due to empty data');
                    }
                } else {
                     // $customer->vehicle()->delete(); // Optional: delete if missing
                }

                // 6. Update References (Handle Create/Update/Delete)
                $existingReferenceIds = $customer->references()->pluck('id')->toArray();
                $incomingReferenceIds = collect($referencesData)->pluck('id')->filter()->toArray();

                // Delete references not present in the incoming data
                $idsToDelete = array_diff($existingReferenceIds, $incomingReferenceIds);
                if (!empty($idsToDelete)) {
                    $customer->references()->whereIn('id', $idsToDelete)->delete();
                    Log::info('API Deleted old references', ['ids' => $idsToDelete]);
                }

                // Update or Create references
                foreach ($referencesData as $referenceData) {
                    $referenceId = $referenceData['id'] ?? null;
                    // Prepare data, removing 'id' if it's null for creation
                    $updateData = [
                        'name' => $referenceData['name'],
                        'relationship' => $referenceData['relationship'],
                        'occupation' => $referenceData['occupation'] ?? null,
                        'phone_number' => $referenceData['phone_number'] ?? null,
                    ];
                    $customer->references()->updateOrCreate(
                        ['id' => $referenceId, 'customer_id' => $customer->id], // Match by id if exists, always ensure customer_id
                        $updateData
                    );
                }
                Log::info('API Customer References Updated/Created');


                // Reload the customer to get fresh data after updates
                $customer->refresh();
                return $customer;
            });

            Log::info('API Customer Transaction Committed Successfully for Update');

            // Load relationships needed for the resource response
            $updatedCustomer->load([
                'details.phones',
                'details.addresses',
                'company.phones',
                'company.addresses',
                'jobInfo',
                'vehicle',
                'references',
            ]);

            return new CustomerResource($updatedCustomer);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('API Customer Update Not Found', ['id' => $id]);
            return response()->json(['message' => 'Customer not found.'], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            // Should not be hit if CustomerRequest is used
            Log::warning('API Customer Update Validation Failed (Controller Catch)', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation Failed', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            DB::rollBack(); // Ensure rollback
            Log::error('API Customer update failed: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to update customer.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Optional: Add policy check here if needed
            // $this->authorize('delete', $customer);

            // Optional: Check if customer can be deleted (e.g., no active loans)
            // if ($customer->loanApplications()->whereNotIn('status', ['completed', 'rejected'])->exists()) {
            //     Log::warning('API Customer Deletion Denied - Active Loans', ['id' => $id]);
            //     return response()->json([
            //         'message' => 'Cannot delete customer with active loan applications.'
            //     ], Response::HTTP_FORBIDDEN);
            // }

            DB::transaction(function () use ($customer) {
                // Consider deleting related data or rely on cascading deletes/soft deletes
                // Example: Soft delete related models if they use SoftDeletes trait
                $customer->details()->delete(); // Assuming soft deletes
                $customer->company()->delete(); // Assuming soft deletes
                $customer->jobInfo()->delete(); // Assuming soft deletes
                $customer->vehicle()->delete(); // Assuming soft deletes
                $customer->references()->delete(); // Assuming soft deletes
                // Phones and Addresses are usually deleted via cascade or through their parent (details/company)

                // Finally, soft delete the customer
                $customer->delete();
            });


            Log::info("API Customer {$id} soft deleted.");

            return response()->json(null, Response::HTTP_NO_CONTENT); // Standard response for successful deletion

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('API Customer Delete Not Found', ['id' => $id]);
            return response()->json(['message' => 'Customer not found.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            DB::rollBack(); // Ensure rollback if transaction failed during deletion
            Log::error('API Customer deletion failed: ' . $e->getMessage(), ['id' => $id]);
            return response()->json(['message' => 'Failed to delete customer.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Check if a customer NID exists.
     *
     * @param  string  $nid
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNidExists(string $nid): JsonResponse
    {
        // Validate the NID: must be exactly 11 digits
        $validator = Validator::make(['nid' => $nid], [
            'nid' => ['required', 'string', 'regex:/^[0-9]{11}$/'],
        ],
        [
            'nid.required' => 'El NID es requerido.',
            'nid.string'   => 'El NID debe ser una cadena de texto.',
            'nid.regex'    => 'El NID debe contener exactamente 11 dígitos numéricos.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exists' => false, // Indicate non-existence due to invalid format
                'message' => 'Formato de NID inválido.',
                'errors' => $validator->errors()
            ], 400); // Bad Request
        }

        // Check if customer exists using the validated NID
        $customerExists = Customer::where('nid', $nid)->exists();

        if ($customerExists) {
            $customer = Customer::where('nid', $nid)->first();
        }

        return response()->json([
            'exists' => $customerExists,
            'message' => $customerExists ? __('The client\'s NID was found.') : __('The client\'s NID was not found.'),
            'customer' => $customerExists ? [
                'NID' => $customer->NID,
                'id' => $customer->id,
                'details' => $customer->details ? [
                    'first_name' => $customer->details->first_name,
                    'last_name' => $customer->details->last_name,
                    'email' => $customer->details->email,
                ] : null,
            ] : null,
        ]);
    }
}
