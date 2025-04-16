<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoanApplication;
use App\Http\Requests\LoanApplicationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoanApplicationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('admin.loan-applications.index');
  }

  public function datatable()
  {
    try {
      $data = LoanApplication::with([
        'details:id,loan_application_id,amount',
        'customer.details:id,customer_id,first_name,last_name',
        'customer.company:id,name,customer_id',
        'customer.portfolio.broker.user:id,name',
      ])
        ->select([
          'loan_applications.id',
          'loan_applications.status',
          'loan_applications.customer_id',
          'loan_applications.created_at',
        ])
        ->latest()
        ->get();

      return datatables()->of($data)
        ->addColumn('created_at', function ($row) {
          return $row->created_at;
        })
        ->editColumn('status', function ($row) {
          return __($row->status);
        })
        ->toJson();
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to fetch loan applications. ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.loan-applications.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(LoanApplicationRequest $request)
  {
    Log::info('Loan Application Store Request Received', ['request_data' => $request->all()]);

    try {
      $validatedData = $request->validated();

      Log::info('Loan Application Store Validation Passed', ['validated_data' => $validatedData]);

      DB::beginTransaction();
      try {
        // 1. Create Customer
        $customer = Customer::create([
          'NID' => $validatedData['customer']['NID'],
          'lead_channel' => 'admin_panel', // Provide a default value for the required field
          // Add other direct customer fields if any exist in the model
        ]);
        Log::info('Customer Created', ['customer_id' => $customer->id]);

        // 2. Create Customer Details
        $customerDetail = $customer->details()->create($validatedData['customer']['details']);
        Log::info('Customer Details Created', ['customer_detail_id' => $customerDetail->id]);

        // 3. Create Customer Phones
        if (isset($validatedData['customer']['details']['phones'])) {
          foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
            // Only create phone if number is not null or empty
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

        // 5. Create Company
        $company = $customer->company()->create($validatedData['customer']['company']);
        Log::info('Company Created', ['company_id' => $company->id]);

        // 6. Create Company Addresses
        if (isset($validatedData['customer']['company']['addresses'])) {
          foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
            // Ensure the address type is appropriate for a company, e.g., 'work'
            $addressData['type'] = $addressData['type'] ?? 'work'; // Default or ensure type
            $company->addresses()->create($addressData);
          }
          Log::info('Company Addresses Created');
        }

        // 7. Create Company Phones (Optional)
        if (isset($validatedData['customer']['company']['phones'])) {
          foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
            // Only create phone if number is not null or empty
            if (!empty($phoneData['number'])) {
              $company->phones()->create($phoneData);
            }
          }
          Log::info('Company Phones Created');
        }

        // 8. Create Job Info
        $customer->jobInfo()->create($validatedData['customer']['jobInfo']);
        Log::info('Customer Job Info Created');

        // 9. Create Vehicle Info (Conditional)
        if (isset($validatedData['customer']['vehicle']) && !empty($validatedData['customer']['vehicle']['vehicle_brand'])) {
          $customer->vehicle()->create($validatedData['customer']['vehicle']);
          Log::info('Customer Vehicle Created');
        }

        // 10. Create References
        if (isset($validatedData['customer']['references'])) {
          foreach ($validatedData['customer']['references'] as $referenceData) {
            $customer->references()->create($referenceData);
          }
          Log::info('Customer References Created');
        }

        // 11. Create Loan Application
        $loanApplicationData = [
          'customer_id' => $customer->id,
          'status' => 'received', // Initial status
          'user_id' => Auth::id(), // Associate with the logged-in user
        ];
        $loanApplication = LoanApplication::create($loanApplicationData);
        Log::info('Loan Application Created', ['loan_application_id' => $loanApplication->id]);

        // 12. Create Loan Application Details (Optional)
        if (isset($validatedData['details']) && !empty(array_filter($validatedData['details']))) {
          // Check if amount is null
          if (isset($validatedData['details']['amount']) && $validatedData['details']['amount'] === null) {
            $validatedData['details']['amount'] = 0; // Set a default value like 0
          }
          $loanApplication->details()->create($validatedData['details']);
          Log::info('Loan Application Details Created');
        }

        DB::commit();
        Log::info('Transaction Committed Successfully');

        return redirect()->route('loan-applications.show', $loanApplication->id)
          ->with('success', __('Loan application submitted successfully'));
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Loan Application creation failed during transaction: ' . $e->getMessage(), [
          'exception' => $e,
          'trace' => $e->getTraceAsString() // Log stack trace for detailed debugging
        ]);

        return back()->withInput()->withErrors([
          'error' => __('Failed to submit loan application. Please try again or contact support.') . ' ' . $e->getMessage() // Provide generic message + specific error for debugging if needed
        ]);
      }
    } catch (ValidationException $e) {
      Log::warning('Loan Application Store Validation Failed', ['errors' => $e->errors()]);
      return back()->withInput()->withErrors($e->errors());
    } catch (\Exception $e) {
      // Catch any other unexpected errors
      Log::error('Unexpected error during Loan Application store: ' . $e->getMessage(), [
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
    try {
      // Define eager loading relationships once
      $relationships = [
        'details',
        'customer.details',
        'customer.details.phones' => function ($query) {
          $query->limit(2);
        },
        'customer.details.addresses' => function ($query) {
          $query->limit(2);
        },
        'customer.vehicle',
        'customer.company',
        'customer.jobInfo',
        'customer.financialInfo',
        'customer.references',
        'customer.portfolio.broker.user',
        'risks',
        'notes'
      ];

      // Use single with() call with dot notation for nested relationships
      $loanApplication = LoanApplication::with($relationships)
        ->findOrFail($id);
      return view('admin.loan-applications.show', compact('loanApplication'));
    } catch (\Exception $e) {
      // Log the error for debugging
      Log::error('Loan Application fetch failed: ' . $e->getMessage());

      return response()->json([
        'status' => 'error',
        'message' => 'Failed to fetch loan application. ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(int $id)
  {
    try {
      // Define eager loading relationships once
      $relationships = [
        'details',
        'customer.details',
        'customer.details.phones' => function ($query) {
          $query->limit(2);
        },
        'customer.details.addresses' => function ($query) {
          $query->limit(2);
        },
        'customer.vehicle',
        'customer.company',
        'customer.jobInfo',
        'customer.financialInfo',
        'customer.references',
        'customer.portfolio.broker.user',
        'risks',
        'notes'
      ];

      // Use single with() call with dot notation for nested relationships
      $loanApplication = LoanApplication::with($relationships)
        ->findOrFail($id);
      return view('admin.loan-applications.edit', compact('loanApplication'));
    } catch (\Exception $e) {
      // Log the error for debugging
      Log::error('Loan Application fetch failed: ' . $e->getMessage());

      return response()->json([
        'status' => 'error',
        'message' => 'Failed to fetch loan application. ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(LoanApplicationRequest $request, int $id)
  {
    // Log the entire request data for debugging
    Log::info('Loan Application Update Request', [
      'request_data' => $request->all(),
      'references' => $request->input('customer.references', 'NO REFERENCES')
    ]);

    try {
      // Find the loan application with relationships
      $loanApplication = LoanApplication::findOrFail($id);

      $validatedData = $request->validated();

      Log::info('Loan Application Update Validation Passed', ['validated_data' => $validatedData]);

      DB::beginTransaction();
      try {
        // Update Loan Application Details
        if (isset($validatedData['details'])) {
          // Check if amount is null
          if (isset($validatedData['details']['amount']) && $validatedData['details']['amount'] === null) {
            $validatedData['details']['amount'] = 0;
          }
          if (isset($validatedData['details']['term']) && $validatedData['details']['term'] === null) {
            $validatedData['details']['term'] = 1; // Set a default value for term
          }
          if (isset($validatedData['details']['rate']) && $validatedData['details']['rate'] === null) {
            $validatedData['details']['rate'] = 0; // Set a default value for rate
          }
          if (isset($validatedData['details']['frequency']) && $validatedData['details']['frequency'] === null) {
            $validatedData['details']['frequency'] = 'monthly'; // Set a default value for frequency
          }
          // Get the existing details record and update it directly
          $details = $loanApplication->details;
          if ($details) {
            $details->update($validatedData['details']);
            Log::info('Loan Application Details Updated', [
              'method' => 'update',
              'loan_application_id' => $loanApplication->id,
              'details' => $validatedData['details'],
            ]);
          } else {
            // If no details exist yet, create them
            $loanApplication->details()->create($validatedData['details']);
            Log::info('Loan Application Details Created', [
              'method' => 'create',
              'loan_application_id' => $loanApplication->id,
              'details' => $validatedData['details']
            ]);
          }
        }

        // Update Customer
        if (isset($validatedData['customer'])) {
          $customer = $loanApplication->customer;

          // Update Customer Basic Info
          if (isset($validatedData['customer']['NID'])) {
            $customer->update(['NID' => $validatedData['customer']['NID']]);
            Log::info('Customer Basic Info Updated', ['customer_id' => $customer->id]);
          }

          // Update Customer Details
          if (isset($validatedData['customer']['details'])) {
            $customerDetails = $customer->details;
            $customerDetails->update($validatedData['customer']['details']);
            Log::info('Customer Details Updated', ['customer_detail_id' => $customerDetails->id]);

            // Update Phones
            if (isset($validatedData['customer']['details']['phones'])) {
              $customerDetails->phones()->delete(); // Remove existing phones
              foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
                $customerDetails->phones()->create($phoneData);
              }
              Log::info('Customer Phones Updated');
            }

            // Update Addresses
            if (isset($validatedData['customer']['details']['addresses'])) {
              $customerDetails->addresses()->delete(); // Remove existing addresses
              foreach ($validatedData['customer']['details']['addresses'] as $addressData) {
                $customerDetails->addresses()->create($addressData);
              }
              Log::info('Customer Addresses Updated');
            }
          }

          // Update Company
          if (isset($validatedData['customer']['company'])) {
            $company = $customer->company;
            $company->update($validatedData['customer']['company']);
            Log::info('Company Updated', ['company_id' => $company->id]);

            // Update Company Phones
            if (isset($validatedData['customer']['company']['phones'])) {
              $company->phones()->delete();
              foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
                $company->phones()->create($phoneData);
              }
              Log::info('Company Phones Updated');
            }

            // Update Company Addresses
            if (isset($validatedData['customer']['company']['addresses'])) {
              $company->addresses()->delete();
              foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
                $company->addresses()->create($addressData);
              }
              Log::info('Company Addresses Updated');
            }
          }

          // Update Job Info
          if (isset($validatedData['customer']['jobInfo'])) {
            $customer->jobInfo()->update($validatedData['customer']['jobInfo']);
            Log::info('Customer Job Info Updated');
          }

          // Update Vehicle Info
          if (isset($validatedData['customer']['vehicle'])) {
            $customer->vehicle()->updateOrCreate(
              ['customer_id' => $customer->id],
              $validatedData['customer']['vehicle']
            );
            Log::info('Customer Vehicle Updated');
          }

          // Update References Info
          Log::info('References Update Started', [
            'references_data' => $validatedData['customer']['references'] ?? 'NO REFERENCES DATA'
          ]);

          if (isset($validatedData['customer']['references'])) {
            foreach ($validatedData['customer']['references'] as $referenceData) {
              Log::info('Processing Reference', [
                'reference_data' => $referenceData,
                'customer_id' => $customer->id
              ]);

              $reference = $customer->references()->updateOrCreate(
                ['id' => $referenceData['id']],
                [
                  'customer_id' => $customer->id,
                  'name' => $referenceData['name'],
                  'occupation' => $referenceData['occupation'] ?? null,
                  'relationship' => $referenceData['relationship'] ?? null,
                  'phone_number' => $referenceData['phone_number'] ?? null,
                ]
              );

              // Log after successful update or creation
              Log::info('Reference Updated/Created', [
                'reference_id' => $reference->id,
                'name' => $reference->name,
                'occupation' => $reference->occupation,
                'relationship' => $reference->relationship
              ]);
            }
          } else {
            Log::warning('No references data found in validated data');
          }
        }

        DB::commit();
        Log::info('Transaction Committed Successfully');

        // Reload the model with all relationships
        $loanApplication->load([
          'details',
          'customer.details',
          'customer.details.phones',
          'customer.details.addresses',
          'customer.company',
          'customer.company.phones',
          'customer.company.addresses',
          'customer.jobInfo',
          'customer.vehicle',
          'customer.references',
        ]);

        return redirect()->route('loan-applications.show', $loanApplication->id)
          ->with('success', 'Loan application updated successfully');
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Loan Application update failed: ' . $e->getMessage());
        return back()->withInput()->withErrors([
          'error' => 'Failed to update loan application. ' . $e->getMessage()
        ]);
      }
    } catch (ValidationException $e) {
      Log::warning('Loan Application Update Validation Failed', ['errors' => $e->errors()]);
      return back()->withInput()->withErrors($e->errors());
    } catch (\Exception $e) {
      Log::error('Loan Application update failed: ' . $e->getMessage());
      return back()->withInput()->withErrors([
        'error' => 'Failed to update loan application. ' . $e->getMessage()
      ]);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    try {
      $loanApplication = LoanApplication::findOrFail($id);

      // Check if the loan application can be deleted (only 'received' status)
      if ($loanApplication->status !== 'received') {
        return back()->withErrors([
          'error' => 'Only loan applications with "received" status can be deleted.'
        ]);
      }

      // Soft delete the loan application
      $loanApplication->delete();

      Log::info("Loan Application {$id} soft deleted by user: " . Auth::id());

      return redirect()->route('loan-applications.index')
        ->with('success', 'Loan application deleted successfully');
    } catch (\Exception $e) {
      Log::error('Loan Application deletion failed: ' . $e->getMessage());

      return back()->withErrors([
        'error' => 'Failed to delete loan application. ' . $e->getMessage()
      ]);
    }
  }
}
