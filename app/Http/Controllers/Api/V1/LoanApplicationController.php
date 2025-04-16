<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanApplicationRequest;
use App\Http\Resources\V1\LoanApplicationResource; // Assuming this resource exists
use App\Models\Customer;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoanApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 15); // Default to 15 items per page

            $loanApplications = LoanApplication::with([
                'details:id,loan_application_id,amount',
                'customer:id,NID', // Keep customer info minimal for list view
                'customer.details:id,customer_id,first_name,last_name', // Basic customer details
            ])
                ->select([
                    'loan_applications.id',
                    'loan_applications.status',
                    'loan_applications.customer_id',
                    'loan_applications.created_at',
                ])
                ->latest()
                ->paginate($perPage);

            return LoanApplicationResource::collection($loanApplications);
        } catch (\Exception $e) {
            Log::error('API Loan Application Index Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve loan applications.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoanApplicationRequest $request)
    {
        Log::info('API Loan Application Store Request Received', ['request_data' => $request->all()]);

        try {
            $validatedData = $request->validated();
            Log::info('API Loan Application Store Validation Passed', ['validated_data' => $validatedData]);

            $loanApplication = DB::transaction(function () use ($validatedData) {
                // 1. Create Customer
                $customer = Customer::create([
                    'NID' => $validatedData['customer']['NID'],
                    'lead_channel' => 'api', // Indicate source
                ]);
                Log::info('API Customer Created', ['customer_id' => $customer->id]);

                // 2. Create Customer Details
                $customerDetail = $customer->details()->create($validatedData['customer']['details']);
                Log::info('API Customer Details Created', ['customer_detail_id' => $customerDetail->id]);

                // 3. Create Customer Phones
                if (isset($validatedData['customer']['details']['phones'])) {
                    foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
                        if (!empty($phoneData['number'])) {
                            $customerDetail->phones()->create($phoneData);
                        }
                    }
                    Log::info('API Customer Phones Created');
                }

                // 4. Create Customer Addresses
                if (isset($validatedData['customer']['details']['addresses'])) {
                    foreach ($validatedData['customer']['details']['addresses'] as $addressData) {
                        $customerDetail->addresses()->create($addressData);
                    }
                    Log::info('API Customer Addresses Created');
                }

                // 5. Create Company
                if (isset($validatedData['customer']['company'])) {
                    $company = $customer->company()->create($validatedData['customer']['company']);
                    Log::info('API Company Created', ['company_id' => $company->id]);

                    // 6. Create Company Addresses
                    if (isset($validatedData['customer']['company']['addresses'])) {
                        foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
                            $addressData['type'] = $addressData['type'] ?? 'work';
                            $company->addresses()->create($addressData);
                        }
                        Log::info('API Company Addresses Created');
                    }

                    // 7. Create Company Phones
                    if (isset($validatedData['customer']['company']['phones'])) {
                        foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
                            if (!empty($phoneData['number'])) {
                                $company->phones()->create($phoneData);
                            }
                        }
                        Log::info('API Company Phones Created');
                    }
                }


                // 8. Create Job Info
                if (isset($validatedData['customer']['jobInfo'])) {
                    $customer->jobInfo()->create($validatedData['customer']['jobInfo']);
                    Log::info('API Customer Job Info Created');
                }


                // 9. Create Vehicle Info (Conditional)
                if (isset($validatedData['customer']['vehicle']) && !empty($validatedData['customer']['vehicle']['vehicle_brand'])) {
                    $customer->vehicle()->create($validatedData['customer']['vehicle']);
                    Log::info('API Customer Vehicle Created');
                }

                // 10. Create References
                if (isset($validatedData['customer']['references'])) {
                    foreach ($validatedData['customer']['references'] as $referenceData) {
                        $customer->references()->create($referenceData);
                    }
                    Log::info('API Customer References Created');
                }

                // 11. Create Loan Application
                $loanApplicationData = [
                    'customer_id' => $customer->id,
                    'status' => 'received', // Initial status
                    'user_id' => Auth::guard('sanctum')->id(), // Associate with the authenticated API user
                ];
                $loanApplication = LoanApplication::create($loanApplicationData);
                Log::info('API Loan Application Created', ['loan_application_id' => $loanApplication->id]);

                // 12. Create Loan Application Details (Optional)
                if (isset($validatedData['details']) && !empty(array_filter($validatedData['details']))) {
                    $detailsData = $validatedData['details'];
                    $detailsData['amount'] = $detailsData['amount'] ?? 0; // Default amount if null
                    $loanApplication->details()->create($detailsData);
                    Log::info('API Loan Application Details Created');
                }

                return $loanApplication;
            });

            Log::info('API Transaction Committed Successfully');

            // Load relationships needed for the resource response
            $loanApplication->load([
                'details',
                'customer.details.phones',
                'customer.details.addresses',
                'customer.company.phones',
                'customer.company.addresses',
                'customer.jobInfo',
                'customer.vehicle',
                'customer.references',
            ]);

            return (new LoanApplicationResource($loanApplication))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            Log::warning('API Loan Application Store Validation Failed', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation Failed', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            DB::rollBack(); // Ensure rollback if transaction didn't catch it
            Log::error('API Loan Application creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to submit loan application.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanApplication $loanApplication)
    {
        try {
            $loanApplication->load([
                'details',
                'customer.details.phones',
                'customer.details.addresses',
                'customer.vehicle',
                'customer.company.phones',
                'customer.company.addresses',
                'customer.jobInfo',
                'customer.financialInfo',
                'customer.references',
                'customer.portfolio.broker.user:id,name', // Example of loading related user info
                'risks', // Assuming risks relationship exists
                'notes'  // Assuming notes relationship exists
            ]);

            return new LoanApplicationResource($loanApplication);
        } catch (\Exception $e) {
            Log::error('API Loan Application Show Error: ' . $e->getMessage());
            return response()->json(['message' => 'Loan application not found or error loading data.'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LoanApplicationRequest $request, LoanApplication $loanApplication)
    {
        Log::info('API Loan Application Update Request Received', ['id' => $loanApplication->id, 'request_data' => $request->all()]);

        try {
            $validatedData = $request->validated();
            Log::info('API Loan Application Update Validation Passed', ['validated_data' => $validatedData]);

            $updatedLoanApplication = DB::transaction(function () use ($validatedData, $loanApplication) {
                // Update Loan Application Details
                if (isset($validatedData['details'])) {
                    $detailsData = $validatedData['details'];
                    $detailsData['amount'] = $detailsData['amount'] ?? 0;
                    $detailsData['term'] = $detailsData['term'] ?? 1;
                    $detailsData['rate'] = $detailsData['rate'] ?? 0;
                    $detailsData['frequency'] = $detailsData['frequency'] ?? 'monthly';

                    if ($loanApplication->details) {
                        $loanApplication->details->update($detailsData);
                        Log::info('API Loan Application Details Updated', ['loan_application_id' => $loanApplication->id]);
                    } else {
                        $loanApplication->details()->create($detailsData);
                        Log::info('API Loan Application Details Created', ['loan_application_id' => $loanApplication->id]);
                    }
                }

                // Update Customer related data
                if (isset($validatedData['customer'])) {
                    $customer = $loanApplication->customer;

                    // Update Customer Basic Info
                    if (isset($validatedData['customer']['NID'])) {
                        $customer->update(['NID' => $validatedData['customer']['NID']]);
                        Log::info('API Customer Basic Info Updated', ['customer_id' => $customer->id]);
                    }

                    // Update Customer Details
                    if (isset($validatedData['customer']['details'])) {
                        $customerDetails = $customer->details;
                        if ($customerDetails) {
                            $customerDetails->update($validatedData['customer']['details']);
                            Log::info('API Customer Details Updated', ['customer_detail_id' => $customerDetails->id]);

                            // Update Phones (Replace existing)
                            if (isset($validatedData['customer']['details']['phones'])) {
                                $customerDetails->phones()->delete();
                                foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
                                     if (!empty($phoneData['number'])) {
                                        $customerDetails->phones()->create($phoneData);
                                     }
                                }
                                Log::info('API Customer Phones Updated');
                            }

                            // Update Addresses (Replace existing)
                            if (isset($validatedData['customer']['details']['addresses'])) {
                                $customerDetails->addresses()->delete();
                                foreach ($validatedData['customer']['details']['addresses'] as $addressData) {
                                    $customerDetails->addresses()->create($addressData);
                                }
                                Log::info('API Customer Addresses Updated');
                            }
                        } else {
                             // Create if not exists
                             $customerDetails = $customer->details()->create($validatedData['customer']['details']);
                             Log::info('API Customer Details Created', ['customer_detail_id' => $customerDetails->id]);
                             // Handle phones/addresses creation similar to above if needed
                        }
                    }

                    // Update Company
                    if (isset($validatedData['customer']['company'])) {
                        $company = $customer->company;
                        if ($company) {
                            $company->update($validatedData['customer']['company']);
                            Log::info('API Company Updated', ['company_id' => $company->id]);

                            // Update Company Phones (Replace existing)
                            if (isset($validatedData['customer']['company']['phones'])) {
                                $company->phones()->delete();
                                foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
                                     if (!empty($phoneData['number'])) {
                                        $company->phones()->create($phoneData);
                                     }
                                }
                                Log::info('API Company Phones Updated');
                            }

                            // Update Company Addresses (Replace existing)
                            if (isset($validatedData['customer']['company']['addresses'])) {
                                $company->addresses()->delete();
                                foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
                                    $addressData['type'] = $addressData['type'] ?? 'work';
                                    $company->addresses()->create($addressData);
                                }
                                Log::info('API Company Addresses Updated');
                            }
                        } else {
                            // Create if not exists
                            $company = $customer->company()->create($validatedData['customer']['company']);
                            Log::info('API Company Created', ['company_id' => $company->id]);
                            // Handle phones/addresses creation similar to above if needed
                        }
                    }

                    // Update Job Info
                    if (isset($validatedData['customer']['jobInfo'])) {
                        $customer->jobInfo()->updateOrCreate(
                            ['customer_id' => $customer->id],
                            $validatedData['customer']['jobInfo']
                        );
                        Log::info('API Customer Job Info Updated/Created');
                    }

                    // Update Vehicle Info
                    if (isset($validatedData['customer']['vehicle'])) {
                         if (!empty($validatedData['customer']['vehicle']['vehicle_brand'])) {
                            $customer->vehicle()->updateOrCreate(
                                ['customer_id' => $customer->id],
                                $validatedData['customer']['vehicle']
                            );
                            Log::info('API Customer Vehicle Updated/Created');
                         } else {
                             // If brand is empty, potentially delete existing vehicle info
                             $customer->vehicle()->delete();
                             Log::info('API Customer Vehicle Deleted due to empty data');
                         }
                    }

                    // Update References Info (Replace existing based on ID or create new)
                    if (isset($validatedData['customer']['references'])) {
                        $existingReferenceIds = $customer->references()->pluck('id')->toArray();
                        $incomingReferenceIds = collect($validatedData['customer']['references'])->pluck('id')->filter()->toArray();

                        // Delete references not present in the incoming data
                        $idsToDelete = array_diff($existingReferenceIds, $incomingReferenceIds);
                        if (!empty($idsToDelete)) {
                            $customer->references()->whereIn('id', $idsToDelete)->delete();
                            Log::info('API Deleted old references', ['ids' => $idsToDelete]);
                        }

                        // Update or Create references
                        foreach ($validatedData['customer']['references'] as $referenceData) {
                            $referenceId = $referenceData['id'] ?? null;
                            $customer->references()->updateOrCreate(
                                ['id' => $referenceId, 'customer_id' => $customer->id], // Match by id if exists, always ensure customer_id
                                [ // Data to update or create with
                                    'name' => $referenceData['name'],
                                    'occupation' => $referenceData['occupation'] ?? null,
                                    'relationship' => $referenceData['relationship'] ?? null,
                                    'phone_number' => $referenceData['phone_number'] ?? null,
                                ]
                            );
                        }
                         Log::info('API Customer References Updated/Created');
                    } else {
                        // If no references array is sent, maybe delete all existing? Or do nothing?
                        // Current: Do nothing if 'references' key is missing. Uncomment to delete all.
                        // $customer->references()->delete();
                        // Log::info('API Deleted all customer references as key was missing');
                    }
                }

                // Reload the application to get fresh data after updates
                $loanApplication->refresh();
                return $loanApplication;
            });

            Log::info('API Transaction Committed Successfully for Update');

            // Load relationships needed for the resource response
            $updatedLoanApplication->load([
                'details',
                'customer.details.phones',
                'customer.details.addresses',
                'customer.company.phones',
                'customer.company.addresses',
                'customer.jobInfo',
                'customer.vehicle',
                'customer.references',
            ]);

            return new LoanApplicationResource($updatedLoanApplication);

        } catch (ValidationException $e) {
            Log::warning('API Loan Application Update Validation Failed', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation Failed', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            DB::rollBack(); // Ensure rollback
            Log::error('API Loan Application update failed: ' . $e->getMessage(), [
                 'exception' => $e,
                 'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to update loan application.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanApplication $loanApplication)
    {
        try {
            // Optional: Add policy check here if needed
            // $this->authorize('delete', $loanApplication);

            // Check if the loan application can be deleted (e.g., only 'received' status)
            if ($loanApplication->status !== 'received') {
                 Log::warning('API Loan Application Deletion Denied - Incorrect Status', ['id' => $loanApplication->id, 'status' => $loanApplication->status]);
                return response()->json([
                    'message' => 'Only loan applications with "received" status can be deleted.'
                ], Response::HTTP_FORBIDDEN); // 403 Forbidden is appropriate
            }

            // Soft delete the loan application
            $loanApplication->delete();

            Log::info("API Loan Application {$loanApplication->id} soft deleted by user: " . Auth::guard('sanctum')->id());

            return response()->json(null, Response::HTTP_NO_CONTENT); // Standard response for successful deletion

        } catch (\Exception $e) {
            Log::error('API Loan Application deletion failed: ' . $e->getMessage(), ['id' => $loanApplication->id]);
            return response()->json(['message' => 'Failed to delete loan application.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
