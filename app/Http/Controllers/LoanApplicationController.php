<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $loanApplications = LoanApplication::latest()
                                        ->with([
                                          'details',
                                          'customer',
                                          'customer.details',
                                          'customer.company',
                                          'customer.jobInfo',
                                          'customer.financialInfo',
                                          'customer.references',
                                          'risks',
                                          'notes',
                                          ])

                                        ->first();
    return $loanApplications;
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
  public function show(string $id)
  {
    //
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
