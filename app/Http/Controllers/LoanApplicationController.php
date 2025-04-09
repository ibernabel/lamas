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
          $query->limit(1);
        },
        'customer.details.addresses' => function ($query) {
          $query->limit(1);
        },
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
          $query->limit(1);
        },
        'customer.details.addresses' => function ($query) {
          $query->limit(1);
        },
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
        'details' => 'sometimes|array',
        'details.*.field' => 'sometimes|string',  // Ajusta según tus campos

        'customer' => 'sometimes|array',
        'customer.details' => 'sometimes|array',
        'customer.details.*.field' => 'sometimes|string',

        'customer.company' => 'sometimes|array',
        'customer.company.name' => 'sometimes|string',
        'customer.company.phone' => 'sometimes|string',
        'customer.company.address' => 'sometimes|string',
        // Añade más campos específicos de company

        'customer.job_info' => 'sometimes|array',
        'customer.job_info.self_employed' => 'sometimes|boolean',
        'customer.financial_info' => 'sometimes|array',
        'customer.references' => 'sometimes|array',
        'customer.portfolio' => 'sometimes|array',

        'risks' => 'sometimes|array',
        'risks.*.type' => 'sometimes|string',
        'risks.*.level' => 'sometimes|string',

        'notes' => 'sometimes|array',
        'notes.*.content' => 'sometimes|string',

        'customer.vehicle' => 'sometimes|array',
        'customer.vehicle.type' => 'sometimes|in:owned,rented,financed,none,other',
        'customer.vehicle.brand' => 'sometimes|string',
        'customer.vehicle.model' => 'sometimes|string',
        'customer.vehicle.year' => 'sometimes|integer|min:1900|max:' . date('Y'),
        'customer.vehicle.color' => 'sometimes|string',
        'customer.vehicle.plate_number' => 'sometimes|string',
        'customer.vehicle.owned' => 'sometimes|boolean',
        'customer.vehicle.rented' => 'sometimes|boolean',
        'customer.vehicle.leased' => 'sometimes|boolean',
        'customer.vehicle.shared' => 'sometimes|boolean',
        'customer.vehicle.financed' => 'sometimes|boolean',

      ]);

      DB::beginTransaction();
      try {
        // Update main loan application
        $loanApplication->update($validatedData);

        // Update customer related data if provided
        if ($request->has('customer')) {
          // Update customer details
          if ($request->has('customer.details')) {
            $loanApplication->customer->details()->update($request->input('customer.details'));
          }

          // Update company
          if ($request->has('customer.company')) {
            $loanApplication->customer->company()->update($request->input('customer.company'));
          }

          // Update job info
          if ($request->has('customer.job_info')) {
            $loanApplication->customer->jobInfo()->update($request->input('customer.job_info'));
          }

          // Update financial info
          if ($request->has('customer.financial_info')) {
            $loanApplication->customer->financialInfo()->update($request->input('customer.financial_info'));
          }

          // Update or sync references
          if ($request->has('customer.references')) {
            $loanApplication->customer->references()->sync($request->input('customer.references'));
          }

          // Update portfolio and related broker data
          if ($request->has('customer.portfolio')) {
            $loanApplication->customer->portfolio()->update($request->input('customer.portfolio'));
          }
        }

        // Update or sync risks
        if ($request->has('risks')) {
          $loanApplication->risks()->sync($request->input('risks'));
        }

        // Update or sync notes
        if ($request->has('notes')) {
          $loanApplication->notes()->sync($request->input('notes'));
        }

        // Update or sync vehicle info
        if ($request->has('customer.vehicle')) {
          $loanApplication->customer->vehicleInfo()->update($request->input('customer.vehicle'));
        }

        DB::commit();

        // Reload the model with all relationships
        $loanApplication->load([
          'details',
          'customer.details',
          'customer.company',
          'customer.jobInfo',
          'customer.financialInfo',
          'customer.references',
          'customer.portfolio.broker.user',
          'risks',
          'notes',
          'customer.vehicle'
        ]);

        return redirect()->route('loan-applications.show', $loanApplication->id)
          ->with('success', 'Loan application updated successfully');
      } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
      }
    } catch (ValidationException $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      Log::error('Loan Application update failed: ' . $e->getMessage());

      return response()->json([
        'status' => 'error',
        'message' => 'Failed to update loan application. ' . $e->getMessage()
      ], 500);
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
