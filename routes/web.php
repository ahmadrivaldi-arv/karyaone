<?php

use App\Http\Controllers\PayrollController;
use App\Livewire\App\Attendance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('attendance', Attendance::class)->name('attendance');

Route::get('payroll/{payroll}/pdf', [PayrollController::class, 'pdf'])->name('payroll.pdf');
