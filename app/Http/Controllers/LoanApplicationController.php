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
    return view('admin.loan-applications.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      // Validate the incoming request
      $validatedData = $request->validate([
        // Personal Details
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'NID' => 'required|unique:customers,NID',
        'birth_date' => 'required|date|before:-18 years',
        'mobile_phone' => 'required|string',
        'home_phone' => 'nullable|string',
        'email' => 'required|email|unique:customers,email',
        'marital_status' => 'required|in:single,married,divorced,widowed',
        'nationality' => 'required|string',
        'address' => 'required|string',
        'housing_type' => 'required|in:owned,rented,family',
        'residence_start_date' => 'required|date',

        // Vehicle Information
        'own_vehicle' => 'nullable|boolean',
        'vehicle_financed' => 'nullable|boolean',
        'vehicle_brand' => 'nullable|string',
        'vehicle_year' => 'nullable|integer|min:1900|max:' . date('Y'),

        // Spouse Information
        'spouse_name' => 'nullable|string',
        'spouse_phone' => 'nullable|string',

        // Job Information
        'self_employed' => 'nullable|boolean',
        'company_name' => 'nullable|string',
        'work_phone' => 'nullable|string',
        'work_address' => 'nullable|string',
        'position' => 'nullable|string',
        'employment_start_date' => 'nullable|date',
        'monthly_salary' => 'required|numeric|min:0',
        'other_income' => 'nullable|numeric|min:0',
        'other_income_description' => 'nullable|string',
        'supervisor_name' => 'nullable|string',

        // References
        'references' => 'required|array|size:2',
        'references.*.name' => 'required|string',
        'references.*.occupation' => 'required|string',
        'references.*.relationship' => 'required|string',

        // Acceptance
        'acceptance' => 'accepted'
      ]);

      DB::beginTransaction();
      try {
        // Create Customer
        $customer = Customer::create([
          'identification' => $validatedData['identification'],
          'email' => $validatedData['email']
        ]);

        // Create Customer Details
        $customerDetail = $customer->details()->create([
          'first_name' => $validatedData['first_name'],
          'last_name' => $validatedData['last_name'],
          'birth_date' => $validatedData['birth_date'],
          'marital_status' => $validatedData['marital_status'],
          'nationality' => $validatedData['nationality']
        ]);

        // Create Phone
        $customerDetail->phones()->create([
          'type' => 'mobile',
          'number' => $validatedData['mobile_phone']
        ]);

        if (!empty($validatedData['home_phone'])) {
          $customerDetail->phones()->create([
            'type' => 'home',
            'number' => $validatedData['home_phone']
          ]);
        }

        // Create Address
        $customerDetail->addresses()->create([
          'type' => 'primary',
          'address' => $validatedData['address'],
          'housing_type' => $validatedData['housing_type'],
          'residence_start_date' => $validatedData['residence_start_date']
        ]);

        // Create Job Info if applicable
        if (!empty($validatedData['company_name']) || !empty($validatedData['position'])) {
          $customer->jobInfo()->create([
            'self_employed' => $validatedData['self_employed'] ?? false,
            'company_name' => $validatedData['company_name'],
            'work_phone' => $validatedData['work_phone'],
            'work_address' => $validatedData['work_address'],
            'position' => $validatedData['position'],
            'employment_start_date' => $validatedData['employment_start_date'],
            'monthly_salary' => $validatedData['monthly_salary'],
            'other_income' => $validatedData['other_income'] ?? 0,
            'other_income_description' => $validatedData['other_income_description'],
            'supervisor_name' => $validatedData['supervisor_name']
          ]);
        }

        // Create Vehicle Info if applicable
        if ($validatedData['own_vehicle']) {
          $customer->vehicleInfo()->create([
            'brand' => $validatedData['vehicle_brand'],
            'year' => $validatedData['vehicle_year'],
            'financed' => $validatedData['vehicle_financed'] ?? false
          ]);
        }

        // Create Spouse Info if applicable
        if (!empty($validatedData['spouse_name'])) {
          $customer->spouseInfo()->create([
            'name' => $validatedData['spouse_name'],
            'phone' => $validatedData['spouse_phone']
          ]);
        }

        // Create References
        foreach ($validatedData['references'] as $referenceData) {
          $customer->references()->create([
            'name' => $referenceData['name'],
            'occupation' => $referenceData['occupation'],
            'relationship' => $referenceData['relationship']
          ]);
        }

        // Create Loan Application
        $loanApplication = LoanApplication::create([
          'customer_id' => $customer->id,
          'status' => 'received'
        ]);

        DB::commit();

        return redirect()->route('loan-applications.show', $loanApplication->id)
          ->with('success', 'Loan application submitted successfully');
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Loan Application creation failed: ' . $e->getMessage());

        return back()->withInput()->withErrors([
          'error' => 'Failed to submit loan application. ' . $e->getMessage()
        ]);
      }
    } catch (ValidationException $e) {
      return back()->withInput()->withErrors($e->errors());
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

        // Customer Details
        'customer.NID' => 'sometimes|string|max:50',
        'customer.details.first_name' => 'sometimes|string|max:100',
        'customer.details.last_name' => 'sometimes|string|max:100',
        'customer.details.birthday' => 'sometimes|date',
        'customer.details.email' => 'sometimes|email|max:255',
        'customer.details.marital_status' => 'sometimes|in:single,married,divorced,widowed,other',
        'customer.details.nationality' => 'sometimes|string|max:100',
        'customer.details.gender' => 'sometimes|in:male,female',
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
        'customer.jobInfo.other_incomes' => 'sometimes|numeric|min:0',
        'customer.jobInfo.other_incomes_source' => 'sometimes|string|max:255',
        'customer.jobInfo.schedule' => 'sometimes|string|max:255',
        'customer.jobInfo.supervisor_name' => 'sometimes|string|max:255',
      ]);

      DB::beginTransaction();
      try {
        // Update Loan Application Details
        if (isset($validatedData['details'])) {
          $loanApplication->details()->update($validatedData['details']);
        }

        // Update Customer
        if (isset($validatedData['customer'])) {
          $customer = $loanApplication->customer;

          // Update Customer Basic Info
          if (isset($validatedData['customer']['NID'])) {
            $customer->update(['NID' => $validatedData['customer']['NID']]);
          }

          // Update Customer Details
          if (isset($validatedData['customer']['details'])) {
            $customerDetails = $customer->details;
            $customerDetails->update($validatedData['customer']['details']);

            // Update Phones
            if (isset($validatedData['customer']['details']['phones'])) {
              $customerDetails->phones()->delete(); // Remove existing phones
              foreach ($validatedData['customer']['details']['phones'] as $phoneData) {
                $customerDetails->phones()->create($phoneData);
              }
            }

            // Update Addresses
            if (isset($validatedData['customer']['details']['addresses'])) {
              $customerDetails->addresses()->delete(); // Remove existing addresses
              foreach ($validatedData['customer']['details']['addresses'] as $addressData) {
                $customerDetails->addresses()->create($addressData);
              }
            }

          }
          
          // Update Vehicle Info
          if (isset($validatedData['customer']['vehicle'])) {
            $customer->vehicle()->updateOrCreate(
              ['customer_id' => $customer->id],
              $validatedData['customer']['vehicle']
            );
          }

          // Update Company
          if (isset($validatedData['customer']['company'])) {
            $company = $customer->company;
            $company->update($validatedData['customer']['company']);

            // Update Company Phones
            if (isset($validatedData['customer']['company']['phones'])) {
              $company->phones()->delete();
              foreach ($validatedData['customer']['company']['phones'] as $phoneData) {
                $company->phones()->create($phoneData);
              }
            }

            // Update Company Addresses
            if (isset($validatedData['customer']['company']['addresses'])) {
              $company->addresses()->delete();
              foreach ($validatedData['customer']['company']['addresses'] as $addressData) {
                $company->addresses()->create($addressData);
              }
            }
          }

          // Update Job Info
          if (isset($validatedData['customer']['jobInfo'])) {
            $customer->jobInfo()->update($validatedData['customer']['jobInfo']);
          }
        }

        DB::commit();

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
