<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function pdf(Request $request, Payroll $payroll)
    {
        $pdf = Pdf::loadView('pdf.payroll', [
            'payroll' => $payroll
        ]);

        return $pdf->stream();
    }
}
