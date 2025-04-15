<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
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
    return view('loan-applications.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    Log::info('Loan Application Store Request Received', ['request_data' => $request->all()]);

    try {
      // Validate the incoming request using nested structure
      $validatedData = $request->validate([
        // Loan Details (Optional at creation)
        'details.amount' => 'sometimes|nullable|numeric|min:0',
        'details.term' => 'sometimes|nullable|integer|min:1',
        'details.rate' => 'sometimes|nullable|numeric|min:0|max:100',
        'details.frequency' => 'sometimes|nullable|in:weekly,biweekly,monthly',
        'details.purpose' => 'sometimes|nullable|string|max:1000',

        // Customer Core Info (Required)
        'customer.NID' => 'required|string|max:50|unique:customers,NID', // Ensure NID is unique
        'customer.details.first_name' => 'required|string|max:100',
        'customer.details.last_name' => 'required|string|max:100',
        'customer.details.birthday' => 'required|date',
        'customer.details.email' => 'required|email|max:255|unique:customer_details,email', // Ensure email is unique
        'customer.details.marital_status' => 'required|in:single,married,divorced,widowed,other',
        'customer.details.nationality' => 'required|string|max:100',
        'customer.details.housing_type' => 'required|in:owned,rented,mortgaged,other',

        // Customer Optional Info
        'customer.details.gender' => 'sometimes|nullable|in:male,female',
        'customer.details.education_level' => 'sometimes|nullable|in:primary,secondary,high_school,bachelor,postgraduate,master,doctorate,other',
        'customer.details.move_in_date' => 'sometimes|nullable|date',

        // Customer Phones (Required: at least one mobile)
        'customer.details.phones' => 'required|array|min:1',
        'customer.details.phones.*.number' => 'sometimes|nullable|string|max:20',
        'customer.details.phones.*.type' => 'sometimes|nullable|in:mobile,home',
        'customer.details.phones' => [ // Custom rule to ensure at least one mobile phone
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

        // Customer Addresses (Required: at least one primary)
        'customer.details.addresses' => 'required|array|min:1',
        'customer.details.addresses.*.street' => 'required|string|max:255',
        'customer.details.addresses.*.street2' => 'sometimes|nullable|string|max:255',
        'customer.details.addresses.*.city' => 'required|string|max:100',
        'customer.details.addresses.*.state' => 'required|string|max:100',
        'customer.details.addresses.*.type' => 'required|in:home,work,billing,shipping', // Use valid ENUM values
        'customer.details.addresses' => [ // Custom rule to ensure at least one 'home' address (assuming this is primary)
          'required',
          'array',
          'min:1',
          function ($attribute, $value, $fail) {
            $hasHome = collect($value)->contains(function ($address) {
              return isset($address['type']) && $address['type'] === 'home' && !empty($address['street']); // Check for 'home' type
            });
            if (!$hasHome) {
              $fail(__('validation.custom.customer.details.addresses.home_required')); // Adjust message key if needed
            }
          },
        ],

        // Vehicle (Optional)
        'customer.vehicle.vehicle_type' => 'sometimes|nullable|required_with:customer.vehicle.vehicle_brand|in:owned,rented,financed,shared,leased,borrowed,none,other',
        'customer.vehicle.vehicle_brand' => 'sometimes|nullable|string|max:100',
        'customer.vehicle.vehicle_model' => 'sometimes|nullable|string|max:100',
        'customer.vehicle.vehicle_year' => 'sometimes|nullable|integer|min:1900|max:' . date('Y') + 1, // Max current year + 1

        // Job Information (Required fields if employed, company name required)
        'customer.jobInfo.is_self_employed' => 'required|boolean',
        'customer.company.name' => 'required|string|max:255',
        'customer.company.email' => 'sometimes|nullable|email|max:255|unique:companies,email', // Added company email validation
        'customer.company.phones.*.number' => 'sometimes|nullable|string|max:20', // Optional company phone
        'customer.company.addresses' => 'required|array|min:1', // Require at least one company address
        'customer.company.addresses.*.street' => 'required|string|max:255',
        'customer.company.addresses.*.street2' => 'sometimes|nullable|string|max:255',
        'customer.company.addresses.*.city' => 'sometimes|string|max:100',
        'customer.company.addresses.*.state' => 'sometimes|string|max:100',
        'customer.company.addresses.*.type' => 'required|in:home,work,billing,shipping', // Use valid ENUM values
        'customer.jobInfo.role' => 'required|string|max:100',
        'customer.jobInfo.start_date' => 'required|date',
        'customer.jobInfo.salary' => 'required|numeric|min:0',

        // Job Information (Optional)
        'customer.jobInfo.payment_type' => 'sometimes|nullable|in:cash,bank_transfer',
        'customer.jobInfo.payment_frequency' => 'sometimes|nullable|in:weekly,biweekly,monthly',
        'customer.jobInfo.payment_bank' => 'sometimes|nullable|string|max:255',
        'customer.jobInfo.other_incomes' => 'sometimes|nullable|numeric|min:0',
        'customer.jobInfo.other_incomes_source' => 'sometimes|nullable|required_with:customer.jobInfo.other_incomes|string|max:255',
        'customer.jobInfo.schedule' => 'sometimes|nullable|string|max:255',
        'customer.jobInfo.level' => 'sometimes|nullable|string|max:255',
        'customer.jobInfo.supervisor_name' => 'sometimes|nullable|string|max:255',

        // References (Required: at least one, name and relationship required per reference)
        // Spouse info should be submitted as a reference with relationship 'spouse' or similar
        'customer.references' => 'required|array|min:1',
        'customer.references.*.name' => 'required|string|max:255',
        'customer.references.*.relationship' => 'required|string|max:255',
        'customer.references.*.occupation' => 'sometimes|nullable|string|max:255',
        'customer.references.*.phone_number' => 'sometimes|nullable|string|max:20',

        // Acceptance (Required)
        'acceptance' => 'accepted'
      ], [
        // Custom validation messages (optional, but good practice)
        'customer.NID.unique' => __('validation.unique', ['attribute' => __('NID')]),
        'customer.details.email.unique' => __('validation.unique', ['attribute' => __('Email')]),
        'customer.details.phones.mobile_required' => __('A mobile phone number is required.'),
        'customer.details.addresses.home_required' => __('A home address is required.'), // Adjusted message key
        'acceptance.accepted' => __('You must accept the terms and conditions.'),
      ]);

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
            $company->phones()->create($phoneData);
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
          $loanApplication->details()->create($validatedData['details']);
          Log::info('Loan Application Details Created');
        }

        DB::commit();
        Log::info('Transaction Committed Successfully');

        return redirect()->route('admin.loan-applications.show', $loanApplication->id)
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
  public function update(Request $request, int $id)
  {
    // Log the entire request data for debugging
    Log::info('Loan Application Update Request', [
      'request_data' => $request->all(),
      'references' => $request->input('customer.references', 'NO REFERENCES')
    ]);

    try {
      // Find the loan application with relationships
      $loanApplication = LoanApplication::findOrFail($id);

      // Validate the incoming request - including nested relationships
      $validatedData = $request->validate([
        // Loan Details
        'details.amount' => 'sometimes|numeric|min:0',
        'details.term' => 'sometimes|integer|min:1',
        'details.rate' => 'sometimes|numeric|min:0|max:100',
        'details.frequency' => 'sometimes|in:weekly,biweekly,monthly',
        'details.purpose' => 'sometimes|string|max:1000',
        'details.quota' => 'sometimes|numeric|min:0',
        'details.customer_comment' => 'sometimes|string|max:1000',

        // Customer Details
        'customer.NID' => 'sometimes|string|max:50',
        'customer.details.first_name' => 'sometimes|string|max:100',
        'customer.details.last_name' => 'sometimes|string|max:100',
        'customer.details.birthday' => 'sometimes|date',
        'customer.details.email' => 'sometimes|email|max:255',
        'customer.details.marital_status' => 'sometimes|in:single,married,divorced,widowed,other',
        'customer.details.nationality' => 'sometimes|string|max:100',
        'customer.details.gender' => 'sometimes|nullable|in:male,female',
        'customer.details.education_level' => 'sometimes|in:primary,secondary,high_school,bachelor,postgraduate,master,doctorate,other',
        'customer.details.housing_type' => 'sometimes|in:owned,rented,mortgaged,other',
        'customer.details.move_in_date' => 'sometimes|date',

        // Phones
        'customer.details.phones.*.number' => 'sometimes|string|max:20',
        'customer.details.phones.*.type' => 'sometimes|in:mobile,home',

        // Addresses
        'customer.details.addresses.*.street' => 'sometimes|string|max:255',
        'customer.details.addresses.*.street2' => 'sometimes|string|max:255',
        'customer.details.addresses.*.city' => 'sometimes|string|max:100',
        'customer.details.addresses.*.state' => 'sometimes|string|max:100',

        // Vehicle
        'customer.vehicle.vehicle_type' => 'sometimes|in:owned,rented,financed,shared,leased,borrowed,none,other',
        'customer.vehicle.vehicle_brand' => 'sometimes|string|max:100',
        'customer.vehicle.vehicle_model' => 'sometimes|string|max:100',
        'customer.vehicle.vehicle_year' => 'sometimes|integer|min:1900|max:2100',

        // Job Information
        'customer.jobInfo.is_self_employed' => 'sometimes|boolean',
        'customer.company.name' => 'sometimes|string|max:255',
        'customer.company.phones.*.number' => 'sometimes|string|max:20',
        'customer.company.addresses.*.street' => 'sometimes|string|max:255',
        'customer.jobInfo.role' => 'sometimes|string|max:100',
        'customer.jobInfo.start_date' => 'sometimes|date',
        'customer.jobInfo.salary' => 'sometimes|numeric|min:0',
        'customer.jobInfo.payment_type' => 'sometimes|in:cash,bank_transfer',
        'customer.jobInfo.payment_frequency' => 'sometimes|in:weekly,biweekly,monthly',
        'customer.jobInfo.payment_bank' => 'sometimes|string|max:255',
        'customer.jobInfo.other_incomes' => 'sometimes|nullable|numeric|min:0',
        'customer.jobInfo.other_incomes_source' => 'sometimes|nullable|string|max:255',
        'customer.jobInfo.schedule' => 'sometimes|nullable|string|max:255',
        'customer.jobInfo.supervisor_name' => 'sometimes|string|max:255',


        // References
        'customer.references' => 'required|array|min:1', // Changed size:2 to min:1
        'customer.references.*.id' => 'required|integer|exists:customer_references,id',
        'customer.references.*.name' => 'required|string|max:255',
        'customer.references.*.occupation' => 'sometimes|string|max:255',
        'customer.references.*.relationship' => 'sometimes|string|max:255',
        'customer.references.*.phone_number' => 'sometimes|nullable|string|max:20', // Added validation for phone number

      ]);

      Log::info('Loan Application Update Validation Passed', ['validated_data' => $validatedData]);

      DB::beginTransaction();
      try {
        // Update Loan Application Details
        if (isset($validatedData['details'])) {
          // Get the existing details record and update it directly
          $details = $loanApplication->details;
          if ($details) {
            $details->update($validatedData['details']);
            Log::info('Loan Application Details Updated', [
              'loan_application_id' => $loanApplication->id,
              'data' => $validatedData['details'],
              'amount' => $details->amount,
              'term' => $details->term,
              'rate' => $details->rate,
              'frequency' => $details->frequency,
              'purpose' => $details->purpose,
              'quota' => $details->quota,
              'customer_comment' => $details->customer_comment,
            ]);
          } else {
            // If no details exist yet, create them
            $loanApplication->details()->create($validatedData['details']);
            Log::info('Loan Application Details Created', [
              'loan_application_id' => $loanApplication->id,
              'data' => $validatedData['details']
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
