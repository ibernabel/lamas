<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Http\Request;
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

      //dd($data->first()); // For debuging

      //return $data;
      return datatables()->of($data)
        ->addColumn('created_at', function ($row) {
          return $row->created_at;
        })
        ->editColumn('status', function ($row) {
          return __($row->status);
        })
        ->toJson();
      //return DB::getSchemaBuilder()->getColumnListing('loan_applications');

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
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
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
        // Añade más campos específicos de company

        'customer.job_info' => 'sometimes|array',
        'customer.financial_info' => 'sometimes|array',
        'customer.references' => 'sometimes|array',
        'customer.portfolio' => 'sometimes|array',

        'risks' => 'sometimes|array',
        'risks.*.type' => 'sometimes|string',
        'risks.*.level' => 'sometimes|string',

        'notes' => 'sometimes|array',
        'notes.*.content' => 'sometimes|string'
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
          'notes'
        ]);

        //return response()->json([
        //    'status' => 'success',
        //    'message' => 'Loan application updated successfully',
        //    'data' => $loanApplication
        //], 200);

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
    //
  }
}
