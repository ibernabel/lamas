<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class LoanApplicationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $data = LoanApplication::with([
        'details:id,loan_application_id,amount',
        'customer.details:id,customer_id,first_name,last_name',
        'customer.company:id,name,customer_id',
        'customer.portfolio.broker.user:id,name',
      ])
        ->select('id', 'created_at', 'status', 'customer_id')
        ->latest()
        //->dump()
        ->get();
      //return response()->json([
      //  'status' => 'success',
      //  'data' => $loanApplications
      //], 200);
      $loanApplications = datatables()->of($data)->toJson();
      //return $loanApplications;
      return view('admin.loan-application', compact('loanApplications'));
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to fetch loan applications. ' . $e->getMessage()
      ], 500);
    }
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

      return response()->json([
        'status' => 'success',
        'data' => $loanApplication
      ], 200);
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
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
