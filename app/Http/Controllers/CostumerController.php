<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest; // Import the new request class
use App\Models\Customer;
use App\Models\Company; // Added for type hinting if needed
use App\Models\CustomerDetail; // Added for type hinting if needed
// use Illuminate\Http\Request; // No longer needed for store/update
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CostumerController extends Controller // Renamed class
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Implement view for listing customers
        return view('admin.customers.index'); // Changed view path
    }

    /**
     * Provide data for the datatable.
     */
    public function datatable()
    {
        // TODO: Implement datatable logic for customers
        try {
            $query = Customer::with([
                'details:id,customer_id,first_name,last_name',
                'company:id,name,customer_id',
                'portfolio.broker.user:id,name', // Keep broker info if relevant
            ])
                ->select('customers.*'); // Select all columns from customers

            return datatables()->eloquent($query)
                ->addColumn('name', function ($row) {
                    return $row->details?->first_name . ' ' . $row->details?->last_name;
                })
                ->addColumn('company', function ($row) {
                    return $row->company?->name;
                })
                 ->addColumn('NID', function ($row) {
                    return $row->NID;
                })
                ->addColumn('broker', function ($row) {
                    return $row->portfolio?->broker?->user?->name;
                })
                ->addColumn('actions', function ($row) {
                    // Generate action buttons (e.g., View, Edit)
                    $viewUrl = route('customers.show', $row->id); // Changed route name
                    $editUrl = route('customers.edit', $row->id); // Changed route name
                    return '<a class="btn btn-primary" href="' . $viewUrl . '">' . __('View') . '</a> ';
                })
                 ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('created_at_formated', function ($row) {
                    return $row->created_at->format('d/m/Y h:i:s A'); // Format date with AM/PM
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('details', function ($q) use ($keyword) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$keyword}%")
                            ->orWhere('first_name', 'like', "%{$keyword}%")
                            ->orWhere('last_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('NID', function ($query, $keyword) {
                    $query->where('NID', 'like', "%{$keyword}%");
                })
                ->filterColumn('company', function ($query, $keyword) {
                     $query->whereHas('company', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('broker', function ($query, $keyword) {
                    $query->whereHas('portfolio.broker.user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                 ->filterColumn('created_at', function ($query, $keyword) {
                    // Custom filter for the 'created_at' column
                    $query->whereDate('created_at', '=', $keyword);
                })
                ->orderColumn('created_at', function ($query, $order) {
                    // Custom ordering for the 'created_at' column - Force descending
                    $query->orderBy('created_at', 'desc');
                })
                ->rawColumns(['actions'])
                ->toJson();
        } catch (\Exception $e) {
            Log::error('Customer Datatable Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'error' => 'Failed to fetch customers. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create'); // Changed view path
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request) // Use CustomerRequest for validation
    {
        Log::info('Customer Store Request Received', ['request_data' => $request->all()]);

        try {
            // Validation is now handled by CustomerRequest
            $validatedData = $request->validated();

            Log::info('Customer Store Validation Passed', ['validated_data' => $validatedData]);

            DB::beginTransaction();
            try {
                // 1. Create Customer
                $customer = Customer::create([
                    'NID' => $validatedData['customer']['NID'],
                    'lead_channel' => 'admin_panel', // Or get from request if applicable
                    'user_id' => Auth::id(), // Associate with the logged-in user
                    // Add other direct customer fields if any exist in the model
                ]);
                Log::info('Customer Created', ['customer_id' => $customer->id]);

                // 2. Create Customer Details
                $customerDetail = $customer->details()->create($validatedData['customer']['details']);
                Log::info('Customer Details Created', ['customer_detail_id' => $customerDetail->id]);

                // 3. Create Customer Phones
                if (isset($validatedData['customer']['details']['phones'])) {
                    foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
                        if (!empty($phoneData['number'])) {
                            $customerDetail->phones()->create($phoneData);
                        }
                    }
                    Log::info('Customer Phones Created');
                }

                // 4. Create Customer Addresses
                if (isset($validatedData['customer']['details']['addresses'])) {
                    foreach ($validatedData['customer']['details']['addresses'] as $addressData) {
                        $customerDetail->addresses()->create($addressData);
                    }
                    Log::info('Customer Addresses Created');
                }

                // 5. Create Company (if data provided)
                if (isset($validatedData['customer']['company']) && !empty(array_filter($validatedData['customer']['company']))) {
                    $company = $customer->company()->create($validatedData['customer']['company']);
                    Log::info('Company Created', ['company_id' => $company->id]);

                    // 6. Create Company Addresses
                    if (isset($validatedData['customer']['company']['addresses'])) {
                        foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
                            $addressData['type'] = $addressData['type'] ?? 'work';
                            $company->addresses()->create($addressData);
                        }
                        Log::info('Company Addresses Created');
                    }

                    // 7. Create Company Phones
                    if (isset($validatedData['customer']['company']['phones'])) {
                        foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
                            if (!empty($phoneData['number'])) {
                                $company->phones()->create($phoneData);
                            }
                        }
                        Log::info('Company Phones Created');
                    }
                }


                // 8. Create Job Info (if data provided)
                if (isset($validatedData['customer']['jobInfo']) && !empty(array_filter($validatedData['customer']['jobInfo']))) {
                    $customer->jobInfo()->create($validatedData['customer']['jobInfo']);
                    Log::info('Customer Job Info Created');
                }

                // 9. Create Vehicle Info (Conditional)
                if (isset($validatedData['customer']['vehicle']) && !empty($validatedData['customer']['vehicle']['vehicle_brand'])) {
                    $customer->vehicle()->create($validatedData['customer']['vehicle']);
                    Log::info('Customer Vehicle Created');
                }

                // 10. Create References
                if (isset($validatedData['customer']['references'])) {
                    foreach ($validatedData['customer']['references'] as $referenceData) {
                         if (!empty(array_filter($referenceData))) { // Only process if data exists
                            // Separate phone data
                            $phonesData = $referenceData['phones'] ?? [];
                            unset($referenceData['phones']); // Remove phones from main data

                            // Create the reference without the phone number field
                            $reference = $customer->references()->create($referenceData);

                            // Create associated phones
                            foreach ($phonesData as $phoneData) {
                                if (!empty($phoneData['number'])) {
                                    $reference->phones()->create($phoneData); // Create phone linked to the reference
                                }
                            }
                         }
                    }
                    Log::info('Customer References and Phones Created'); // Update log message
                }

                // Removed LoanApplication and LoanApplicationDetail creation

                DB::commit();
                Log::info('Transaction Committed Successfully for Customer Creation');

                return redirect()->route('customers.show', $customer->id) // Changed route name
                    ->with('success', __('Customer created successfully')); // Changed success message

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Customer creation failed during transaction: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);

                return back()->withInput()->withErrors([
                    'error' => __('Failed to create customer. Please try again or contact support.') . ' ' . $e->getMessage()
                ]);
            }
        } catch (ValidationException $e) {
            Log::warning('Customer Store Validation Failed', ['errors' => $e->errors()]);
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Unexpected error during Customer store: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors([
                'error' => __('An unexpected error occurred. Please try again or contact support.')
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // TODO: Implement view for showing a customer
        try {
            $relationships = [
                'details',
                'details.phones', // Simplified relationship loading
                'details.addresses',
                'vehicle',
                'company',
                'company.phones',
                'company.addresses',
                'jobInfo',
                'financialInfo', // Keep if needed
                'references',
                'portfolio.broker.user', // Keep if needed
                // Removed loan application specific relations like 'risks', 'notes'
            ];

            $customer = Customer::with($relationships)->findOrFail($id);
            return view('admin.customers.show', compact('customer')); // Changed view path

        } catch (\Exception $e) {
            Log::error('Customer fetch failed: ' . $e->getMessage());
            // Redirect back or show an error view
             return redirect()->route('customers.index')->withErrors([ // Changed route name
                'error' => 'Failed to fetch customer. ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        // TODO: Implement view for editing a customer
         try {
            $relationships = [
                'details',
                'details.phones',
                'details.addresses',
                'vehicle',
                'company',
                'company.phones',
                'company.addresses',
                'jobInfo',
                'financialInfo',
                'references',
                'portfolio.broker.user',
            ];

            $customer = Customer::with($relationships)->findOrFail($id);
            return view('admin.customers.edit', compact('customer')); // Changed view path

        } catch (\Exception $e) {
            Log::error('Customer fetch for edit failed: ' . $e->getMessage());
             return redirect()->route('customers.index')->withErrors([ // Changed route name
                'error' => 'Failed to fetch customer for editing. ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, int $id) // Use CustomerRequest for validation
    {
        Log::info('Customer Update Request Received', ['request_data' => $request->all(), 'customer_id' => $id]);

        try {
            $customer = Customer::findOrFail($id);

            // Validation is now handled by CustomerRequest
            $validatedData = $request->validated();

            Log::info('Customer Update Validation Passed', ['validated_data' => $validatedData]);

            DB::beginTransaction();
            try {
                // Removed Loan Application Details update

                // Update Customer
                if (isset($validatedData['customer'])) {
                    // Update Customer Basic Info
                    if (isset($validatedData['customer']['NID'])) {
                        $customer->update(['NID' => $validatedData['customer']['NID']]);
                        Log::info('Customer Basic Info Updated', ['customer_id' => $customer->id]);
                    }

                    // Update Customer Details
                    if (isset($validatedData['customer']['details'])) {
                        $customerDetails = $customer->details;
                        if ($customerDetails) {
                            $customerDetails->update($validatedData['customer']['details']);
                            Log::info('Customer Details Updated', ['customer_detail_id' => $customerDetails->id]);

                            // Update Phones
                            if (isset($validatedData['customer']['details']['phones'])) {
                                $customerDetails->phones()->delete(); // Remove existing phones
                                foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
                                     if (!empty($phoneData['number'])) { // Only save if number exists
                                        $customerDetails->phones()->create($phoneData);
                                     }
                                }
                                Log::info('Customer Phones Updated');
                            }

                            // Update Addresses
                            if (isset($validatedData['customer']['details']['addresses'])) {
                                $customerDetails->addresses()->delete(); // Remove existing addresses
                                foreach ($validatedData['customer']['details']['addresses'] as $addressData) {
                                     if (!empty(array_filter($addressData))) { // Only save if data exists
                                        $customerDetails->addresses()->create($addressData);
                                     }
                                }
                                Log::info('Customer Addresses Updated');
                            }
                        } else {
                             // Create details if they don't exist
                             $customer->details()->create($validatedData['customer']['details']);
                             Log::info('Customer Details Created during update');
                             // Handle phones/addresses for newly created details if necessary
                        }
                    }

                    // Update Company
                    if (isset($validatedData['customer']['company'])) {
                        $company = $customer->company()->updateOrCreate(
                            ['customer_id' => $customer->id], // Find by customer_id
                            $validatedData['customer']['company'] // Data to update or create with
                        );
                        Log::info('Company Updated/Created', ['company_id' => $company->id]);

                        // Update Company Phones
                        if (isset($validatedData['customer']['company']['phones'])) {
                            $company->phones()->delete();
                            foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
                                 if (!empty($phoneData['number'])) {
                                    $company->phones()->create($phoneData);
                                 }
                            }
                            Log::info('Company Phones Updated');
                        }

                        // Update Company Addresses
                        if (isset($validatedData['customer']['company']['addresses'])) {
                            $company->addresses()->delete();
                            foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
                                 if (!empty(array_filter($addressData))) {
                                    $addressData['type'] = $addressData['type'] ?? 'work';
                                    $company->addresses()->create($addressData);
                                 }
                            }
                            Log::info('Company Addresses Updated');
                        }
                    }

                    // Update Job Info
                    if (isset($validatedData['customer']['jobInfo'])) {
                         $customer->jobInfo()->updateOrCreate(
                            ['customer_id' => $customer->id],
                            $validatedData['customer']['jobInfo']
                        );
                        Log::info('Customer Job Info Updated/Created');
                    }

                    // Update Vehicle Info
                    if (isset($validatedData['customer']['vehicle'])) {
                        $customer->vehicle()->updateOrCreate(
                            ['customer_id' => $customer->id],
                            $validatedData['customer']['vehicle']
                        );
                        Log::info('Customer Vehicle Updated/Created');
                    }

                    // Update References Info
                    if (isset($validatedData['customer']['references'])) {
                        // Get existing reference IDs for comparison
                        $existingReferenceIds = $customer->references()->pluck('id')->toArray();
                        $incomingReferenceIds = collect($validatedData['customer']['references'])->pluck('id')->filter()->toArray();

                        // Delete references that are no longer in the request
                        $idsToDelete = array_diff($existingReferenceIds, $incomingReferenceIds);
                        if (!empty($idsToDelete)) {
                            $customer->references()->whereIn('id', $idsToDelete)->delete();
                            Log::info('Deleted old references', ['ids' => $idsToDelete]);
                        }

                        // Update or Create references
                        foreach ($validatedData['customer']['references'] as $referenceData) {
                             if (!empty(array_filter($referenceData))) { // Only process if data exists
                                $referenceId = $referenceData['id'] ?? null;
                                $referenceId = $referenceData['id'] ?? null;
                                // Separate phone data
                                $phonesData = $referenceData['phones'] ?? [];
                                unset($referenceData['phones']); // Remove phones from main data
                                unset($referenceData['phone_number']); // Ensure old field is not present

                                // Data for updateOrCreate (without phone_number)
                                $referenceUpdateData = [
                                    'customer_id' => $customer->id, // Ensure customer_id is set
                                    'name' => $referenceData['name'],
                                    'occupation' => $referenceData['occupation'] ?? null,
                                    'relationship' => $referenceData['relationship'] ?? null,
                                    // Removed 'phone_number'
                                ];

                                // Update or create the reference itself
                                $reference = $customer->references()->updateOrCreate(
                                    ['id' => $referenceId], // Match criteria by ID only
                                    $referenceUpdateData // Data to update/create with
                                );

                                // Update phones (delete existing, create new)
                                $reference->phones()->delete(); // Remove old phones for this reference
                                foreach ($phonesData as $phoneData) {
                                    if (!empty($phoneData['number'])) {
                                        $reference->phones()->create($phoneData); // Create new phones
                                    }
                                }
                                Log::info('Reference and Phones Updated/Created', ['reference_id' => $reference->id]);
                             }
                        }
                         Log::info('Customer References and Phones Updated'); // Update log message
                    } else {
                         // If no references array is sent, potentially delete all existing ones
                         // $customer->references()->delete();
                         // Log::info('All customer references deleted as none were provided in update.');
                         // Decide if this is the desired behavior
                    }
                }

                DB::commit();
                Log::info('Transaction Committed Successfully for Customer Update');

                return redirect()->route('customers.show', $customer->id) // Changed route name
                    ->with('success', __('Customer updated successfully')); // Changed success message

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Customer update failed during transaction: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->withErrors([
                    'error' => __('Failed to update customer. Please try again or contact support.') . ' ' . $e->getMessage()
                ]);
            }
        } catch (ValidationException $e) {
            Log::warning('Customer Update Validation Failed', ['errors' => $e->errors()]);
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Unexpected error during Customer update: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors([
                'error' => __('An unexpected error occurred during update. Please try again or contact support.')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // TODO: Implement logic for deleting a customer (soft delete recommended)
        try {
            $customer = Customer::findOrFail($id);

            // Optional: Add checks here if a customer can be deleted (e.g., no active loans)
            // if ($customer->hasActiveLoans()) { // Example check
            //     return back()->withErrors(['error' => 'Cannot delete customer with active loans.']);
            // }

            DB::beginTransaction();
            try {
                // Consider deleting related data or using cascading deletes in migrations
                $customer->details()->delete();
                $customer->company()->delete(); // This might need cascading for addresses/phones
                $customer->jobInfo()->delete();
                $customer->vehicle()->delete();
                $customer->references()->delete();
                // Add deletion for other related models if necessary

                $customer->delete(); // Soft delete the customer
                DB::commit();
                Log::info("Customer {$id} soft deleted by user: " . Auth::id());

                return redirect()->route('customers.index') // Changed route name
                    ->with('success', __('Customer deleted successfully')); // Changed success message
            } catch (\Exception $e) {
                 DB::rollBack();
                 Log::error('Customer deletion failed during transaction: ' . $e->getMessage(), ['exception' => $e]);
                 return back()->withErrors([
                    'error' => __('Failed to delete customer and related data.') . ' ' . $e->getMessage()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Customer deletion failed: ' . $e->getMessage());
            return back()->withErrors([
                'error' => __('Failed to delete customer.') . ' ' . $e->getMessage()
            ]);
        }
    }
}
