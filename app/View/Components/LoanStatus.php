<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoanStatus extends Component
{
    public $status;

    public function __construct($status)
    {
        $this->status = strtolower($status);
    }

    public function render()
    {
        return view('components.loan-status');
    }
}