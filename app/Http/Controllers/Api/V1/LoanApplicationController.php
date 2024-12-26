<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $loanApplication = LoanApplication::with([
        'details',
        'customer' => function ($query) {
          $query->with([
            'details',
            'company',
            'jobInfo',
            'financialInfo',
            'references'
          ]);
        },
        'risks',
        'notes',
      ])
        ->latest()
        ->paginate(15);

      return response()->json([
        'status' => 'success',
        'data' => $loanApplication
      ], 200);
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
      $loanApplication = LoanApplication::with([
        'details',
        'customer' => function ($query) {
          $query->with([
            'details',
            'company',
            'jobInfo',
            'financialInfo',
            'references'
          ]);
        },
        'risks',
        'notes',
      ])->findOrFail($id);

      //return $loanApplication;
      return response()->json([
        'status' => 'success',
        'data' => $loanApplication
      ], 200);
    } catch (\Exception $e) {
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
